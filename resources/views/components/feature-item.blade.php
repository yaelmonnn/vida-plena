@props(['icono', 'titulo', 'descripcion', 'color' => '#83D77C'])

<div class="p-6 text-center">

    <div class="text-6xl mb-4 transition transform hover:scale-110" style="color: {{ $color }}">
        <i class="{{ $icono }}"></i>
    </div>

    <h4 class="font-semibold mb-2">
        {{ $titulo }}
    </h4>

    <p class="text-gray-500 text-sm">
        {{ $descripcion }}
    </p>

</div>
