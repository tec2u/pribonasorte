@extends('adminlte::page')

@section('title', 'Stock')

@section('content_header')
  <h4>Stock movement</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Stock Products <a class="btn btn-primary" href="{{ route('admin.packages.stock_admin') }}">Back</a> </h3>
        @php
          $date_now = date('Y-m-d');
        @endphp

      </div>
    </div>

    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <form action="{{ route('admin.packages.stock_admin.update') }}" method="post">
        @csrf
        <input type="hidden" name="id" id="" value="{{ $product->id }}">
        <input type="text" name="" id="" value="{{ $product->name }}" readonly>
        <input type="number" name="stock" id="" value="{{ $product->stock }}">
        <button class="btn btn-primary">Edit stock</button>
      </form>
      <div class="row">

        <table class="table table-hover table-bordered results" style="margin-top: 20px;">
          <thead>
            <tr>
              <th>#</th>
              <th>Product</th>
              <th>Id Product</th>
              <th>Username</th>
              <th>Name</th>
              <th>Date</th>
              <th>Order ID</th>
              <th>Amount</th>
              {{-- <th>Details</th> --}}

            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
            </tr>
          </thead>
          <tbody>
            @forelse($stock as $item)
              @php

                $id_product = $item->product_id;
                $id_user = $item->user_id;

                $prduct_name = Illuminate\Support\Facades\DB::select("SELECT * FROM products WHERE id = '$id_product'");
                if ($item->ecommerce_externo == 0) {
                    $user_name = Illuminate\Support\Facades\DB::select("SELECT * FROM users WHERE id = '$id_user'");
                } else {
                    $user_name = Illuminate\Support\Facades\DB::select(
                        "SELECT * FROM ecomm_registers WHERE id = '$id_user'",
                    );
                }

              @endphp

              <tr>
                <th>{{ $item->id }}</th>
                <td>{{ $prduct_name[0]->name }}</td>
                <td>{{ $item->product_id }}</td>
                @if (!empty($user_name[0]->login))
                  <td>{{ $user_name[0]->login }}</td>
                @else
                  <td>-</td>
                @endif
                @if (!empty($user_name[0]->name))
                  <td>{{ $user_name[0]->name . ' ' . $user_name[0]->last_name }}</td>
                @else
                  <td>-</td>
                @endif
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->number_order }}</td>
                <td>{{ $item->amount }}</td>

              </tr>
            @empty
              <p>@lang('admin.orders.order.empty')</p>
            @endforelse

          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix py-3">
        @if (!isset($filter) and empty($filter))
          {{ $stock->links() }}
        @endif
      </div>
    </div>


  @stop
  @section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
  @stop
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  @section('js')
    <script>
      $(document).ready(function() {
        $(".search").keyup(function() {
          var searchTerm = $(".search").val();
          var listItem = $('.results tbody').children('tr');
          var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

          $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array) {
              return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "")
                .toLowerCase()) >= 0;
            }
          });

          $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e) {
            $(this).attr('visible', 'false');
          });

          $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e) {
            $(this).attr('visible', 'true');
          });

          var jobCount = $('.results tbody tr[visible="true"]').length;
          $('.counter').text(jobCount + ' item');

          if (jobCount == '0') {
            $('.no-result').show();
          } else {
            $('.no-result').hide();
          }
        });
      });
    </script>
  @stop
