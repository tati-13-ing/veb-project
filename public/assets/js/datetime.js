(function ($) {
  const MONTHS = [
    "Январь","Февраль","Март","Апрель","Май","Июнь",
    "Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"
  ];

  // Формат даты 
  function formatDate(d) {
    const dd = String(d.getDate()).padStart(2, "0");
    const mmName = MONTHS[d.getMonth()];
    const yy = String(d.getFullYear()).slice(-2);
    return `${dd} ${mmName} ${yy}`;
  }

  // Формат времени
  function formatTime(d) {
    const hh = String(d.getHours()).padStart(2, "0");
    const mi = String(d.getMinutes()).padStart(2, "0");
    const ss = String(d.getSeconds()).padStart(2, "0");
    return `${hh}:${mi}:${ss}`;
  }

  // Создать DOM-узел для даты/времени и вставить в меню
  function ensureClockNode() {
    const $nav = $("nav.nav");
    if ($nav.length === 0) return null;

    let $span = $nav.find("#nav-datetime");
    if ($span.length === 0) {
      $span = $('<span id="nav-datetime" class="nav__datetime" aria-live="polite"></span>');
      $nav.append($span);
    }

    return $span;
  }

  function render() {
    const $box = ensureClockNode();
    if (!$box) return;

    const now = new Date();
    const dateStr = formatDate(now);
    const timeStr = formatTime(now);

    $box.text(`${timeStr}  ${dateStr}`);
    $box.attr("title", `${dateStr}, ${timeStr}`);
  }

  // Запуск после загрузки DOM
  $(function () {
    render();
    setInterval(render, 1000);
  });

})(jQuery);
