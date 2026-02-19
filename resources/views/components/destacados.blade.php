@props([
    'imagen',
    'titulo'
])

<div class="relative group rounded-3xl overflow-hidden shadow-xl">

    <img src="{{ asset('images/'.$imagen) }}"
        class="w-full h-80 object-cover group-hover:scale-110 transition duration-500">

    <div class="absolute inset-0 bg-linear-to-t from-black/70 to-transparent flex items-end p-8">

        <h3 class="text-white text-2xl font-bold">
            {{ $titulo }}
        </h3>

    </div>

</div>
