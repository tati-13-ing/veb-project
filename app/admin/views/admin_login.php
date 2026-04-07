<div class="card" style="max-width: 400px; margin: 50px auto;">
    <h2>Вход в панель администратора</h2>
    
    <?php if (isset($_SESSION['admin_login_error'])): ?>
        <div class="form-errors"><?= htmlspecialchars($_SESSION['admin_login_error']) ?></div>
        <?php unset($_SESSION['admin_login_error']); ?>
    <?php endif; ?>
    
    <form method="post" action="/admin/auth/login" class="contact-form">
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
            <a href="/" class="button-link">На главную</a>
        </div>
    </form>
</div>
