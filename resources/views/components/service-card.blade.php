@props(['title', 'gradient', 'icon'])

<div class="bg-linear-to-br rounded-2xl p-8 shadow-xl hover:shadow-2xl transition duration-300 transform hover:-translate-y-2" style="background: {{ $gradient }}">
    <div class="flex flex-col items-center text-center text-white">
        <div class="mb-4">
            {!! $icon !!}
        </div>
        <h3 class="text-2xl font-bold">{{ $title }}</h3>
    </div>
</div>
