@props(['title', 'price', 'image'])

<div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition duration-300">
    <div class="p-8">
        <div class="bg-white rounded-xl p-8 mb-6 flex items-center justify-center" style="min-height: 200px;">
            <img src="{{ asset($image) }}" alt="{{ $title }}" class="max-h-48 object-contain">
        </div>
        <h3 class="text-2xl font-bold text-gray-800 mb-3 text-center">{{ $title }}</h3>
        <p class="text-2xl font-semibold text-gray-600 mb-6 text-center">${{ $price }}</p>
        <button class="w-full bg-teal-400 hover:bg-teal-500 text-white font-semibold px-6 py-3 rounded-lg transition duration-300">
            Ver Detalles
        </button>
    </div>
</div>
