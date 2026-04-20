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
        <div class="row row--top">
        <label for="registration-login">Логин *</label>

        <div class="ajax-login-area">
            <div class="ajax-login-check-wrap">
                <input
                    type="text"
                    id="registration-login"
                    name="login"
                    value="<?= htmlspecialchars($old['login'] ?? '') ?>"
                    autocomplete="username"
                    required
                >

                <button type="button" id="check-login-btn" class="ajax-inline-button">
                    Проверить занятость
                </button>
            </div>

            <div id="login-check-result" class="ajax-check-result" aria-live="polite"></div>
        </div>
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
            <button type="submit" id="registration-submit">Зарегистрироваться</button>
            <a href="/authorization" class="button-link">Уже есть аккаунт? Войти</a>
        </div>
    </form>
    <div class="ajax-hidden-area" aria-hidden="true">
        <form method="post"
            action="/registration/checklogin"
            target="registrationLoginFrame"
            id="login-check-form"
            style="display:none !important;">
            <input type="hidden" name="login" id="login-check-hidden">
        </form>

        <iframe
            name="registrationLoginFrame"
            id="registrationLoginFrame"
            class="ajax-hidden-frame"
            hidden
            tabindex="-1"
            style="display:none !important; width:0 !important; height:0 !important; border:0 !important; position:absolute !important; left:-9999px !important; top:-9999px !important;"
        ></iframe>
    </div>

    <script src="/public/assets/js/registration_ajax.js" defer></script>
</div>