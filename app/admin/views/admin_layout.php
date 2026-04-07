<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($title) ?> – Админка</title>
    <link rel="stylesheet" href="/public/assets/css/styles.css">
</head>
<body>
    <header>
        <div class="wrapper">
            <h1>Панель администратора</h1>
            <nav class="nav">
                <a href="/admin/blog/editor">Редактор блога</a>
                <a href="/admin/guestbook/upload">Загрузка сообщений гостевой книги</a>
                <a href="/admin/statistics">Статистика посещений</a>
                <a href="/admin/auth/logout">Выход из админки</a>
            </nav>
        </div>
    </header>
    <div class="wrapper">
        <?php include $content_file; ?>
    </div>
    <footer>
        <div class="wrapper">
            <small>&copy; Административная панель</small>
        </div>
    </footer>
</body>
</html>