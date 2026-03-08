@props(['titulo','precio','imagen','categoria','estado','rating','descripcion'])

<div class="bg-white rounded-3xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300 group">

    <div class="relative overflow-hidden">
        <img src="{{ asset('images/'.$imagen) }}"
            class="w-full h-64 object-cover group-hover:scale-110 transition duration-500">

    </div>

    <div class="p-6 space-y-3">

        <div class="flex justify-between items-center">

            <span class="text-xs font-semibold text-[#E28987]">
                {{ $categoria }}
            </span>

            <span class="bg-[#83d77c] text-white font-bold text-xs px-3 py-1 rounded-full">
                {{ $estado }}
            </span>

        </div>

        <h3 class="text-lg font-bold">
            {{ $titulo }}
        </h3>

        <p class="text-gray-600">
            {{ $descripcion }}
        </p>

        <x-rating-stars :rating="$rating" />

        <div class="flex justify-between items-center pt-2">
            <span class="text-2xl font-bold text-[#E48F62]">
                ${{ $precio }}
            </span>


            <div class="flex items-center gap-2 pt2">
                <button
                    class="bg-white border-2 border-[#E28987] text-[#E28987] p-3 rounded-xl
                        hover:bg-[#E28987] hover:text-white transition duration-300">

                    <i class="fa-solid fa-cart-shopping"></i>

                </button>

                <button
                    class="bg-white border-2 border-[#ffbb51] text-[#ffbb51] p-3 rounded-xl
                        hover:bg-[#ffbb51] hover:text-white transition">
                    <i class="fa-regular fa-heart"></i>
                </button>
            </div>



        </div>

    </div>

</div>
