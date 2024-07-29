@extends('layouts.header')
@section('content')
  <style>
    .price-now {
      font-size: 25px;
      font-weight: bold;
    }
  </style>

  <main id="main" class="main">
    {{-- @include('flash::message') --}}
    <section id="produto" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row justify-content-evenly" style="margin-bottom: 30px;">
            <ul class="list-group list-group-horizontal" style="margin-left: 20px;">
              <li class="list-group-item"><a href="{{ route('packages.index_products') }}">Products</a></li>
              <li class="list-group-item fw-bold">{{ $product->name }}</li>
            </ul>
          </div>
          <div class="row justify-content-evenly">
            <div class="col-6">
              <div class="card shadow p-md-5" style="width: 100%;">
                <img src="/img/products/{{ $product->img_1 }}" class="card-img-top" alt="...">
              </div>
            </div>
            <div class="col-6">
              <div class="row">
                <p class="card-title text-start">{{ $product->name }}</p>
              </div>
              <div class="row">
                <p class="">{!! $product->description !!}</p>
              </div>
              {{-- <div class="row">
                <p class="">Stock: {{ $stock->amount }}</p>
              </div> --}}
              <div class="row mt-4">
                @php
                  $new_price = $product->backoffice_price;

                  $qv = $product->qv;
                  $qv_format = number_format($qv, 2, ',', '.');
                @endphp
                <p class="price-now">€ {{ $new_price }} (exl.VAT)</p>
                <p class="price-now" style="color: #51185D; font-size: 1rem;">QV {{ $qv }} |
                  CV {{ $product->cv }}
                </p>
              </div>
              @if ($stock > 0)
                <form action="{{ route('packages.buy_products', ['id' => $product->id]) }}" method="GET">
                  {{-- @csrf --}}
                  <div class="row">
                    <input style="width: 20%; margin-left: 10px;" name="quant_product" value="1"
                      max="{{ $stock }}" min="0" type="number">
                    <input name="id_product" value="{{ $product->id }}" type="hidden">
                  </div>
                  <div class="row">
                    <button type="submit" class="btn btn-secondary btn-lg"
                      style="width: 60%; margin-left: 10px; margin-top: 20px;">Add to Cart</button>
                  </div>
                </form>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
