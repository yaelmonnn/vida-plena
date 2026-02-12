@props(['titulo','precio','imagen','categoria','rating' => 5])

<div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 group">

    <div class="relative overflow-hidden">
        <img src="{{ asset('images/'.$imagen) }}"
            class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">

        <span class="absolute top-4 left-4 bg-[#83d77c] text-white text-xs px-3 py-1 rounded-full">
            Nuevo
        </span>
    </div>

    <div class="p-6 space-y-3">

        <span class="text-xs font-semibold text-[#E28987]">
            {{ $categoria }}
        </span>

        <h3 class="text-lg font-bold">
            {{ $titulo }}
        </h3>

        <x-rating-stars :rating="$rating" />

        <div class="flex justify-between items-center pt-2">
            <span class="text-2xl font-bold text-[#E48F62]">
                ${{ $precio }}
            </span>

            <button
                class="bg-white border-2 border-[#E28987] text-[#E28987] p-3 rounded-xl">
                🛒
            </button>

        </div>

    </div>

</div>
