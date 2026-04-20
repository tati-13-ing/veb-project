document.addEventListener('DOMContentLoaded', function () {
  const loginInput = document.getElementById('registration-login');
  const checkButton = document.getElementById('check-login-btn');
  const resultNode = document.getElementById('login-check-result');
  const hiddenInput = document.getElementById('login-check-hidden');
  const hiddenForm = document.getElementById('login-check-form');
  const iframe = document.getElementById('registrationLoginFrame');
  const submitButton = document.getElementById('registration-submit');

  if (!loginInput || !checkButton || !resultNode || !hiddenInput || !hiddenForm || !iframe) {
    return;
  }

  let requestedLogin = '';

  function showMessage(text, ok) {
    resultNode.textContent = text;
    resultNode.classList.remove('is-ok', 'is-error', 'is-loading');

    if (ok === true) {
      resultNode.classList.add('is-ok');
      if (submitButton) submitButton.disabled = false;
    } else if (ok === false) {
      resultNode.classList.add('is-error');
      if (submitButton) submitButton.disabled = true;
    }
  }

  function checkLogin() {
    const login = loginInput.value.trim();
    requestedLogin = login;

    if (login === '') {
      showMessage('Введите логин для проверки.', false);
      return;
    }

    hiddenInput.value = login;
    resultNode.textContent = 'Проверка...';
    resultNode.classList.remove('is-ok', 'is-error');
    resultNode.classList.add('is-loading');
    hiddenForm.submit();
  }

  checkButton.addEventListener('click', checkLogin);
  loginInput.addEventListener('blur', checkLogin);

  loginInput.addEventListener('input', function () {
    resultNode.textContent = '';
    resultNode.classList.remove('is-ok', 'is-error', 'is-loading');
    if (submitButton) submitButton.disabled = false;
  });

  iframe.addEventListener('load', function () {
    let raw = '';

    try {
      raw = iframe.contentWindow.document.body.textContent || '';
    } catch (e) {
      showMessage('Не удалось прочитать ответ сервера.', false);
      return;
    }

    raw = raw.trim();
    if (!raw) return;

    try {
      const data = JSON.parse(raw);

      if (requestedLogin !== loginInput.value.trim()) {
        return;
      }

      showMessage(data.message || 'Проверка завершена.', !!data.available);
    } catch (e) {
      showMessage('Сервер вернул некорректный JSON.', false);
    }
  });
});