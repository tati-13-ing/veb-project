<div class="card">
    <h2>Загрузка сообщений гостевой книги</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <?php if (isset($success) && $success > 0): ?>
        <div class="form-success">
            Успешно загружено сообщений: <?= $success ?>
        </div>
    <?php endif; ?>
    
    <p>Формат файла .inc: каждая строка содержит данные в формате:</p>
    <pre>Дата;ФИО;E-mail;Текст сообщения</pre>
    <p>Пример: 15.12.2026;Иванов Иван Иванович;ivan@example.com;Привет всем!</p>
    
    <form method="post" action="/guestbook/processUpload" enctype="multipart/form-data" class="contact-form">
        <div class="row">
            <label for="messages_file">Файл сообщений (.inc)</label>
            <input type="file" id="messages_file" name="messages_file" accept=".inc" required>
        </div>
        
        <div class="row buttons">
            <button type="submit">Загрузить</button>
            <a href="/guestbook" class="button-link">Назад к гостевой книге</a>
        </div>
    </form>
</div>

<style>
.button-link {
    display: inline-block;
    padding: 10px 14px;
    background: var(--gray-700);
    color: var(--text);
    text-decoration: none;
    border-radius: 10px;
    text-align: center;
}

pre {
    background: var(--bg);
    padding: 10px;
    border-radius: 8px;
    overflow-x: auto;
}
</style>