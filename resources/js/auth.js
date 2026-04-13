/**
 * resources/js/auth.js
 * Comportamiento compartido para ambos formularios de login.
 * Importar en los layouts que lo necesiten o en app.js.
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── Toggle password visibility ─────────────────────────────
    const toggleMap = [
        { btn: 'togglePw',    input: 'password',     icon: 'eyeIcon'    },  // login usuario
        { btn: 'admTogglePw', input: 'password',     icon: 'admEyeIcon' },  // login admin
    ];

    const fechaInput = document.getElementById('fecha_nacimiento');

    if (fechaInput) {
        fechaInput.addEventListener('change', () => {
            const fecha = new Date(fechaInput.value);
            const hoy = new Date();

            let edad = hoy.getFullYear() - fecha.getFullYear();
            const m = hoy.getMonth() - fecha.getMonth();

            if (m < 0 || (m === 0 && hoy.getDate() < fecha.getDate())) {
                edad--;
            }

            if (edad < 18) {
                alert('Debes ser mayor de edad');
                fechaInput.value = '';
            }
        });
    }

    toggleMap.forEach(({ btn, input, icon }) => {
        const btnEl   = document.getElementById(btn);
        const iconEl  = document.getElementById(icon);
        const inputEl = document.getElementById(input);

        if (!btnEl || !inputEl) return;

        btnEl.addEventListener('click', () => {
            const isPassword = inputEl.type === 'password';
            inputEl.type = isPassword ? 'text' : 'password';

            if (iconEl) {
                iconEl.classList.toggle('fa-eye',        !isPassword);
                iconEl.classList.toggle('fa-eye-slash',   isPassword);
            }
        });
    });

    // ── Loading state en submit ────────────────────────────────
    const forms = [
        { form: 'loginForm',  btn: 'submitBtn',    label: 'Iniciando sesión...' },
        { form: 'registerForm', btn: 'registerBtn', label: 'Creando cuenta...' },
    ];

    forms.forEach(({ form, btn, label }) => {
        const formEl = document.getElementById(form);
        const btnEl  = document.getElementById(btn);

        if (!formEl || !btnEl) return;

        formEl.addEventListener('submit', () => {
            btnEl.disabled = true;
            btnEl.style.opacity = '0.7';
            btnEl.innerHTML = `<i class="fa-solid fa-spinner fa-spin"></i> ${label}`;
        });
    });

    // ── Validación de contraseña en registro ───────────────────
    const pwInput        = document.getElementById('password');
    const pwConfirmInput = document.getElementById('reg_password_confirmation');
    const strengthBar    = document.getElementById('pwStrength');
    const strengthLabel  = document.getElementById('pwStrengthLabel');

    if (pwInput && strengthBar) {
        pwInput.addEventListener('input', () => {
            const val = pwInput.value;
            const score = calcPasswordStrength(val);

            const colors = ['#f87171', '#fb923c', '#facc15', '#4ade80', '#22c55e'];
            const labels = ['Muy débil', 'Débil', 'Regular', 'Buena', 'Excelente'];
            const widths = ['20%', '40%', '60%', '80%', '100%'];

            strengthBar.style.width           = score >= 0 ? widths[score] : '0%';
            strengthBar.style.backgroundColor = score >= 0 ? colors[score] : 'transparent';
            if (strengthLabel) {
                strengthLabel.textContent  = score >= 0 ? labels[score] : '';
                strengthLabel.style.color  = score >= 0 ? colors[score] : 'transparent';
            }
        });
    }

    if (pwInput && pwConfirmInput) {
        const checkMatch = () => {
            if (!pwConfirmInput.value) return;
            const match = pwInput.value === pwConfirmInput.value;
            pwConfirmInput.style.borderColor = match ? '#4ade80' : '#f87171';
        };
        pwConfirmInput.addEventListener('input', checkMatch);
        pwInput.addEventListener('input', checkMatch);
    }

    // ── Helpers ───────────────────────────────────────────────

    /**
     * Calcula fortaleza de contraseña 0-4.
     */
    function calcPasswordStrength(pw) {
        if (!pw) return -1;
        let score = 0;
        if (pw.length >= 8)  score++;
        if (pw.length >= 12) score++;
        if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
        if (/[0-9]/.test(pw)) score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return Math.min(score - 1, 4);
    }

});
