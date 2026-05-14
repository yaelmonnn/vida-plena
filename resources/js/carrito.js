// resources/js/carrito.js

/* ─── helpers ─────────────────────────────────────────────── */
function csrf() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

function actualizarBadgeCarrito(total) {
    const badge = document.getElementById('carrito-badge');
    if (!badge) return;
    badge.textContent = total;
    if (total > 0) {
        badge.classList.remove('hidden');
        badge.classList.add('badge-pop');
        setTimeout(() => badge.classList.remove('badge-pop'), 400);
    } else {
        badge.classList.add('hidden');
    }
}

/* ─── animación en el botón ───────────────────────────────── */
function animarBotonCarrito(btn) {
    if (!btn) return;
    btn.disabled = true;
    btn.classList.add('btn-loading');
    const icon = btn.querySelector('i');
    if (icon) {
        icon.className = 'fa-solid fa-spinner fa-spin';
    }
    return () => {
        btn.disabled = false;
        btn.classList.remove('btn-loading');
        if (icon) {
            icon.className = 'fa-solid fa-cart-shopping';
            // Animación "rebote" al completar
            btn.classList.add('btn-success-anim');
            setTimeout(() => btn.classList.remove('btn-success-anim'), 600);
        }
    };
}

/* ─── agregar al carrito ──────────────────────────────────── */
window.agregarAlCarrito = function (productoId, btnEl) {
    const estaLogueado = window.__auth ?? false;

    if (!estaLogueado) {
        Swal.fire({
            icon: 'info',
            title: 'Inicia sesión',
            text: 'Debes iniciar sesión para agregar productos al carrito.',
            confirmButtonText: 'Iniciar sesión',
            confirmButtonColor: '#E48F62',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#6b7280',
        }).then(r => { if (r.isConfirmed) window.location.href = '/login'; });
        return;
    }

    // Encontrar el botón desde el elemento que se pasó o buscar el más cercano
    const btn = btnEl instanceof Element ? btnEl : null;
    const restaurar = animarBotonCarrito(btn);

    fetch('/carrito/agregar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf(),
        },
        body: JSON.stringify({ producto_id: productoId, cantidad: 1 }),
    })
    .then(r => {
        if (!r.ok) return r.json().then(d => Promise.reject(d));
        return r.json();
    })
    .then(data => {
        if (restaurar) restaurar();

        if (data.ok) {
            actualizarBadgeCarrito(data.total_items);

            // Toast elegante (no bloquea la UI)
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                didOpen: (t) => {
                    t.addEventListener('mouseenter', Swal.stopTimer);
                    t.addEventListener('mouseleave', Swal.resumeTimer);
                },
            });
            Toast.fire({ icon: 'success', title: '¡Agregado al carrito!' });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'No disponible',
                text: data.message,
                confirmButtonColor: '#E48F62',
            });
        }
    })
    .catch(err => {
        if (restaurar) restaurar();
        const msg = err?.message ?? 'No se pudo agregar al carrito.';
        Swal.fire({
            icon: 'error',
            title: 'Oops',
            text: msg,
            confirmButtonColor: '#E48F62',
        });
    });
};
