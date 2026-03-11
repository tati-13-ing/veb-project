<!-- Выпадающее меню -->
<ul class="dropdown">
    <li class="dropdown__item">
        <button class="dropdown__toggle" id="interests-toggle" type="button">
            Мои интересы
        </button>
        <ul class="dropdown__menu" id="interests-menu">
            <?php foreach ($categories as $key => $title): ?>
                <li><a class="dropdown__link" href="#<?= $key ?>"><?= htmlspecialchars($title) ?></a></li>
            <?php endforeach; ?>
        </ul>
    </li>
</ul>

<!-- Блоки интересов, созданные циклами по данным модели -->
<?php foreach ($items as $key => $interestList): ?>
    <article class="card_int" id="<?= $key ?>">
        <header><h3><?= htmlspecialchars($categories[$key] ?? $key) ?></h3></header>
        <?php if ($key === 'hobby' || $key === 'music'): ?>
            <ol>
            <?php foreach ($interestList as $item): ?>
                <li>
                    <?= htmlspecialchars($item['name']) ?>
                    <?php if (!empty($item['description'])): ?>
                        <br><small><?= htmlspecialchars($item['description']) ?></small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ol>
        <?php else: ?>
            <ul>
            <?php foreach ($interestList as $item): ?>
                <li>
                    <?= htmlspecialchars($item['name']) ?>
                    <?php if (!empty($item['description'])): ?>
                        <br><small><?= htmlspecialchars($item['description']) ?></small>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </article>
<?php endforeach; ?>

<script src="/public/assets/js/interests.js" defer></script>