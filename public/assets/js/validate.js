(function(){
  // Ищем форму по классу
  const form = document.querySelector('.contact-form');
  if (!form) return;

  // Словарь проверок по полям (по id)
  const tests = {
    fio: (v) => /^([А-ЯЁ][а-яё]+\s+){2}[А-ЯЁ][а-яё]+$/.test(v.trim()),
    phone: (v) => /^\+7\d{10}$/.test(v.trim()),
    email: (v) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v.trim()),
    message: (v) => v.trim().length >= 10
  };

  // Сообщения об ошибках
  const messages = {
    fio: 'Укажите ФИО полностью: Фамилия Имя Отчество (с заглавных)',
    phone: 'Формат телефона: +7XXXXXXXXXX (11 цифр)',
    email: 'Введите корректный e-mail (name@example.com)',
    message: 'Сообщение должно быть не короче 10 символов'
  };
  // Поповер с подсказками формата ввода 
  // Создаём один общий блок-подсказку, который будет показываться
  // рядом со знаком "!" при наведении мыши.
  const popover = document.createElement('div'); // сам всплывающий блок
  popover.id = 'popover-help';
  popover.style.display = 'none';
  document.body.appendChild(popover);

  let popoverHideTimer = null; // таймер для скрытия поповера

  // Функция показа поповера около переданного элемента
  function showPopover(target, text){
    // очищаем старый таймер скрытия
    if (popoverHideTimer){
      clearTimeout(popoverHideTimer);
      popoverHideTimer = null;
    }

    popover.textContent = text; // текст подсказки

    // Получаем координаты элемента относительно окна
    const rect = target.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollLeft = window.pageXOffset || document.documentElement.scrollLeft;

    // Размещаем поповер справа от значка "!"
    popover.style.top = (rect.top + scrollTop - 10) + 'px';
    popover.style.left = (rect.right + scrollLeft + 10) + 'px';
    popover.style.display = 'block';
  }

  // Функция запуска таймера скрытия поповера через 3 секунды
  function hidePopoverLater(){
    if (popoverHideTimer){
      clearTimeout(popoverHideTimer);
    }
    popoverHideTimer = setTimeout(function(){
      popover.style.display = 'none';
    }, 3000); // 3 секунды
  }

  // Для каждого поля, для которого у нас есть сообщение в messages,
  // создаём небольшой значок "!" справа от поля.
  Object.keys(messages).forEach(function(id){
    const field = form.querySelector('#' + id); // сам input / textarea
    if (!field) return; // если поля нет, просто пропускаем

    // Создаём элемент-значок
    const icon = document.createElement('span');
    icon.className = 'field-help'; // класс для оформления кружка
    icon.textContent = '!';        // сам восклицательный знак
    icon.dataset.help = messages[id]; // сохраняем текст подсказки в data-атрибут

    // Вставляем значок сразу после поля ввода
    if (field.parentElement){
      field.parentElement.insertBefore(icon, field.nextSibling);
    }

    // При наведении мыши показываем поповер
    icon.addEventListener('mouseenter', function(){
      showPopover(icon, icon.dataset.help);
    });

    // Когда мышь уходит с иконки — запускаем таймер скрытия
    icon.addEventListener('mouseleave', hidePopoverLater);
  });

  // Если мышь зашла на поповер, не скрываем его
  popover.addEventListener('mouseenter', function(){
    if (popoverHideTimer){
      clearTimeout(popoverHideTimer);
      popoverHideTimer = null;
    }
  });

  // Если мышь ушла с поповера — тоже запускаем таймер скрытия
  popover.addEventListener('mouseleave', hidePopoverLater);
  //  конец блока поповера


  // Создаём span для ошибки после элемента 
  function ensureErrorNode(field){
    let node = field.parentElement.querySelector('.error-text');
    if (!node) {
      node = document.createElement('span');
      node.className = 'error-text';
      field.parentElement.appendChild(node);
    }
    return node;
  }

  // Валидация конкретного поля
  function validateField(field){
    const id = field.id;
    const value = field.value ?? '';
    const test = tests[id];
    const ok = typeof test === 'function' ? test(value) : (field.checkValidity?.() ?? true);

    // манипуляции DOM-классами
    field.classList.toggle('field--ok', ok);
    field.classList.toggle('field--err', !ok);

    const err = ensureErrorNode(field);
    // Текст под полем больше не показываем, так как подробная
    // подсказка теперь доступна во всплывающем блоке по знаку "!".
    err.textContent = '';

    return ok;
  }

  // Проверка всей формы: включает/отключает submit
  function updateSubmitState(){
    const required = form.querySelectorAll('[required]');
    const allOk = Array.from(required).every(validateField);
    const submit = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submit){
      submit.disabled = !allOk;
    }
    return allOk;
  }

  // Навешиваем обработчики blur и input
  form.addEventListener('blur', (e) => {
    const target = e.target;
    if (target instanceof HTMLInputElement || target instanceof HTMLTextAreaElement){
      validateField(target);
      updateSubmitState();
    }
  }, true);

  form.addEventListener('input', (e) => {
    const target = e.target;
    if (target instanceof HTMLInputElement || target instanceof HTMLTextAreaElement){
      validateField(target);
      updateSubmitState();
    }
  });

  // Первичная инициализация (после загрузки DOM)
  document.addEventListener('DOMContentLoaded', updateSubmitState);
})();



