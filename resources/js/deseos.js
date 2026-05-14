// resources/js/deseos.js

/* Set en memoria con los IDs en deseos del usuario actual */
window.__deseos = new Set();

/* Carga los IDs al iniciar (solo si está logueado) */
window.cargarDeseos = async function () {
    if (!(window.__auth ?? false)) return;
    try {
        const r = await fetch('/deseos/mis-ids', {
            headers: { 'Accept': 'application/json' },
        });
        const data = await r.json();
        window.__deseos = new Set(data.ids.map(Number));
        actualizarCorazonesDOM();
    } catch (_) {}
};

/* Retorna true si el producto ya está en deseos */
window.estaEnDeseos = function (productoId) {
    return window.__deseos.has(Number(productoId));
};

/* Pinta/despinta todos los corazones en el DOM */
function actualizarCorazonesDOM() {
    document.querySelectorAll('[data-deseo-btn]').forEach(btn => {
        const id = Number(btn.dataset.deseoBtn);
        pintarCorazon(btn, window.__deseos.has(id));
    });
}

function pintarCorazon(btn, activo) {
    const icon = btn.querySelector('i');
    if (!icon) return;
    if (activo) {
        icon.className = 'fa-solid fa-heart';   // relleno
        btn.classList.add('deseo-activo');
        btn.classList.remove('deseo-inactivo');
        btn.title = 'Quitar de favoritos';
    } else {
        icon.className = 'fa-regular fa-heart'; // outline
        btn.classList.remove('deseo-activo');
        btn.classList.add('deseo-inactivo');
        btn.title = 'Guardar en favoritos';
    }
}

function actualizarBadgeDeseos(total) {
    const badge = document.getElementById('deseos-badge');
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

/* Toggle principal */
window.toggleDeseo = function (productoId, btnEl) {
    const estaLogueado = window.__auth ?? false;

    if (!estaLogueado) {
        Swal.fire({
            icon: 'info',
            title: 'Inicia sesión',
            text: 'Debes iniciar sesión para guardar favoritos.',
            confirmButtonText: 'Iniciar sesión',
            confirmButtonColor: '#E48F62',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            cancelButtonColor: '#6b7280',
        }).then(r => { if (r.isConfirmed) window.location.href = '/login'; });
        return;
    }

    const btn = btnEl instanceof Element ? btnEl : null;
    if (btn) {
        btn.disabled = true;
        btn.classList.add('animate-pulse');
    }

    fetch('/deseos/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ producto_id: productoId }),
    })
    .then(r => r.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('animate-pulse');
        }

        if (data.ok) {
            // Actualiza set en memoria
            if (data.en_deseos) {
                window.__deseos.add(Number(productoId));
            } else {
                window.__deseos.delete(Number(productoId));
            }

            // Actualiza TODOS los corazones de ese producto en el DOM
            document.querySelectorAll(`[data-deseo-btn="${productoId}"]`).forEach(b => {
                pintarCorazon(b, data.en_deseos);
                // Animación corazón
                b.classList.add('heart-pop');
                setTimeout(() => b.classList.remove('heart-pop'), 500);
            });

            actualizarBadgeDeseos(data.total_deseos);

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: data.en_deseos ? 'success' : 'info',
                title: data.mensaje,
            });
        }
    })
    .catch(() => {
        if (btn) {
            btn.disabled = false;
            btn.classList.remove('animate-pulse');
        }
    });
};

/* Carga al iniciar la página y después de cada navegación Livewire */
document.addEventListener('DOMContentLoaded', window.cargarDeseos);
document.addEventListener('livewire:navigated', window.cargarDeseos);
