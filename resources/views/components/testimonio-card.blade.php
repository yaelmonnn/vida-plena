@props([
    'rating' => 5,
    'texto',
    'autor',
    'color' => '#E48F62'
])

<div class="bg-white p-8 rounded-3xl shadow-xl hover:-translate-y-3 transition">

    <x-rating-stars :rating="$rating" />

    <p class="mt-4 text-gray-600">
        "{{ $texto }}"
    </p>

    <div class="mt-4 font-semibold" style="color: {{ $color }}">
        — {{ $autor }}
    </div>

</div>
