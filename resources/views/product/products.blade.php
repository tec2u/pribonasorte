@extends('layouts.header')
@section('content')
<style>
    #card-product {
        padding: 30px;
    }

    .modal-backdrop {
        background-color: transparent !important;
    }

    p.title-product {
        margin-left: 10px;
        font-size: 30px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .moldure {
        width: 100%;
        height: 300px;
        background-size: 100%;
        overflow: hidden;
        cursor: pointer;
    }

    img.imagem_zoon {
        max-width: 100%;
        -moz-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }

    img.imagem_zoon:hover {
        -moz-transform: scale(1.1);
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    h5.tittle-name {
        font-weight: bold;
    }

    .container-description {
        width: 100%;
        height: 120px;
    }

    p.number-products {
        margin-left: 10px;
    }

    .quant_cart {
        width: 60px;
        height: 40px;
        padding-left: 10px;
        border-radius: 100px;
        text-align: center;
        border: 1px solid #636363;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #EEEEEE;
        color: inherit;
    }
</style>
@php
$categorias = \App\Models\Categoria::get();
@endphp
<main id="main" class="main">
    {{-- @include('flash::message') --}}
    <section id="produtos" class="content">
        <div class="fade">
            <div class="container-fluid">
                @error('address')
                <span class="invalid-feedback " style="display:block" role="alert">
                    <h5 style="color:red">{{ $message }}</h5>
                </span>
                @enderror

                <div class="row-title">
                    <p class="title-product">@lang('package.products')</p>
                    <p class="number-products">
                        @php $prosucts_number = count($products); @endphp
                        {{ $prosucts_number }} Produtos
                    </p>

                    @if (count($categorias) > 0)
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Categorias
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('packages.index_products') }}">Todos</a></li>
                            @foreach ($categorias as $cat)
                            <li><a class="dropdown-item"
                                    href="{{ route('packages.index_categoria', $cat->id) }}">{{ $cat->nome }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                </div>

                <div class="container">
                    <div class="row">
                        @forelse($products as $product)
                        <div class="col-sm-3 card-deck hover">
                            <div id="card-product" class="card">

                                <div class='base-img'>
                                    <div class='moldure'>
                                        <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}"><img class='imagem_zoon'
                                                src='/img/products/{{ $product->img_1 }}'></a>
                                    </div>
                                </div>

                                @php
                                $new_price = $product->backoffice_price;
                                @endphp

                                <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">
                                    <h5 class="tittle-name">{{ $product->name }}</h5>
                                </a>
                                <h6 class="text-price">R$  {{ $new_price }}</h6>


                                <div class="container-description">
                                    @if (!empty($product->description))
                                    <h6 class="text-description">
                                        <div style="height: 90px; overflow: hidden;">
                                            {!! $product->description !!}
                                        </div>
                                        <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">Ver mais...</a>
                                    </h6>
                                    @else
                                    <h6 class="text-description">Sem descrição!</h6>
                                    @endif
                                </div>

                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ...
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                                                <button type="button" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($product->type == 'fisico')
                                    <div>Estoque {{ $product->stock }}</div>
                                @endif
                                <form class="buyProductForm" action="{{ route('packages.buy_products', ['id' => $product->id]) }}"
                                    method="GET">
                                    <input name="quant_product" class="quant_cart" value="1" max="{{ $product->stock <= 0 && $product->type == 'fisico' ? $product->stock : 1000 }}"
                                        min="0" type="number">
                                    <button type="submit" style="padding: 6px 1rem !important"
                                        class="btn btn-primary m-4 rounded-pill" {{ ($product->stock <= 0 && $product->type == 'fisico') ? 'disabled': '' }}>Adicionar ao carrinho</button>
                                </form>

                            </div>
                        </div>

                        @empty
                        <p>@lang('package.any_products_registered')</p>
                        @endforelse

                    </div>
                    <div class="card-footer clearfix py-3">
                        {{-- {{ $products->links() }} --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<div class="modal fade" id="addToCartModal" tabindex="-1" role="dialog" aria-labelledby="addToCartModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Produto adicionado ao carrinho</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Deseja ir ao carrinho ou continuar comprando?
            </div>
            <div class="modal-footer">
                {{-- <a href="{{ route('packages.index_products') }}" class="btn btn-secondary">Back to products</a> --}}
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continuar</button>
                <a href="{{ route('packages.cart_buy') }}" class="btn btn-primary">ir ao carrinho</a>
            </div>
        </div>
    </div>
</div>

@endsection
