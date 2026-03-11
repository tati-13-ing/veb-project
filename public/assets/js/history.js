// Получение cookie по имени
function getCookie(name) {
  const matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([.$?*|{}()\[\]\\/+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : null;
}

function setCookie(name, value, expiration_days) {
  const d = new Date();
  d.setTime(d.getTime() + (expiration_days * 24 * 60 * 60 * 1000));
  const expires = "expires=" + d.toUTCString();
  document.cookie = name + "=" + encodeURIComponent(value) + "; " + expires + "; path=/";
}

window.HistoryTracker = (function($){
  const LS_KEY = 'sessionViews';     // ключ для LocalStorage (текущий сеанс)
  const CK_KEY = 'allTimeViews';     // ключ для Cookies (все время)

  // Прочитать объект из LocalStorage 
  function readSession() {
    try {
      const text = localStorage.getItem(LS_KEY);
      return text ? JSON.parse(text) : {};
    } catch(e) { return {}; }
  }

  // Сохранить объект в LocalStorage
  function writeSession(obj) {
    localStorage.setItem(LS_KEY, JSON.stringify(obj));
  }

  // Прочитать объект из Cookies 
  function readAllTime() {
    try {
      const text = getCookie(CK_KEY);
      return text ? JSON.parse(text) : {};
    } catch(e) { return {}; }
  }

  // Сохранить объект в Cookies 
  function writeAllTime(obj) {
    setCookie(CK_KEY, JSON.stringify(obj), 365);
  }

  // Увеличить счетчик для страницы
  function increment(map, key) {
    if (!map[key]) map[key] = 0;
    map[key] += 1;
  }

  // отслеживание имени страницы
  function detectPageKey() {
    const parts = location.pathname.split('/').filter(Boolean);
    const file = parts.length ? parts[parts.length - 1] : 'index.html';
    const title = document.title || file;
    return { key: file, title };
  }

  // зафиксировать просмотр страницы
  function trackPageView() {
    const { key } = detectPageKey();

    const s = readSession();
    increment(s, key);
    writeSession(s);

    const a = readAllTime();
    increment(a, key);
    writeAllTime(a);
  }

  // отрисовать таблицы на странице history.html
  function renderTables() {
  const sessionObj = readSession();
  const allTimeObj = readAllTime();

  // Словарь названий из навигационного меню
  const navTitles = {};
  $('nav a[href]').each(function() {
    const href = $(this).attr('href');
    if (href && !href.startsWith('http')) {
        navTitles[href] = $(this).text().trim();
    }
  });

  // Дополнительные названия для страниц, отсутствующих в меню
  const extraTitles = {
    'test-odm.html': 'Тест — Основы дискретной математики'
    // при необходимости можно добавить другие страницы
  };

  // Функция получения названия по имени файла
  function getTitle(file) {
    return navTitles[file] || extraTitles[file] || file;
  }

  // Отрисовка таблицы текущего сеанса
  const $sessionTbody = $('#session-tbody');
  if ($sessionTbody.length) {
    $sessionTbody.empty();
    const sessionKeys = Object.keys(sessionObj).sort();
    if (sessionKeys.length === 0) {
      $sessionTbody.append('<tr><td colspan="2" class="muted">Пока нет данных</td></tr>');
    } else {
      sessionKeys.forEach(function(file) {
        const $tr = $('<tr></tr>');
        $tr.html('<td>' + getTitle(file) + '</td><td>' + sessionObj[file] + '</td>');
        $sessionTbody.append($tr);
      });
    }
  }

  // Отрисовка таблицы за всё время
  const $allTimeTbody = $('#alltime-tbody');
  if ($allTimeTbody.length) {
    $allTimeTbody.empty();
    const allTimeKeys = Object.keys(allTimeObj).sort();
    if (allTimeKeys.length === 0) {
      $allTimeTbody.append('<tr><td colspan="2" class="muted">Пока нет данных</td></tr>');
    } else {
      allTimeKeys.forEach(function(file) {
        const $tr = $('<tr></tr>');
        $tr.html('<td>' + getTitle(file) + '</td><td>' + allTimeObj[file] + '</td>');
        $allTimeTbody.append($tr);
      });
    }
  }
}

  function resetSession() { localStorage.removeItem(LS_KEY); }
  function resetAllTime() { setCookie(CK_KEY, "{}", 365); }

  return { trackPageView, renderTables, resetSession, resetAllTime, getCookie, setCookie };
})(jQuery);

// фиксируем просмотр страницы после загрузки DOM
$(function(){
  window.HistoryTracker.trackPageView();
});
