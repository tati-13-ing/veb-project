<div class="card">
    <h2>Вход</h2>
    
    <?php if (!empty($error)): ?>
        <div class="form-errors"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="post" action="/authorization/login" class="contact-form">
        <div class="row">
            <label>Логин</label>
            <input type="text" name="login" required>
        </div>
        <div class="row">
            <label>Пароль</label>
            <input type="password" name="password" required>
        </div>
        <div class="row buttons">
            <button type="submit">Войти</button>
            <a href="/registration" class="button-link">Зарегистрироваться</a>
        </div>
    </form>
</div>