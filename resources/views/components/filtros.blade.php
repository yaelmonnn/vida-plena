<section class="max-w-7xl mx-auto px-6 py-12 reveal">

    <div class="grid lg:grid-cols-4 gap-10">

        <!-- SIDEBAR FILTROS -->
        <div class="bg-white p-6 rounded-3xl shadow-xl space-y-8 h-fit text-base">

            <!-- Buscar -->
            <div>
                <label class="font-semibold text-lg">Buscar</label>
                <input type="text"
                    placeholder="Ej. barandal, reloj..."
                    class="mt-2 w-full border rounded-xl px-4 py-3 focus:ring-2 focus:ring-[#E48F62] outline-none">
            </div>

            <!-- Categoría -->
            <div>
                <label class="font-semibold text-lg">Categoría</label>
                <div class="mt-3 space-y-3 text-base">
                    @foreach ($categorias as $c)
                        <label class="flex items-center gap-2">
                            <input type="checkbox">
                            <i class="{{ $c->icono }}"></i> {{ $c->categoria }}
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Precio -->
            <div>
                <label class="font-semibold text-lg">Rango de precio</label>
                <input type="range" min="0" max="10000"
                    class="w-full mt-3 accent-[#E28987]">
                <div class="flex justify-between text-sm text-gray-500">
                    <span>$0</span>
                    <span>$10,000</span>
                </div>
            </div>

            <!-- Disponibilidad -->
            <div>
                <label class="font-semibold text-lg">Disponibilidad</label>
                <div class="mt-3 space-y-3 text-base">
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> En stock
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox"> En promoción
                    </label>
                </div>
            </div>

            <button class="w-full bg-[#ffbb51] text-white py-3 rounded-xl font-semibold text-lg hover:bg-[#E48F62] transition">
                Aplicar filtros
            </button>

        </div>

        <!-- PRODUCTOS -->
        <div class="lg:col-span-3">
            <x-product-grid />
        </div>

    </div>

</section>
