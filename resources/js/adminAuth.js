/**
 * resources/js/adminAuth.js
 * Comportamiento del formulario de login administrativo.
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle password visibility ─────────────────────────────
    const toggleBtn = document.getElementById('togglePw');
    const pwInput   = document.getElementById('password');
    const eyeIcon   = document.getElementById('eyeIcon');

    if (toggleBtn && pwInput) {
        toggleBtn.addEventListener('click', () => {
            const isPassword = pwInput.type === 'password';
            pwInput.type = isPassword ? 'text' : 'password';

            if (eyeIcon) {
                eyeIcon.classList.toggle('fa-eye',      !isPassword);
                eyeIcon.classList.toggle('fa-eye-slash', isPassword);
            }
        });
    }

    // ── Loading state en submit ────────────────────────────────
    const formEl = document.querySelector('form[action*="admin/login"]');
    const btnEl  = formEl?.querySelector('.auth-btn');

    if (formEl && btnEl) {
        formEl.addEventListener('submit', () => {
            btnEl.disabled = true;
            btnEl.style.opacity = '0.7';
            btnEl.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> Accediendo...`;
        });
    }

});
