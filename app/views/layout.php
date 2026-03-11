<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/public/assets/css/styles.css" />
    <link rel="icon" href="/public/assets/img/favicon-32x32.png" sizes="32x32" type="image/png" />
    <link rel="icon" href="/public/assets/img/favicon-16x16.png" sizes="16x16" type="image/png" />
    <link rel="apple-touch-icon" href="/public/assets/img/apple-touch-icon.png" />
    <link rel="icon" href="/public/assets/img/android-chrome-192x192.png" sizes="192x192" type="image/png" />
    <link rel="icon" href="/public/assets/img/android-chrome-512x512.png" sizes="512x512" type="image/png" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <meta name="theme-color" content="#ffffff" />
</head>
<body>
    <header>
        <div class="wrapper">
            <h1>Персональный сайт Татьяны</h1>
            <nav class="nav">
                <a href="/">Главная</a>
                <a href="/about">Обо мне</a>
                <a href="/interests">Мои интересы</a>
                <a href="/study">Учеба</a>
                <a href="/test">Тест</a>
                <a href="/album">Фотоальбом</a>
                <a href="/contacts">Контакты</a>
                <a href="/history">История</a>
            </nav>
            <!-- Выпадающее меню "Мои интересы" будет добавлено в само представление interests.php, т.к. оно специфично для этой страницы -->
        </div>
    </header>

    <div class="wrapper">
        <?php include $content_view; ?>
    </div>

    <footer>
        <div class="wrapper">
            <small>&copy; тут подвал</small>
        </div>
    </footer>

    <!-- Общие скрипты -->
    <script src="/public/assets/js/datetime.js" defer></script>
    <script src="/public/assets/js/history.js" defer></script>
    <script src="/public/assets/js/photoalbum.js" defer></script
</body>
</html>