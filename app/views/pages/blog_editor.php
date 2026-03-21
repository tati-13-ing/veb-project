<div class="card">
    <div class="editor-header">
        <h2> Редактор блога</h2>
        <div class="editor-links">
            <a href="/blog" class="btn-view">Просмотр блога</a>
            <a href="/blog/editor" class="btn-refresh">Обновить</a>
        </div>
    </div>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="form-success"> Запись успешно добавлена!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['updated'])): ?>
        <div class="form-success">Запись успешно обновлена!</div>
    <?php endif; ?>
    
    <?php if (isset($_GET['deleted'])): ?>
        <div class="form-success">Запись успешно удалена!</div>
    <?php endif; ?>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    
    <!-- Форма добавления новой записи -->
    <div class="add-form">
        <h3>Добавить новую запись</h3>
        <form method="post" action="/blog/save" enctype="multipart/form-data" class="contact-form">
            <div class="row">
                <label for="title"> Тема сообщения *</label>
                <input type="text" id="title" name="title" 
                       value="<?= htmlspecialchars($oldData['title'] ?? '') ?>" 
                       placeholder="Введите заголовок..." required>
            </div>
            
            <div class="row">
                <label for="author"> Автор</label>
                <input type="text" id="author" name="author" 
                       value="<?= htmlspecialchars($oldData['author'] ?? 'Администратор') ?>">
            </div>
            
            <div class="row">
                <label for="image"> Изображение</label>
                <input type="file" id="image" name="image" accept="image/*">
                <small class="hint">Поддерживаются: JPG, PNG, GIF, WEBP</small>
            </div>
            
            <div class="row">
                <label for="message"> Текст сообщения *</label>
                <textarea id="message" name="message" rows="8" 
                          placeholder="Введите текст сообщения..." required><?= htmlspecialchars($oldData['message'] ?? '') ?></textarea>
            </div>
            
            <div class="row buttons">
                <button type="submit" class="btn-primary"> Опубликовать</button>
                <button type="reset" class="btn-secondary"> Очистить</button>
            </div>
        </form>
    </div>
    
    <hr>
    
    <!-- Список всех записей -->
    <div class="posts-list">
        <h3> Все записи блога</h3>
        
        <?php if (empty($posts)): ?>
            <p class="muted">Пока нет записей. Создайте первую запись!</p>
        <?php else: ?>
            <div class="admin-posts">
                <?php foreach ($posts as $post): ?>
                    <div class="admin-post-item">
                        <div class="admin-post-header">
                            <div class="admin-post-title">
                                <strong><?= htmlspecialchars($post->title) ?></strong>
                                <span class="post-id">ID: <?= $post->id ?></span>
                            </div>
                            <div class="admin-post-actions">
                                <a href="/blog/edit?id=<?= $post->id ?>" class="btn-edit"> Редактировать</a>
                                <a href="/blog/delete?id=<?= $post->id ?>" 
                                   class="btn-delete" 
                                   onclick="return confirm('Удалить запись «<?= htmlspecialchars($post->title) ?>»?')">
                                    Удалить
                                </a>
                            </div>
                        </div>
                        <div class="admin-post-meta">
                            <span> <?= htmlspecialchars($post->getFormattedDate()) ?></span>
                            <span> <?= htmlspecialchars($post->author) ?></span>
                        </div>
                        <div class="admin-post-preview">
                            <?php if (!empty($post->image_path)): ?>
                                <img src="/public/<?= htmlspecialchars($post->image_path) ?>" 
                                     alt="<?= htmlspecialchars($post->title) ?>"
                                     class="admin-post-image">
                            <?php endif; ?>
                            <div class="admin-post-excerpt">
                                <?= nl2br(htmlspecialchars($post->getExcerpt(100))) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Пагинация для редактора -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>" class="pagination-link">&laquo; Предыдущая</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="pagination-current"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?>" class="pagination-link"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>" class="pagination-link">Следующая &raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <p class="muted">Всего записей: <?= $totalPosts ?></p>
        <?php endif; ?>
    </div>
</div>

<style>
.editor-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 10px;
}
.editor-links {
    display: flex;
    gap: 10px;
}
.btn-view, .btn-refresh {
    padding: 8px 16px;
    background: var(--gray-700);
    color: var(--text);
    text-decoration: none;
    border-radius: 8px;
}
.btn-view:hover, .btn-refresh:hover {
    background: var(--accent);
    color: var(--accent-contrast);
}
.add-form {
    background: var(--bg);
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}
.admin-posts {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.admin-post-item {
    background: var(--bg);
    border: 1px solid var(--gray-700);
    border-radius: 12px;
    padding: 15px;
}
.admin-post-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 10px;
}
.admin-post-title {
    display: flex;
    align-items: center;
    gap: 10px;
}
.post-id {
    font-size: 12px;
    color: var(--muted);
}
.admin-post-actions {
    display: flex;
    gap: 10px;
}
.btn-edit, .btn-delete {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
}
.btn-edit {
    background: var(--accent);
    color: var(--accent-contrast);
}
.btn-delete {
    background: var(--error-red);
    color: white;
}
.admin-post-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: var(--muted);
    margin-bottom: 10px;
}
.admin-post-preview {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}
.admin-post-image {
    max-width: 100px;
    max-height: 80px;
    object-fit: cover;
    border-radius: 8px;
}
.admin-post-excerpt {
    flex: 1;
    font-size: 14px;
    color: var(--text);
}
.btn-primary {
    background: var(--accent);
    color: var(--accent-contrast);
}
.btn-secondary {
    background: var(--gray-700);
    color: var(--text);
}
.pagination {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 20px;
    flex-wrap: wrap;
}
.pagination-link,
.pagination-current {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    background: var(--bg);
    color: var(--text);
}
.pagination-current {
    background: var(--accent);
    color: var(--accent-contrast);
}
.pagination-link:hover {
    background: var(--gray-700);
}
</style>