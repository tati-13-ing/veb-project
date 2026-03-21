<div class="card">
    <div class="blog-header">
        <h2>Мой блог</h2>
        <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
            <a href="/blog/editor" class="btn-editor">✏️ Редактор блога</a>
        <?php endif; ?>
    </div>
    
    <div class="blog-info">
        <p>Добро пожаловать в мой блог! Здесь я делюсь своими мыслями, интересами и новостями.</p>
    </div>
    
    <?php if (empty($posts)): ?>
        <p class="muted">Пока нет записей. Загляните позже!</p>
    <?php else: ?>
        <div class="blog-posts">
            <?php foreach ($posts as $post): ?>
                <article class="blog-post">
                    <div class="blog-post-header">
                        <h3><?= htmlspecialchars($post->title) ?></h3>
                        <div class="blog-post-meta">
                            <span class="post-date"> <?= htmlspecialchars($post->getFormattedDate()) ?></span>
                            <span class="post-author"> <?= htmlspecialchars($post->author) ?></span>
                        </div>
                    </div>
                    
                    <?php if (!empty($post->image_path)): ?>
                        <div class="blog-post-image">
                            <img src="/public/<?= htmlspecialchars($post->image_path) ?>" 
                                 alt="<?= htmlspecialchars($post->title) ?>"
                                 loading="lazy">
                        </div>
                    <?php endif; ?>
                    
                    <div class="blog-post-content">
                        <?= nl2br(htmlspecialchars($post->message)) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <!-- Пагинация -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>" class="pagination-link">&laquo; Предыдущая</a>
                <?php endif; ?>
                
                <?php
                $start = max(1, $currentPage - 2);
                $end = min($totalPages, $currentPage + 2);
                
                if ($start > 1): ?>
                    <a href="?page=1" class="pagination-link">1</a>
                    <?php if ($start > 2): ?>
                        <span class="pagination-dots">…</span>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="pagination-current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>" class="pagination-link"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($end < $totalPages): ?>
                    <?php if ($end < $totalPages - 1): ?>
                        <span class="pagination-dots">…</span>
                    <?php endif; ?>
                    <a href="?page=<?= $totalPages ?>" class="pagination-link"><?= $totalPages ?></a>
                <?php endif; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="pagination-link">Следующая &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <p class="muted">Всего записей: <?= $totalPosts ?></p>
    <?php endif; ?>
</div>

<style>
.blog-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.btn-editor {
    padding: 8px 16px;
    background: var(--accent);
    color: var(--accent-contrast);
    text-decoration: none;
    border-radius: 8px;
}
.btn-editor:hover {
    filter: brightness(1.1);
}
.blog-info {
    background: var(--bg);
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
}
.blog-post {
    border: 1px solid var(--gray-700);
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    background: var(--bg);
    transition: transform 0.2s ease;
}
.blog-post:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px var(--shadow-blue);
}
.blog-post-header {
    border-bottom: 1px solid var(--gray-700);
    margin-bottom: 15px;
    padding-bottom: 10px;
}
.blog-post-header h3 {
    margin: 0 0 8px 0;
    color: var(--accent);
}
.blog-post-meta {
    display: flex;
    gap: 15px;
    font-size: 12px;
    color: var(--muted);
}
.blog-post-image {
    margin: 15px 0;
    text-align: center;
}
.blog-post-image img {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    object-fit: cover;
}
.blog-post-content {
    line-height: 1.6;
    margin-top: 15px;
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
.pagination-dots {
    padding: 6px 12px;
    color: var(--muted);
}
</style>