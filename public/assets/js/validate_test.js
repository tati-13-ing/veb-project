// scripts/validate_test.js
(function($) {
    // Глобальная функция валидации, вызываемая из атрибута onsubmit формы
    window.validate = function(formElement) {
        // Оборачиваем форму в jQuery-объект
        var $form = $(formElement);

        // --- Проверка ФИО ---
        var $fio = $form.find('#fio');
        var fioVal = $fio.val().trim();
        if (fioVal === '') {
            alert('Поле "ФИО" обязательно для заполнения.');
            $fio.focus();
            return false;
        }
        // Разрешены только буквы (русские/латинские), пробелы и дефисы
        if (!/^[а-яА-ЯёЁa-zA-Z\s\-]+$/.test(fioVal)) {
            alert('ФИО может содержать только буквы, пробелы и дефисы.');
            $fio.focus();
            return false;
        }

        // --- Проверка группы ---
        var $group = $form.find('#group');
        if (!$group.val()) { // если значение отсутствует (теоретически невозможно, но на всякий случай)
            alert('Выберите группу.');
            $group.focus();
            return false;
        }

        // --- Вопрос 1 (чекбоксы) ---
        var $q1checkboxes = $form.find('input[name="Q1_Графы"]');
        if ($q1checkboxes.filter(':checked').length === 0) {
            alert('Вопрос 1: выберите хотя бы один вариант.');
            // Устанавливаем фокус на первый чекбокс
            $q1checkboxes.first().focus();
            return false;
        }

        // --- Вопрос 2 (select) ---
        var $q2 = $form.find('select[name="Q2_Множества"]');
        if (!$q2.val()) {
            alert('Вопрос 2: выберите ответ.');
            $q2.focus();
            return false;
        }

        // --- Вопрос 3 (текстовое поле) ---
        var $q3 = $form.find('#q3');
        var q3Val = $q3.val().trim().toLowerCase();

        // Допустимые варианты ответа (только слова, никаких цифр)
        var validAnswers = ['тридцать два', 'тридцатьдва'];

        // Нормализуем пробелы: заменяем множественные пробелы на один
        var normalized = q3Val.replace(/\s+/g, ' ');

        if (q3Val === '') {
            alert('Вопрос 3: введите ответ.');
            $q3.focus();
            return false;
        }

        // Проверяем, что ответ не содержит цифр (чтобы исключить вариант "32")
        if (/\d/.test(q3Val)) {
            alert('Вопрос 3: ответ должен быть написан словами, не используйте цифры.');
            $q3.focus();
            return false;
        }


        // Если все проверки пройдены – разрешаем отправку формы
        return true;
    };
})(jQuery);