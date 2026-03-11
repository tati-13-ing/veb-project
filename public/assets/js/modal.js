$(function () {

    // Ищем форму и элементы модального окна
    const $form = $('.contact-form');            // форма на странице контактов
    if ($form.length === 0) return;              // если формы нет — выходим

    const $submitBtn = $form.find('button[type="submit"]'); 
    const $modal = $('#modal-overlay');          // фон + окно
    const $btnYes = $('#modal-yes');             // кнопка "Да"
    const $btnNo = $('#modal-no');               // кнопка "Нет"

    // Проверка наличия всех элементов
    if ($submitBtn.length === 0 || $modal.length === 0 || 
        $btnYes.length === 0 || $btnNo.length === 0) return;


    // ---------- Клик по кнопке "Отправить" ----------
    $submitBtn.on('click', function (e) {
        e.preventDefault(); // отменяем стандартную отправку формы

        // Проверяем валидацию (если функция существует)
        if (typeof validate === 'function') {
            const ok = validate($form[0]); // validate принимает обычный DOM-элемент
            if (!ok) return; // если ошибка — модалку не показываем
        }

        // Показываем модальное окно
        $modal.addClass('modal-overlay--visible');
    });


    // ---------- Кнопка "Нет" ----------
    $btnNo.on('click', function () {
        $modal.removeClass('modal-overlay--visible');
    });


    // ---------- Кнопка "Да" ----------
    $btnYes.on('click', function () {
        $modal.removeClass('modal-overlay--visible');
        $form.trigger('submit'); // отправка формы через jQuery
    });


    // ---------- Клик по тёмному фону ----------
    $modal.on('click', function (e) {
        // Проверяем, что кликнули именно по overlay, а не по содержимому
        if ($(e.target).is('#modal-overlay')) {
            $modal.removeClass('modal-overlay--visible');
        }
    });

});
