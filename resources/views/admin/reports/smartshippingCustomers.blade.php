@extends('adminlte::page')

@section('title', 'Stock')

@section('content_header')
  <h4>Smart Shipping</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Smart Shipping Customers</h3>
      </div>
    </div>

    <div class="row" style="padding: 20px;">
      <div class="col-sm-12 col-md-4">
        <form action="{{ route('admin.reports.smartShippingCustomersFilter') }}" method="POST">
          @csrf
          <div class="input-group input-group-lg">
            <input type="text" name="search" class="form-control @error('search') is-invalid @enderror"
              placeholder="@lang('admin.transaction.searchuser')">
            <span class="input-group-append">
              <button type="submit" class="btn btn-info btn-flat">@lang('admin.btn.search')</button>
            </span>
            @error('search')
              <span class="error invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
        </form>
      </div>
    </div>

    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <div class="row">

        <table class="table table-hover table-bordered results" style="margin-top: 20px;">
          <thead>
            <tr>
              <th>Order</th>
              <th>Product</th>
              <th>Username</th>
              <th>Price</th>
              <th>QV</th>
              <th>CV</th>
              <th>Register</th>
              <th></th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
            </tr>
          </thead>
          <tbody>
            @forelse($SSCustomers as $item)
              <div class="modal fade" id="exampleModal{{ $item->number_order }}" tabindex="-1"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <form action="{{ route('admin.reports.disableSmartshipping') }}" method="POST">
                      @csrf
                      <div class="modal-header">
                        <h5 class="modal-title fs-5" id="exampleModalLabel">Disable smartshipping -
                          order {{ $item->number_order }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                      </div>
                      <div class="modal-body">
                        <input type="hidden" name="order" id="number-order-{{ $item->number_order }}">
                        <p>Are you sure you want to disable?</p>
                      </div>
                      <div class="modal-footer">
                        <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                        <button type="submit" class="btn btn-danger">Confirm</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>

              <tr>
                <td>{{ $item->number_order }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->login }}</td>
                <td>{{ $item->total }}</td>
                <td>{{ $item->qv }}</td>
                <td>{{ $item->cv }}</td>
                <td>{{ $item->created_at }}</td>
                <td><button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    onclick="defineOrderInput('{{ $item->number_order }}', 'number-order-{{ $item->number_order }}')"
                    data-bs-target="#exampleModal{{ $item->number_order }}" id="btn-modal-{{ $item->number_order }}">
                    Disable
                  </button></td>
              </tr>
            @empty
              <p>@lang('admin.orders.order.empty')</p>
            @endforelse

          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix py-3">
        {{ $SSCustomers->links() }}
      </div>
    </div>

    <script>
      function defineOrderInput(order, input) {
        ip = document.querySelector(`#${input}`).value = order;
      }
    </script>

  @stop
  @section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
  @stop
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  @section('js')

  @stop
