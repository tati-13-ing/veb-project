<div class="card">
    <h2>Результаты тестирования</h2>
    
    <?php if (empty($results)): ?>
        <p class="muted">Пока нет результатов тестирования.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>ФИО</th>
                        <th>Группа</th>
                        <th>Вопрос 1</th>
                        <th>Вопрос 2</th>
                        <th>Вопрос 3</th>
                        <th>Результат</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $result): ?>
                        <tr>
                            <td><?= htmlspecialchars($result->getFormattedDate()) ?></td>
                            <td><?= htmlspecialchars($result->fio) ?></td>
                            <td><?= htmlspecialchars($result->group_name) ?></td>
                            <td class="<?= $result->q1_result === 'Верно' ? 'correct' : 'incorrect' ?>">
                                <?= htmlspecialchars($result->q1_result) ?>
                            </td>
                            <td class="<?= $result->q2_result === 'Верно' ? 'correct' : 'incorrect' ?>">
                                <?= htmlspecialchars($result->q2_result) ?>
                            </td>
                            <td class="<?= $result->q3_result === 'Верно' ? 'correct' : 'incorrect' ?>">
                                <?= htmlspecialchars($result->q3_result) ?>
                            </td>
                            <td><?= $result->getTotalResult() ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Пагинация -->
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
        
        <p class="muted">Всего результатов: <?= $totalResults ?></p>
    <?php endif; ?>
</div>

<style>
.results-table {
    width: 100%;
    border-collapse: collapse;
}

.results-table th,
.results-table td {
    padding: 10px 12px;
    border-bottom: 1px solid var(--gray-700);
    text-align: left;
}

.results-table th {
    background: var(--bg);
}

.correct {
    color: #4ade80;
}

.incorrect {
    color: #f87171;
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