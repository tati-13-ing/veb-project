<?php
$messageForData = htmlspecialchars($post->message, ENT_QUOTES, 'UTF-8');
$titleForData = htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8');
?>
<div class="admin-post-item" data-post-id="<?= (int)$post->id ?>">
    <div class="admin-post-header">
        <div class="admin-post-title">
            <strong class="admin-post-item-title"><?= htmlspecialchars($post->title) ?></strong>
            <span class="post-id">ID: <?= (int)$post->id ?></span>
        </div>

        <div class="admin-post-actions">
            <a href="/admin/blog/edit?id=<?= (int)$post->id ?>"
               class="btn-edit js-open-blog-edit"
               data-id="<?= (int)$post->id ?>"
               data-title="<?= $titleForData ?>"
               data-message="<?= $messageForData ?>">
                Изменить
            </a>

            <a href="/admin/blog/delete?id=<?= (int)$post->id ?>"
               class="btn-delete"
               onclick="return confirm('Удалить запись «<?= htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') ?>»?')">
                Удалить
            </a>
        </div>
    </div>

    <div class="admin-post-meta">
        <span><?= htmlspecialchars($post->getFormattedDate()) ?></span>
        <span><?= htmlspecialchars($post->author) ?></span>
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