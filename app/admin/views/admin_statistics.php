<div class="card">
    <h2>Статистика посещений</h2>
    
    <?php if (empty($stats)): ?>
        <p class="muted">Пока нет данных</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="results-table">
                <thead>
                    <tr><th>Дата/время</th><th>Страница</th><th>IP</th><th>Хост</th><th>Браузер</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($stats as $s): ?>
                        <tr>
                            <td><?= htmlspecialchars($s->getFormattedDateTime()) ?></td>
                            <td><?= htmlspecialchars($s->page_url) ?></td>
                            <td><?= htmlspecialchars($s->ip_address) ?></td>
                            <td><?= htmlspecialchars($s->host_name) ?></td>
                            <td><?= htmlspecialchars($s->browser_name) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="pagination-current"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?>" class="pagination-link"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        <?php endif; ?>
        <p class="muted">Всего записей: <?= $totalRecords ?></p>
    <?php endif; ?>
</div>