/**
 * product-card.js
 * Registra el componente Alpine.js para el carrusel de imágenes.
 *
 * Livewire carga Alpine internamente, por lo que usamos el evento
 * 'alpine:init' para registrar el componente en el momento correcto.
 *
 * Uso en la vista:
 *   <div x-data="productCard({{ $id }})"> ... </div>
 *
 * Requiere la ruta:
 *   GET /producto/{id}/imagenes
 *   → JSON: [{ ruta: "...", alt_text: "..." }, ...]
 */
document.addEventListener('alpine:init', () => {
    Alpine.data('productCard', (productId) => ({
        productId,
        images:  [],
        current: 0,
        loading: true,

        init() {
            fetch(`/producto/${productId}/imagenes`)
                .then(r => {
                    if (!r.ok) throw new Error(`HTTP ${r.status}`);
                    return r.json();
                })
                .then(data => {
                    this.images = data.map(img => ({
                        src: `/images/${img.ruta}`,
                        alt: img.alt_text || '',
                    }));
                })
                .catch(() => {
                    this.images = [];
                })
                .finally(() => {
                    this.loading = false;
                });
        },

        next() {
            this.current = (this.current + 1) % this.images.length;
        },

        prev() {
            this.current = (this.current - 1 + this.images.length) % this.images.length;
        },
    }));
});
