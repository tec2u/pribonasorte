@extends('layouts.header')
@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    .hiddenRow {
      padding: 0 !important;
    }
  </style>

  <main id="main" class="main">
    <section id="supporttable" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>Order {{ $orderDetails[0]->number_order }} - {{ $orderDetails[0]->payment }}</h1>

              @if ($orderDetails->count() > 0)
                <div class="card shadow my-3">
                  <table class="table text-nowrap">
                    <thead>
                      <tr
                        style="display: flex; align-items: center; width: 100%;justify-content: space-around; text-align: center">
                        <th>Image</th>
                        <th>Product</th>
                        <th>Amount</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orderDetails as $item)
                        <tr
                          style="display: flex; align-items: center; width: 100%;justify-content: space-around; text-align: center">
                          <th>
                            <img src="/img/products/{{ $item->image_product }}" alt=""
                              style="width: 150px; height: 150px;">
                          </th>
                          <th>{{ $item->name_product }}</th>
                          <th>{{ $item->amount }}</th>
                          <th>
                            <a href="{{ route('packages.detail_products', $item->id_product) }}">
                              <button type="button" class="btn btn-primary">Product</button>
                            </a>
                          </th>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <p>no orders found</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection
@section('css')
@stop
