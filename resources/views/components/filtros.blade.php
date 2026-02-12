<section class="max-w-7xl mx-auto px-6 py-12 reveal">

    <div class="grid lg:grid-cols-4 gap-10">

        <!-- SIDEBAR FILTROS -->
        <div class="bg-white p-6 rounded-3xl shadow-xl space-y-8 h-fit">

            <h3 class="text-xl font-bold flex items-center gap-2">
                🔎 Filtros
            </h3>

            <!-- Buscar -->
            <div>
                <label class="font-semibold text-sm">Buscar</label>
                <input type="text"
                    placeholder="Ej. barandal, reloj..."
                    class="mt-2 w-full border rounded-xl px-4 py-2 focus:ring-2 focus:ring-[#E48F62] outline-none">
            </div>

            <!-- Categoría -->
            <div>
                <label class="font-semibold text-sm">Categoría</label>
                <div class="mt-2 space-y-2 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> 🏡 Hogar Seguro
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> 🩺 Rehabilitación
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> ⌚ Tecnología
                    </label>
                </div>
            </div>

            <!-- Precio -->
            <div>
                <label class="font-semibold text-sm">Rango de precio</label>
                <input type="range" min="0" max="10000"
                    class="w-full mt-3 accent-[#E28987]">
                <div class="flex justify-between text-xs text-gray-500">
                    <span>$0</span>
                    <span>$10,000</span>
                </div>
            </div>

            <!-- Disponibilidad -->
            <div>
                <label class="font-semibold text-sm">Disponibilidad</label>
                <div class="mt-2 space-y-2 text-sm">
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> En stock
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> En promoción
                    </label>
                </div>
            </div>

            <button class="w-full bg-[#ffbb51] text-white py-3 rounded-xl font-semibold hover:bg-[#E48F62] transition">
                Aplicar filtros
            </button>

        </div>

        <!-- PRODUCTOS -->
        <div class="lg:col-span-3">
            <x-product-grid />
        </div>

    </div>

</section>
