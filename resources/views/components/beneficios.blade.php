@props([
    'icono',
    'titulo',
    'descripcion',
    'color' => '#83d77c'
])

<div class="p-10 rounded-3xl shadow-lg hover:shadow-2xl transition hover:-translate-y-3"
     style="background-color: {{ $color }}1A">

    <div class="text-5xl mb-4" style="color: {{ $color }}">
        <i class="{{ $icono }}"></i>
    </div>

    <h3 class="font-bold text-xl mb-3">
        {{ $titulo }}
    </h3>

    <p class="text-gray-600">
        {{ $descripcion }}
    </p>

</div>
