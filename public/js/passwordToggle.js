// パスワード表示
(() => {
    const passwordToggle = document.querySelectorAll('.js-password-toggle');

    for (let i = 0; i < 1; i++) {
        passwordToggle[i].addEventListener('change', function () {
            const password = document.querySelectorAll('.js-password'),
                passwordLabel = document.querySelectorAll('.js-password-label');
            if (password[i].type === 'password') {
                password[i].type = 'text';
                passwordLabel[i].innerHTML = '<i class="fas fa-eye-slash fa-lg"></i>';
            } else {
                password[i].type = 'password';
                passwordLabel[i].innerHTML = '<i class="fas fa-eye fa-lg"></i>';
            }
            password[i].focus();
        });
    }
})();