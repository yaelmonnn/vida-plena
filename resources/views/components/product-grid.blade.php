<div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8">

    @foreach ($productos as $p)
        <x-product-card
            titulo="{{ $p->nombre }}"
            precio="{{ $p->precio }}"
            imagen="{{ $p->imagen }}"
            categoria="{{ $p->categoria }}"
            estado="{{ $p->estado_nombre }}"
            rating="{{ $p->calificacion }}"
        />
    @endforeach


</div>

    <div class="mt-10 flex justify-center">
        {{ $productos->links() }}
    </div>