function validate(form) {
  let firstInvalid = null, errors = [];

  // для подписи
  const labelOf = (el) => {
    if (el.id) {
      const lf = form.querySelector(`label[for="${el.id}"]`);
      if (lf?.textContent?.trim()) return lf.textContent.trim();
    }
    const wrap = el.closest?.("label");
    if (wrap?.textContent?.trim()) return wrap.textContent.trim();
    const fs = el.closest?.("fieldset");
    if (fs) {
      const lg = fs.querySelector("legend");
      if (lg?.textContent?.trim()) return lg.textContent.trim();
    }
    const prev = el.previousElementSibling;
    if (prev && /^(P|H\d|LABEL)$/i.test(prev.tagName) && prev.textContent?.trim())
      return prev.textContent.trim();
    const ph = el.getAttribute?.("placeholder");
    return (ph && ph.trim()) || el.name || "Поле";
  };

  // собираем все required и имена групп для radio/checkbox
  const required = form.querySelectorAll("[required]");
  const groupNames = new Set();
  for (const el of required) {
    const type = (el.type || "").toLowerCase();
    const tag  = (el.tagName || "").toLowerCase();

    if (type === "radio" || type === "checkbox") {
      if (el.name) groupNames.add(el.name);
      continue;
    }

    if (tag === "SELECT") {
      if (!el.value) {
        !firstInvalid && (firstInvalid = el);
        errors.push(`${labelOf(el)} — выберите значение`);
      }
      continue;
    }

    if (!(el.value || "").trim()) {
      !firstInvalid && (firstInvalid = el);
      errors.push(`${labelOf(el)} — заполните поле`);
    }
  }

  // Проверяем группы radio/checkbox, где в группе есть required
  for (const name of groupNames) {
    const group = form.querySelectorAll(`input[name="${name}"][type="radio"], input[name="${name}"][type="checkbox"]`);
    const anyChecked = Array.from(group).some(x => x.checked);
    if (!anyChecked) {
      !firstInvalid && (firstInvalid = group[0]);
      errors.push(`${labelOf(group[0])} — выберите вариант`);
    }
  }

  // проверки по варианту
  // ФИО - три слова
  const fio = form.querySelector("#fio");
  if (fio) {
    const v = (fio.value || "").trim();
    const fioPattern = /^[A-Za-zА-Яа-яЁё][A-Za-zА-Яа-яЁё\-’']*\s[A-Za-zА-Яа-яЁё][A-Za-zА-Яа-яЁё\-’']*\s[A-Za-zА-Яа-яЁё][A-Za-zА-Яа-яЁё\-’']*$/;
    const threeWords = v.split(" ").length === 3 && !/\s{2,}/.test(v);
    if (!v || !(fioPattern.test(v) && threeWords)) {
      !firstInvalid && (firstInvalid = fio);
      errors.push("Фамилия Имя Отчество — введите три слова, разделённые одним пробелом (например: Иванов Иван Иванович)");
    }
  }

  // телефон
  const phone = form.querySelector("#phone");
  if (phone) {
    const p = (phone.value || "").trim();
    const phonePattern = /^\+(?:7)\d{8,10}$/;
    if (!phonePattern.test(p)) {
      !firstInvalid && (firstInvalid = phone);
      errors.push("Телефон — начинайте с «+7», без пробелов, только цифры; длина цифр 9–11 (например: +7123456789)");
    }
  }

  // вопрос 3 в тесте - только символы
  const q3 = form.querySelector("#q3");
  if (q3) {
    const t = (q3.value || "").trim();
    if (!t || /\d/.test(t)) {
      !firstInvalid && (firstInvalid = q3);
      errors.push("Вопрос 3 — введите символьное значение без цифр (например: двадцать три)");
    }
  }

  // результат
  if (errors.length) {
    alert(errors.join("\n"));
    firstInvalid?.focus?.();
    return false;
  }
  return true;
}

