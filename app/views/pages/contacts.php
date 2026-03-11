<div class="card">
    <h2>Обратная связь</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="form-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form class="contact-form" action="/contacts" method="post" novalidate>
        <div class="row">
            <label for="fio">Фамилия Имя Отчество</label>
            <input id="fio" name="ФИО" type="text" placeholder="Иванов Иван Иванович" required autocomplete="off" />
        </div>
        <div class="row">
            <label for="phone">Телефон</label>
            <input id="phone" name="Телефон" type="text" placeholder="+7XXXXXXXXX" required autocomplete="off" />
        </div>
        <div class="row">
            <label for="birthday">Дата рождения</label>
            <input id="birthday" name="Дата рождения" type="text" placeholder="дд.мм.гггг" readonly />
        </div>
        <fieldset class="row gender">
            <legend>Пол</legend>
            <div class="options">
                <label><input type="radio" name="Пол" value="Женский" required /> Женский</label>
                <label><input type="radio" name="Пол" value="Мужской" /> Мужской</label>
            </div>
        </fieldset>
        <div class="row">
            <label for="age">Возраст</label>
            <select id="age" name="Возраст" required>
                <option value="" selected disabled>Выберите возраст</option>
                <option>16–20</option>
                <option>21–25</option>
                <option>26–30</option>
                <option>31–40</option>
                <option>41+</option>
            </select>
        </div>
        <div class="row">
            <label for="email">E-mail</label>
            <input id="email" name="Email" type="email" placeholder="name@example.com" required autocomplete="off" />
        </div>
        <div class="row">
            <label for="message">Сообщение</label>
            <textarea id="message" name="Сообщение" rows="5" placeholder="Ваше сообщение..." required autocomplete="off"></textarea>
        </div>
        <div class="row buttons">
            <button type="submit">Отправить</button>
            <button type="reset">Очистить форму</button>
        </div>
    </form>
</div>

<!-- Модальное окно (из оригинального contacts.html) -->
<div id="modal-overlay" class="modal-overlay" aria-hidden="true">
    <div class="modal-window">
        <p class="modal-text">Вы действительно хотите это сделать?</p>
        <div class="modal-buttons">
            <button type="button" id="modal-yes">Да</button>
            <button type="button" id="modal-no">Нет</button>
        </div>
    </div>
</div>

<script src="/public/assets/js/calendar.js" defer></script>
<script src="/public/assets/js/validate.js" defer></script>
<script src="/public/assets/js/modal.js" defer></script>