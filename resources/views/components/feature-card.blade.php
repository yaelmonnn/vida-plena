@props(['title', 'description', 'icon', 'bgColor'])

<div class="text-center">
    <div class="w-24 h-24 {{ $bgColor }} rounded-full flex items-center justify-center mx-auto mb-6">
        {!! $icon !!}
    </div>
    <h3 class="text-2xl font-bold text-gray-800 mb-3">{{ $title }}</h3>
    <p class="text-gray-600">{{ $description }}</p>
</div>
