<div class="card">
    <h2>Загрузка записей блога из CSV файла</h2>
    
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
            Успешно загружено записей: <?= $success ?>
        </div>
    <?php endif; ?>
    
    <p><strong>Формат CSV файла:</strong></p>
    <pre>title,message,author,created_at
"Моя первая запись","Текст сообщения","Иванов Иван","2026-12-15 14:30:00"
"Вторая запись","Еще один текст","Петрова Анна","2026-12-16 10:15:00"</pre>
    
    <p><strong>Примечания:</strong></p>
    <ul>
        <li>Поля: заголовок, текст, автор, дата создания (опционально)</li>
        <li>Разделитель: запятая (,) или точка с запятой (;)</li>
        <li>Кодировка: UTF-8</li>
        <li>Автор и дата могут быть пустыми</li>
    </ul>
    
    <form method="post" action="/blog/processUpload" enctype="multipart/form-data" class="contact-form">
        <div class="row">
            <label for="csv_file">CSV файл</label>
            <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
        </div>
        
        <div class="row buttons">
            <button type="submit">Загрузить</button>
            <a href="/blog" class="button-link">Назад к блогу</a>
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
    font-size: 12px;
}
ul {
    margin: 10px 0;
    padding-left: 20px;
}
li {
    margin: 5px 0;
}
</style>