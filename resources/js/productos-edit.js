// ── Sidebar mobile ──

let table = new DataTable("#myTable");

const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
document.getElementById("menuToggle").addEventListener("click", () => {
    sidebar.classList.add("open");
    overlay.style.display = "block";
});

function closeSidebar() {
    sidebar.classList.remove("open");
    overlay.style.display = "none";
}

// ── Tabs ──
function switchTab(tab) {
    const isAdd = tab === "add";
    document.getElementById("panel-add").classList.toggle("hidden", !isAdd);
    document.getElementById("panel-search").classList.toggle("hidden", isAdd);
    document.getElementById("tab-add").className =
        (isAdd ? "tab-active" : "tab-inactive") +
        " flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200";
    document.getElementById("tab-search").className =
        (!isAdd ? "tab-active" : "tab-inactive") +
        " flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200";
    if (!isAdd) document.getElementById("editForm").classList.add("hidden");
}

// ── Editar ──
function loadEdit(id) {
    const row = document.querySelector(`tr[data-id="${id}"]`);

    document.getElementById("edit-nombre").value = row.dataset.nombre;
    document.getElementById("edit-precio").value = row.dataset.precio; // ahora es numérico puro
    document.getElementById("edit-cantidad").value = row.dataset.cantidad;
    document.getElementById("edit-descripcion").value = row.dataset.descripcion;
    document.getElementById("edit-tipo").value = row.dataset.tipo;

    // Categoría por ID (más confiable que por texto)
    document.querySelector('#formEditar select[name="categoria_id"]').value =
        row.dataset.categoriaId;

    // Estado por ID
    document.getElementById("edit-estado").value = row.dataset.estadoId;

    // Actions
    document.getElementById("formEditar").action =
        `{{ url('admin/productos') }}/${id}`;
    document.getElementById("formEliminar").action =
        `{{ url('admin/productos') }}/${id}`;

    // Botón eliminar
    document.getElementById("btnEliminar").dataset.id = id;
    document.getElementById("btnEliminar").dataset.nombre = row.dataset.nombre;

    document.getElementById("editForm").classList.remove("hidden");
    document.getElementById("editForm").scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

function closeEdit() {
    document.getElementById("editForm").classList.add("hidden");
}

// ── Eliminar con SweetAlert ──
document.getElementById("btnEliminar").addEventListener("click", function () {
    const nombre = this.dataset.nombre;

    Swal.fire({
        title: "¿Eliminar producto?",
        html: `<span class="text-gray-600 text-sm">Estás a punto de eliminar <strong>${nombre}</strong>. Esta acción no se puede deshacer.</span>`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#e05c3a",
        cancelButtonColor: "#6b7280",
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById("formEliminar").submit();
        }
    });
});

function closeEdit() {
    document.getElementById("editForm").classList.add("hidden");
}

// ── Preview imágenes ──
document.getElementById("fileInput")?.addEventListener("change", function () {
    const container = document.getElementById("previewContainer");
    container.innerHTML = "";
    [...this.files].forEach((file) => {
        const reader = new FileReader();
        reader.onload = (e) => {
            const img = document.createElement("img");
            img.src = e.target.result;
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});

