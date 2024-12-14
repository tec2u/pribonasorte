@extends('layouts.header')
@section('content')
  <style>
    #card-product {
      padding: 30px;
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

    .floating-btn {
      /* position: fixed;
                            bottom: 20px;
                            right: 20px; */
      /* background-color: #007bff; */
      color: #fff;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
      margin-bottom: 1rem;
      /* float: right; */
      /* box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3); */
    }
  </style>

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
            <p class="title-product">Choose @lang('package.products')</p>
            <p class="number-products">
              @php $prosucts_number = count($products); @endphp
              {{ $prosucts_number }} Products
            </p>
          </div>

          <div class="container">
            <form action="{{ route('packages.newProcessPackage4') }}" method="post">
              @csrf
              <button class="btn btn-primary btn-lg floating-btn" style="width: fit-content; font-size: 13px;"
                type="submit">
                Next Step</button>
              <br>
              <div class="row">
                @forelse($products as $product)
                  <div class="col-sm-4 card-deck hover">
                    <div id="card-product" class="card">

                      <div class='base-img'>
                        <div class='moldure'>
                          {{-- <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">
                        </a> --}}
                          <img class='imagem_zoon' src='/img/products/{{ $product->img_1 }}'>
                        </div>
                      </div>

                      @php
                        $new_price = $product->backoffice_price;

                        $qv = $product->qv;
                        $qv_format = number_format($qv, 2, ',', '.');
                      @endphp

                      {{-- <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">
                    </a> --}}
                      <h5 class="tittle-name">{{ $product->name }}</h5>
                      <h6 class="text-price">R$ {{ $new_price }}</h6>
                      <h6 class="text-price" style="color: #51185D; font-size:12px;">QV {{ $qv_format }} | CV
                        {{ $product->cv }}</h6>


                      <div class="container-description">
                        @if (!empty($product->description))
                          <h6 class="text-description">
                            <div style="height: 90px; overflow: hidden;">
                              {!! $product->description !!}
                            </div>
                            {{-- <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">see more...</a> --}}
                          </h6>
                        @else
                          <h6 class="text-description">No description at the moment!</h6>
                        @endif
                      </div>

                      @if ($product->stock > 0)
                        <input name="quant_product_{{ $product->id }}" class="quant_cart" value="0"
                          max="{{ $product->stock }}" min="0" type="number">
                      @endif
                    </div>
                  </div>

                @empty
                  <p>@lang('package.any_products_registered')</p>
                @endforelse

              </div>
              <button class="btn btn-primary btn-lg" style="width: fit-content; font-size: 13px;" type="submit">
                Next Step</button>

            </form>

          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
