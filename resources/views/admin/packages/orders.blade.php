@extends('adminlte::page')

@section('title', 'Package Orders')

@section('content_header')
  <h4>@lang('admin.orders.title')</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>@lang('admin.orders.title')</h3>
      </div>
    </div>
    <div class="card-header">
      <div class="row">
        <div class="col-sm-6 col-md-6">
          <div class="text-left">
            <div class="btn-group" style="display: flex;gap:1rem">
              <a href="{{ route('admin.packages.payall') }}" class="btn btn-lg bg-info" title="pay all">
                @lang('admin.orders.payall')
              </a>
              <a href="{{ route('admin.packages.orderPackagesCorporate') }}" class="btn btn-lg bg-info" title="pay all">
                See order corporate
              </a>
              @if ($errors->has('Fakturoid'))
                <div class="alert alert-danger">
                  {{ $errors->first('Fakturoid') }}
                </div>
              @endif

              @if ($errors->has('SendFakturoid'))
                <div class="alert alert-success">
                  {{ $errors->first('SendFakturoid') }}
                </div>
              @endif
            </div>
          </div>
        </div>

        <div class="col-sm-6 col-md-6">
          <form action="{{ route('admin.packages.order_filter_name') }}" method="POST">
            @csrf
            <div class="input-group input-group-lg">
              <input type="text" name="search" class="form-control @error('search') is-invalid @enderror"
                placeholder="@lang('admin.members.search')">
              <span class="input-group-append">
                <button type="submit"
                  style="float: left; height: 45px; margin: -3px 0px 0px 10px; background-color: #490d55; color: #ffffff; border-radius: 5px; padding: 0px 15px;">@lang('admin.btn.search')</button>
              </span>

            </div>
          </form>
        </div>

        <div class="col-sm-6 col-md-6">
          <div class="text-right">
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-lg dropdown-toggle dropdown-icon" data-toggle="dropdown"
                aria-expanded="false">
                @lang('admin.orders.filter')
              </button>
              <div class="dropdown-menu" style="">
                <a class="dropdown-item"
                  href="{{ route('admin.packages.order_filter', ['parameter' => 'paid']) }}">@lang('admin.orders.filterOrder.paid')</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.order_filter', ['parameter' => 'send']) }}">@lang('admin.orders.filterOrder.pending')</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.order_filter', ['parameter' => 'canceled']) }}">@lang('admin.orders.filterOrder.canceled')</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <div class="row">
        <div class="col-sm-12 col-md-4">
          <form action="{{ route('admin.packages.searchOrders') }}" method="POST">
            @csrf
            <div class="input-group input-group-lg">
              <input type="text" name="search" class="form-control @error('search') is-invalid @enderror"
                placeholder="@lang('admin.orders.name')">
              <span class="input-group-append">
                <button type="submit" class="btn btn-info btn-flat">@lang('admin.btn.search')</button>
              </span>
              @error('search')
                <span class="error invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </form>
        </div>
        <div class="col-12 col-md-8">
          <form class="row g-3" method="POST" action="{{ route('admin.packages.getDateOrders') }}">
            @csrf
            <div class="col-auto">
              <label>@lang('admin.btn.firstdate'):</label>
            </div>
            <div class="col">
              <input type="date" class="form-control" name="fdate">
            </div>
            <div class="col-auto">
              <label>@lang('admin.btn.seconddate'):</label>
            </div>
            <div class="col">
              <input type="date" class="form-control" name="sdate">
            </div>
            <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info">
          </form>
        </div>
      </div>
      <table class="table table-hover table-bordered results">
        <thead>
          <tr>
            <th>#</th>
            <th>username</th>
            <th>name</th>
            <th>@lang('admin.orders.order.col2')</th>
            <th>@lang('admin.orders.order.col3')</th>
            <th>@lang('admin.orders.order.col7')</th>
            <th>@lang('admin.orders.order.col8')</th>
            <th>@lang('admin.orders.order.col4')</th>
            <th>@lang('admin.orders.order.col5')</th>
            <th>@lang('admin.orders.order.col6')</th>
            <th>fakturoid</th>
            <th>Invoice</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
          </tr>
        </thead>
        <tbody>
          @forelse($orderpackages as $orderpackage)
            @php
              $sendedfak = DB::select("SELECT * FROM invoices_fakturoid WHERE number_order = $orderpackage->id");
              $SendedFakturoid = !empty($sendedfak);
            @endphp
            @if ($orderpackage->payment_method != 'Admin' || $orderpackage->order_corporate == 0)
              <tr>
                <th>{{ $orderpackage->id }}</th>
                <th>{{ isset($orderpackage->user) ? $orderpackage->user->login : $orderpackage->login }}</th>
                <th>
                  {{ isset($orderpackage->user) ? $orderpackage->user->name . ' ' . $orderpackage->user->last_name : '' }}
                </th>
                <td>{{ isset($orderpackage->package) ? $orderpackage->package->name : $orderpackage->name }}</td>
                <td>{{ number_format($orderpackage->price, 2, ',', '.') }}</td>
                <th>{{ $orderpackage->transaction_wallet }}</th>
                <th>{{ $orderpackage->wallet }}</th>
                <td>{{ date('d/m/Y h:i:s', strtotime($orderpackage->created_at)) }}</td>
                <td>
                  @if ($orderpackage->payment_status == 2)
                    <button class="btn btn-danger btn-sm m-0">@lang('admin.btn.canceled')</button>
                  @elseif($orderpackage->status == 1)
                    <button class="btn btn-warning btn-sm m-0">@lang('admin.btn.paid')</button>
                  @else
                    <button class="btn btn-primary btn-sm m-0">@lang('admin.btn.pending')</button>
                  @endif
                </td>
                <td>
                  <button type="button" class="btn btn-primary btn-sm m-0" data-bs-toggle="modal"
                    data-bs-target="#changeModal{{ $orderpackage->id }}">
                    @lang('admin.btn.changestatus')
                  </button>
                </td>
                @if ($orderpackage->payment_status == 1)
                  @if ($SendedFakturoid)
                    <td>
                      <button type="button" class="btn btn-success">
                        Sended
                      </button>
                    </td>
                  @else
                    <td>
                      <a type="button" class="btn btn-primary btn-sm m-0"
                        href="{{ route('admin.packages.Fakturoid.packages', $orderpackage->id) }}">
                        Send to Fakturoid
                      </a>
                    </td>
                  @endif
                  <td>
                    <button class="btn rounded-pill bg-info px-4 py-1"><a target="_blank"
                        href="{{ route('packages.invoicePackage', ['id' => $orderpackage->id]) }}">invoice</a></button>
                    <button class="btn rounded-pill bg-info px-4 py-1"><a target="_blank"
                        href="{{ route('packages.invoicePackageXml', ['id' => $orderpackage->id]) }}">XML</a></button>
                  </td>
                @endif
              </tr>
            @endif
          @empty
            <p>@lang('admin.orders.order.empty')</p>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer clearfix py-3">
      {{ $orderpackages->links() }}
    </div>
    {{-- <div class="row d-flex justify-content-center ">
      {{$orderpackages->links()}}
  </div> --}}
  </div>
  <!-- Modal -->

  @forelse($orderpackages as $orderpackage)
    <div class="modal fade" id="changeModal{{ $orderpackage->id }}" tabindex="-1" aria-labelledby="changeModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="changeModalLabel">@lang('admin.orders.status.title') #{{ $orderpackage->id }}</h5>
            <button id='closeModal' type="button" class="btn btn-danger"
              data-bs-dismiss="modal">@lang('admin.btn.close')</button>
          </div>
          <div class="modal-body">
            <form class="row g-3" action="{{ route('admin.packages.orderupdate', ['id' => $orderpackage->id]) }}"
              method="POST">
              @csrf
              @method('PUT')
              <table class="table table-hover table-bordered responsive col-md-6">
                <thead>
                  <tr>
                    <th scope="col"><b>@lang('admin.orders.status.col1')</th>
                    <th scope="col"><b>@lang('admin.orders.status.col2')</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th><b>@lang('admin.orders.status.col3'):</th>
                    <td>{{ $orderpackage->transaction_code }}</td>
                  </tr>
                  <tr>
                    <th><b>@lang('admin.orders.status.col4'):</th>
                    <th>{{ isset($orderpackage->user) ? $orderpackage->user->name : $orderpackage->name }}</th>
                  </tr>
                  <tr>
                    <th><b>@lang('admin.orders.status.col5'):</th>
                    <td>{{ isset($orderpackage->package) ? $orderpackage->package->name : $orderpackage->name }}</td>
                  </tr>
                  <tr>
                    <th><b>@lang('admin.orders.status.col6')</th>
                    <td>{{ number_format($orderpackage->price, 2, ',', '.') }}</td>
                  </tr>
                  <tr>
                    <th><b>@lang('admin.orders.status.col7')</th>
                    <td><b>wallet/api</td>
                  </tr>
                  <tr>
                    <th><b>@lang('admin.orders.status.col8')</th>
                    <td>{{ date('d/m/Y h:i:s', strtotime($orderpackage->created_at)) }}</td>
                  </tr>
                  <tr>
                    <th><b>@lang('admin.orders.status.col9')</th>
                    <td>{{ date('h:i:s A , l jS \of F Y ') }}</td>
                  </tr>
                </tbody>
              </table>
              <div class="col-md-6">
                <label for="payment_status">@lang('admin.orders.status.subtitle')</label>
                <select id="payment_status" name="payment_status" class="form-control">

                  @if ($orderpackage->payment_status == 2)
                    <option value="0">@lang('admin.btn.pending')</option>
                    <option value="1">@lang('admin.btn.paid')</option>
                    <option value="2" selected>@lang('admin.btn.underpaid')</option>
                  @elseif($orderpackage->payment_status == 1)
                    <option value="0">@lang('admin.btn.pending')</option>
                    <option value="1" selected>@lang('admin.btn.paid')</option>
                    <option value="2">@lang('admin.btn.underpaid')</option>
                  @else
                    <option value="0" selected>@lang('admin.btn.pending')</option>
                    <option value="1">@lang('admin.btn.paid')</option>
                    <option value="2">@lang('admin.btn.underpaid')</option>
                  @endif

                </select>

                <label for="status">@lang('admin.orders.status.subtitle2')</label>
                <select id="status" name="status" class="form-control">
                  @if ($orderpackage->status == 2)
                    <option value="2" selected>@lang('admin.btn.canceled')</option>
                    <option value="0">@lang('admin.btn.pending')</option>
                    <option value="1">@lang('admin.btn.paid')</option>
                  @elseif($orderpackage->status == 1)
                    <option value="1" selected>@lang('admin.btn.paid')</option>
                    <option value="0">@lang('admin.btn.pending')</option>
                    <option value="2">@lang('admin.btn.canceled')</option>
                  @else
                    <option value="0" selected>@lang('admin.btn.pending')</option>
                    <option value="1">@lang('admin.btn.paid')</option>
                    <option value="2">@lang('admin.btn.canceled')</option>
                  @endif

                </select>

                <button type="submit" class="btn btn-primary mt-3">@lang('admin.orders.status.processed')</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('admin.btn.close')</button>
          </div>
        </div>
      </div>
    </div>
  @empty
  @endforelse

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
