<div class="card">
    <h2>Регистрация</h2>
    
    <?php if (!empty($errors)): ?>
        <div class="form-errors">
            <ul><?php foreach ($errors as $error): ?><li><?= htmlspecialchars($error) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>
    
    <form method="post" action="/registration/register" class="contact-form">
        <div class="row">
            <label>ФИО *</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
        </div>
        <div class="row">
            <label>E-mail *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
        </div>
        <div class="row">
            <label>Логин *</label>
            <input type="text" name="login" value="<?= htmlspecialchars($old['login'] ?? '') ?>" required>
        </div>
        <div class="row">
            <label>Пароль *</label>
            <input type="password" name="password" required>
        </div>
        <div class="row">
            <label>Подтверждение пароля *</label>
            <input type="password" name="password_confirm" required>
        </div>
        <div class="row buttons">
            <button type="submit">Зарегистрироваться</button>
            <a href="/authorization" class="button-link">Уже есть аккаунт? Войти</a>
        </div>
    </form>
</div>