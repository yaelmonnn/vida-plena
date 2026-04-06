
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
