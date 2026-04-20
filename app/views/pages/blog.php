<div class="card">
    <div class="blog-header">
        <h2>Мой блог</h2>
        <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
            <a href="/admin/blog/editor" class="btn-editor"> Редактор блога</a>
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
                <?php $postComments = $commentsByPost[$post->id] ?? []; ?>
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
                    <div class="blog-comments-block">
                        <div class="blog-comments-top">
                            <div class="blog-comments-title-wrap">
                                <h4>Комментарии</h4>

                                <?php if (!isset($_SESSION['user_id'])): ?>
                                    <p class="comments-hint">Чтобы оставить комментарий, войдите в аккаунт.</p>
                                <?php endif; ?>
                            </div>

                            <?php if (isset($_SESSION['user_id'])): ?>
                                <button
                                    type="button"
                                    class="add-comment-btn"
                                    data-post-id="<?= (int)$post->id ?>"
                                    data-post-title="<?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?>">
                                    Добавить комментарий
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="blog-comment-status" id="blog-comment-status-<?= (int)$post->id ?>"></div>

                        <div class="comments-list" id="comments-list-<?= (int)$post->id ?>">
                            <?php if (empty($postComments)): ?>
                                <div class="no-comments" id="no-comments-<?= (int)$post->id ?>">
                                    Комментариев пока нет.
                                </div>
                            <?php else: ?>
                                <?php foreach ($postComments as $comment): ?>
                                    <div class="comment-item" id="comment-<?= (int)$comment->id ?>">
                                        <div class="comment-item-meta">
                                            <strong><?= htmlspecialchars($comment->author_name) ?></strong>
                                            <span><?= htmlspecialchars($comment->getFormattedDate()) ?></span>
                                        </div>
                                        <div class="comment-item-text"><?= nl2br(htmlspecialchars($comment->message)) ?></div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
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
    <div id="comment-modal-overlay" class="ajax-modal-overlay" hidden>
        <div class="ajax-modal-window">
            <div class="ajax-modal-header">
                <h3 id="comment-modal-title">Новый комментарий</h3>
            </div>

            <div class="ajax-modal-body">
                <p id="comment-modal-subtitle" class="muted"></p>
                <textarea id="comment-message-input" rows="7" placeholder="Введите комментарий..."></textarea>
                <div id="comment-modal-error" class="form-errors" style="display:none;"></div>
            </div>

            <div class="ajax-modal-actions">
                <button type="button" id="comment-modal-send">Отправить</button>
                <button type="button" id="comment-modal-cancel" class="btn-secondary">Отмена</button>
            </div>
        </div>
    </div>

    <script src="/public/assets/js/blog_comments.js" defer></script>
</div>

