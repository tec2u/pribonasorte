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
              <h1>Orders {{ $indication->name }}</h1>

              @if ($orders->count() > 0)
                <div class="card shadow my-3" style="margin-top: 20px; padding: 20px;">
                  <div style="width: 100%; display: inline-block;">
                    <a href="{{ route('networks.IndicationEcommFilterMonth') }}"><button
                        style="padding: 0px 60px; margin-bottom: 20px; height: 40px; background: #d26075; color: #ffffff; border-radius: 5px; float: right;">Search
                        for orders of the month</button></a>
                  </div>
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Order</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th>QV</th>
                        <th>Registered</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $item)
                        <tr>
                          <th>{{ $item->number_order }}</th>
                          <th>{{ $item->payment }}</th>
                          <th>{{ $item->total }}</th>
                          <th>{{ $item->t_qv }}</th>
                          <th>{{ $item->created_at }}</th>
                          <th>
                            <a href="{{ route('networks.IndicationEcommOrdersDetail', $item->number_order) }}">
                              <button type="button" class="btn btn-primary">Details</button>
                            </a>
                          </th>
                        </tr>
                      @endforeach
                    </tbody>
                    <div class="card-footer clearfix py-3">
                      {{ $orders->links() }}
                    </div>
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
