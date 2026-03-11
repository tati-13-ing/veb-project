<div class="card">
    <h2>Тест по дисциплине «Основы дискретной математики»</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($results)): ?>
        <div class="test-results">
            <h3>Результаты:</h3>
            <p>Вопрос 1: <?= $results['q1'] ?></p>
            <p>Вопрос 2: <?= $results['q2'] ?></p>
            <p>Вопрос 3: <?= $results['q3'] ?></p>
        </div>
    <?php else: ?>

    <form method="post" action="/test" novalidate>
        <fieldset class="card">
            <legend>Данные студента</legend>
            <label for="fio">ФИО</label>
            <input id="fio" name="ФИО" type="text" required autocomplete="off"/>
            <label for="group">Группа</label>
            <select id="group" name="Группа" required>
                <optgroup label="1 курс">
                    <option>ИC-25-1</option>
                    <option>ИС-25-2</option>
                </optgroup>
                <optgroup label="2 курс">
                    <option>ИС-24-1</option>
                    <option>ИС-24-2</option>
                </optgroup>
            </select>
        </fieldset>

        <fieldset class="card">
            <legend>Вопрос 1</legend>
            <p>Какие из перечисленных структур являются графами в дискретной математике?</p>
            <label><input type="checkbox" name="Q1_Графы[]" value="Ориентированный граф" /> Ориентированный граф</label>
            <label><input type="checkbox" name="Q1_Графы[]" value="Неориентированный граф" /> Неориентированный граф</label>
            <label><input type="checkbox" name="Q1_Графы[]" value="Дерево" /> Дерево</label>
        </fieldset>

        <fieldset class="card">
            <legend>Вопрос 2</legend>
            <p>Выберите истинное утверждение о множествах.</p>
            <select name="Q2_Множества" required>
                <optgroup label="Отношения и операции">
                    <option>Операция объединения A ∪ B содержит элементы, входящие хотя бы в одно множество</option>
                    <option>Пересечение A ∩ B — элементы, входящие только в A</option>
                    <option>Разность A \ B равна B \ A для любых A и B</option>
                </optgroup>
                <optgroup label="Специальные множества">
                    <option>Пустое множество содержит один элемент</option>
                    <option>Пустое множество является подмножеством любого множества</option>
                    <option>Множество натуральных чисел конечно</option>
                    <option>Декартово произведение всегда равно объединению</option>
                </optgroup>
            </select>
        </fieldset>

        <fieldset class="card">
            <legend>Вопрос 3</legend>
            <p>Введите значение 2<sup>5</sup> (напишите ответ словами):</p>
            <input id="q3" type="text" name="Q3_Ответ" required autocomplete="off"/>
        </fieldset>

        <div class="grid">
            <button type="submit">Отправить</button>
            <button type="reset">Очистить форму</button>
        </div>
    </form>
    <?php endif; ?>
</div>

<script src="/public/assets/js/validate_test.js" defer></script>