<div class="card">
    <div class="editor-header">
        <h2> Редактирование записи</h2>
        <a href="/blog/editor" class="btn-refresh">← Назад к списку</a>
    </div>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <form method="post" action="/blog/update" enctype="multipart/form-data" class="contact-form">
        <input type="hidden" name="id" value="<?= $post->id ?>">
        
        <div class="row">
            <label for="title"> Тема сообщения *</label>
            <input type="text" id="title" name="title" 
                   value="<?= htmlspecialchars($post->title) ?>" required>
        </div>
        
        <div class="row">
            <label for="author">Автор</label>
            <input type="text" id="author" name="author" 
                   value="<?= htmlspecialchars($post->author) ?>">
        </div>
        
        <?php if (!empty($post->image_path)): ?>
            <div class="row">
                <label> Текущее изображение</label>
                <div class="current-image">
                    <img src="/public/<?= htmlspecialchars($post->image_path) ?>" 
                         alt="<?= htmlspecialchars($post->title) ?>"
                         style="max-width: 200px; border-radius: 8px;">
                    <p><small>Загрузите новое, чтобы заменить</small></p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <label for="image">Новое изображение (опционально)</label>
            <input type="file" id="image" name="image" accept="image/*">
            <small class="hint">Оставьте пустым, чтобы сохранить текущее изображение</small>
        </div>
        
        <div class="row">
            <label for="message"> Текст сообщения *</label>
            <textarea id="message" name="message" rows="10" required><?= htmlspecialchars($post->message) ?></textarea>
        </div>
        
        <div class="row buttons">
            <button type="submit" class="btn-primary"> Сохранить изменения</button>
            
        </div>
    </form>
</div>

<style>
.current-image {
    background: var(--bg);
    padding: 10px;
    border-radius: 8px;
    text-align: center;
}
</style>