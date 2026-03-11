<section class="card">
    <h2>История текущего сеанса</h2>
    <p class="muted">Показывает, сколько раз вы открывали каждую страницу за время текущего визита.</p>
    <div class="table-wrap">
        <table class="history-table" id="session-table">
            <thead>
                <tr>
                    <th>Страница</th>
                    <th>Посещения</th>
                </tr>
            </thead>
            <tbody id="session-tbody">
                <tr>
                    <td colspan="2" class="muted">Пока нет данных</td>
                </tr>
            </tbody>
        </table>
    </div>
    <button class="btn" id="btn-reset-session" type="button">Сбросить историю сеанса</button>
</section>

<section class="card">
    <h2>История за всё время</h2>
    <p class="muted">Счётчик суммарных посещений страниц.</p>
    <div class="table-wrap">
        <table class="history-table" id="alltime-table">
            <thead>
                <tr>
                    <th>Страница</th>
                    <th>Посещения</th>
                </tr>
            </thead>
            <tbody id="alltime-tbody">
                <tr>
                    <td colspan="2" class="muted">Пока нет данных</td>
                </tr>
            </tbody>
        </table>
    </div>
    <button class="btn" id="btn-reset-alltime" type="button">Сбросить историю за всё время</button>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        if (window.HistoryTracker) {
            HistoryTracker.renderTables();
            document.getElementById("btn-reset-session")?.addEventListener("click", function () {
                HistoryTracker.resetSession();
                HistoryTracker.renderTables();
            });
            document.getElementById("btn-reset-alltime")?.addEventListener("click", function () {
                HistoryTracker.resetAllTime();
                HistoryTracker.renderTables();
            });
        }
    });
</script>