(function ($) {
  const pad2 = (n) => (n < 10 ? "0" + n : "" + n);

  const MONTHS = [
    "Январь","Февраль","Март","Апрель","Май","Июнь",
    "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"
  ];
  const DOW = ["Пн","Вт","Ср","Чт","Пт","Сб","Вс"];

  function formatRu(d) {
    return pad2(d.getDate()) + "." + pad2(d.getMonth() + 1) + "." + d.getFullYear();
  }

  function createCalendar($input) {
    const $backdrop = $('<div class="calendar__backdrop"></div>');
    const $root = $('<div class="calendar"></div>');

    const $header = $('<div class="calendar__header"></div>');

    const $monthSel = $('<select class="calendar__select"></select>');
    $.each(MONTHS, function (i, m) {
      $monthSel.append($('<option></option>').val(i).text(m));
    });

    const $yearSel = $('<select class="calendar__select"></select>');
    const thisYear = new Date().getFullYear();
    for (let y = thisYear + 5; y >= 1900; y--) {
      $yearSel.append($('<option></option>').val(y).text(y));
    }

    const $closeBtn = $('<button type="button" class="calendar__close">Готово</button>');

    $header.append($monthSel, $yearSel, $closeBtn);

    const $grid = $('<div class="calendar__grid"></div>');

    // Заголовки дней недели
    $.each(DOW, function (_, d) {
      $('<div class="calendar__dow"></div>').text(d).appendTo($grid);
    });

    $root.append($header, $grid);
    $('body').append($backdrop, $root);

    const today = new Date();
    let viewYear = today.getFullYear();
    let viewMonth = today.getMonth();
    let selected = null;

    // Инициализация из значения input
    (function initFromInput() {
      const val = $input.val();
      const m = val.match(/^(\d{2})\.(\d{2})\.(\d{4})$/);
      if (m) {
        const d = new Date(parseInt(m[3], 10), parseInt(m[2], 10) - 1, parseInt(m[1], 10));
        if (!isNaN(d)) {
          selected = d;
          viewYear = d.getFullYear();
          viewMonth = d.getMonth();
        }
      }
      $monthSel.val(String(viewMonth));
      $yearSel.val(String(viewYear));
    })();

    function render() {
      // Оставляем только 7 заголовков дней недели
      while ($grid.children().length > 7) {
        $grid.children().last().remove();
      }

      const firstDay = new Date(viewYear, viewMonth, 1);
      const startIdx = (firstDay.getDay() + 6) % 7;
      const daysInMonth = new Date(viewYear, viewMonth + 1, 0).getDate();

      // Предыдущие дни (мутные)
      for (let i = 0; i < startIdx; i++) {
        const d = new Date(viewYear, viewMonth, -(startIdx - 1 - i)).getDate();
        $('<div class="calendar__day calendar__day--muted"></div>')
          .text(d)
          .appendTo($grid);
      }

      // Текущий месяц
      for (let day = 1; day <= daysInMonth; day++) {
        const $btn = $('<div class="calendar__day"></div>').text(String(day));

        if (
          day === today.getDate() &&
          viewMonth === today.getMonth() &&
          viewYear === today.getFullYear()
        ) {
          $btn.addClass("calendar__day--today");
        }

        if (
          selected &&
          day === selected.getDate() &&
          viewMonth === selected.getMonth() &&
          viewYear === selected.getFullYear()
        ) {
          $btn.addClass("calendar__day--selected");
        }

        $btn.on("click", function () {
          selected = new Date(viewYear, viewMonth, day);
          $input.val(formatRu(selected));
          render();
        });

        $btn.appendTo($grid);
      }

      // Хвост следующего месяца (мутные)
      const total = startIdx + daysInMonth;
      const tail = (7 - (total % 7)) % 7;
      for (let i = 1; i <= tail; i++) {
        const d = new Date(viewYear, viewMonth + 1, i).getDate();
        $('<div class="calendar__day calendar__day--muted"></div>')
          .text(d)
          .appendTo($grid);
      }
    }

    $monthSel.on("change", function () {
      viewMonth = parseInt($monthSel.val(), 10);
      render();
    });

    $yearSel.on("change", function () {
      viewYear = parseInt($yearSel.val(), 10);
      render();
    });

    function hide() {
      $backdrop.removeClass("calendar__backdrop--show");
      $root.removeClass("calendar--open");
    }

    function show() {
      $backdrop.addClass("calendar__backdrop--show");
      $root.addClass("calendar--open");
      render();
    }

    $closeBtn.on("click", hide);
    $backdrop.on("click", hide);

    return { show, hide };
  }

  // DOM готов
  $(function () {
    const $input = $('#birthday');
    if ($input.length === 0) return;

    const calendar = createCalendar($input);

    $input.on('focus', function () {
      calendar.show();
    });

    $input.on('click', function () {
      calendar.show();
    });

    $(document).on('keydown', function (e) {
      if (e.key === 'Escape') {
        calendar.hide();
      }
    });
  });
})(jQuery);
