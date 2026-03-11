// Мои интересы (выпадающее меню)
(function ($) {
  const $root = $('.dropdown');
  const $toggle = $('#interests-toggle');
  const $menu = $('#interests-menu');

  if ($root.length === 0 || $toggle.length === 0 || $menu.length === 0) return;

  function closeAll() {
    $root.removeClass('dropdown--open');
  }

  // Клик по кнопке — открыть / закрыть
  $toggle.on("click", function (e) {
    e.stopPropagation();
    $root.toggleClass("dropdown--open");
  });

  // Клик вне меню — закрыть
  $(document).on("click", function (e) {
    if (!$root.has(e.target).length) {
      closeAll();
    }
  });

  // Клик по элементам меню — закрыть + плавный переход
  $menu.on("click", "a", function (e) {
    closeAll();

    try {
      const targetId = $(this).attr("href");
      const $target = $(targetId);
      if ($target.length) {
        $target[0].scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    } catch (_) {}
  });

})(jQuery);