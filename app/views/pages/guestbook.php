<div class="card">
    <h2>Гостевая книга</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="form-success">Сообщение успешно добавлено!</div>
    <?php endif; ?>
    
    <!-- Форма добавления сообщения -->
    <form method="post" action="/guestbook/save" class="contact-form">
        <div class="row">
            <label for="last_name">Фамилия *</label>
            <input type="text" id="last_name" name="last_name" required>
        </div>
        
        <div class="row">
            <label for="first_name">Имя *</label>
            <input type="text" id="first_name" name="first_name" required>
        </div>
        
        <div class="row">
            <label for="middle_name">Отчество</label>
            <input type="text" id="middle_name" name="middle_name">
        </div>
        
        <div class="row">
            <label for="email">E-mail *</label>
            <input type="email" id="email" name="email" required>
        </div>
        
        <div class="row">
            <label for="message">Сообщение *</label>
            <textarea id="message" name="message" rows="5" required></textarea>
        </div>
        
        <div class="row buttons">
            <button type="submit">Отправить</button>
            <button type="reset">Очистить</button>
        </div>
    </form>
    
    <hr>
    
    <!-- Список сообщений -->
    <h3>Сообщения посетителей</h3>
    
    <?php if (empty($messages)): ?>
        <p class="muted">Пока нет сообщений. Будьте первым!</p>
    <?php else: ?>
        <div class="messages-list">
            <?php foreach ($messages as $msg): ?>
                <div class="message-item">
                    <div class="message-header">
                        <strong><?= htmlspecialchars($msg->getFullName()) ?></strong>
                        <span class="message-date"><?= htmlspecialchars($msg->getFormattedDate()) ?></span>
                    </div>
                    <div class="message-email">
                        <small><?= htmlspecialchars($msg->email) ?></small>
                    </div>
                    <div class="message-text">
                        <?= nl2br(htmlspecialchars($msg->message)) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.message-item {
    border: 1px solid var(--gray-700);
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 15px;
    background: var(--bg);
}

.message-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
    flex-wrap: wrap;
    gap: 8px;
}

.message-date {
    font-size: 12px;
    color: var(--muted);
}

.message-email {
    margin-bottom: 10px;
}

.message-text {
    line-height: 1.5;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid var(--gray-700);
}

.form-success {
    background: var(--ok);
    color: white;
    padding: 10px 15px;
    border-radius: 8px;
    margin-bottom: 15px;
}
</style>