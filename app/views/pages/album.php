<div class="card">
    <h2>Фотоальбом</h2>
    <section id="album" class="album-grid">
        <?php if (!empty($photos)): ?>
            <?php foreach ($photos as $photo): ?>
                <figure class="photo">
                    <img src="/public/assets/img/<?= htmlspecialchars($photo['src']) ?>"
                         alt="<?= htmlspecialchars($photo['caption']) ?>"
                         title="<?= htmlspecialchars($photo['caption']) ?>"
                         loading="lazy">
                    <figcaption><?= htmlspecialchars($photo['caption']) ?></figcaption>
                </figure>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Нет фотографий для отображения.</p>
        <?php endif; ?>
    </section>
</div>