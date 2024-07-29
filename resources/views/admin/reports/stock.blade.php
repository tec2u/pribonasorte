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
        <h3>Stock Products</h3>
        @php
          $date_now = date('Y-m-d');
        @endphp

        <div class="col-12 col-md-8" style="margin-top: 0px; width: 100%;">
          <form class="row g-3" method="GET" action="{{ route('admin.packages.stock_admin.filter') }}">
            @csrf
            <div class="col-auto">
              <label style="margin-top: 10px;">@lang('admin.btn.firstdate'):</label>
            </div>
            <div class="col">
              <input type="date" class="form-control" name="fdate">
            </div>
            <div class="col-auto">
              <label style="margin-top: 10px;">@lang('admin.btn.seconddate'):</label>
            </div>
            <div class="col">
              <input type="date" class="form-control" name="sdate">
            </div>
            <div class="col">
              <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info">
            </div>
            <div class="col">
              <a href="{{ route('admin.packages.stock_admin') }}"><button class="btn btn-primary mb-1"
                  style="float: left; width: 150px;">Display all</button></a>
              <div style="clear:both;"></div>
            </div>
            <div class="col">
              <a href="{{ route('admin.packages.stock_admin_report') }}" style="float: left;"><button
                  class="btn btn-primary mb-0">Report</button></a>
            </div>
          </form>
        </div>

        {{-- <table style="float: right; width:100%;">
          <form action="{{ route('admin.packages.stock_admin.filter') }}" method="POST">
            @csrf
            @if (isset($filter) and !empty($filter))
              <tr>
                <td>
                  <input style="width: 120px; padding-left: 5px;" placeholder="in" value="{{ $in }}" type="text"
                    name="in_date" id="">
                </td>
                <td>
                  <input style="width: 120px; padding-left: 5px;" placeholder="until" value="{{ $until }}"
                    type="text" name="until_date" id="">
                </td>
                <td>
                  <button class="btn btn-primary " type="submit">Filter</button>
                </td>
              </tr>
            @else
              <tr>
                <td>
                  <input style="width: 120px; padding-left: 5px;" placeholder="in" value="{{ $date_now }}"
                    type="text" name="in_date" id="">
                </td>
                <td>
                  <input style="width: 120px; padding-left: 5px;" placeholder="until" value="{{ $date_now }}"
                    type="text" name="until_date" id="">
                </td>
                <td>
                  <button class="btn btn-primary " type="submit">Filter</button>
                </td>
              </tr>
            @endif
          </form> --}}
        {{-- <a href="{{ route('admin.packages.stock_admin') }}"><button class="btn btn-primary mb-1"
              style="float: right;">Display all</button></a>
          <div style="clear:both;"></div> --}}
        {{-- <br> --}}
        {{-- <a href="{{ route('admin.packages.stock_admin_report') }}" style="float: right;"><button
              class="btn btn-primary mb-0">Report</button></a> --}}
        {{-- </table> --}}
      </div>
    </div>

    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <form action="{{ route('admin.packages.stock_admin.edit') }}" method="get">
        <select class="form-select" name="id" id="" required>
          @foreach ($allProducts as $item)
            @if ($item->kit == 0)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endif
          @endforeach
        </select>
        <button class="btn btn-primary">Edit stock</button>
      </form>
      <div class="row">

        <div class="row">
          <div style="width: 100%; margin-left: 10px;">


            @foreach ($allProducts as $prod)
              {{ $prod->name }} : {{ $prod->stock }}</br>
            @endforeach
          </div>
        </div>

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
                {{-- <td><a href="{{ route('admin.packages.stock_admin.detal', ['id' => $item->id]) }}"><button style="width: 100%;">Details</button></a></td> --}}
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
      {{-- <div class="row d-flex justify-content-center ">
      {{$orderpackages->links()}}
  </div> --}}
    </div>
    <!-- Modal -->
    @if (isset($detal) and !empty($detal))
      @php
        $id_detal = $detal->product_id;
        $prduct_detal = Illuminate\Support\Facades\DB::select("SELECT * FROM products WHERE id = '$id_detal'");
      @endphp
      <div class="modal_detal">
        <div class="box_modal_stok">
          <div class="width: 100%; display: inline-block;">
            <a href="{{ route('admin.packages.stock_admin') }}">
              <p style="float: right;">✕</p>
            </a>
          </div>
          <table class="table table-hover table-bordered results">
            <thead>
              <tr>
                <th>Product</th>
                <th>Amouunt Stock</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{{ $prduct_detal[0]->name }}</td>
                <td>{{ $detal->amount }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    @endif
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
