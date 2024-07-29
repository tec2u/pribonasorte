@extends('adminlte::page')

@section('title', 'Products Orders')

@section('content_header')
  <h4>
    Orders Product</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Orders Product</h3>
      </div>
      <a href="{{ route('admin.packages.orderfilter.products', ['parameter' => 'paid']) }}">
        <button class="btn btn-warning btn-sm" type="button">
          See Orders Paid
        </button>
      </a>
    </div>
    <div class="card-header">
      <div class="row">
        <div class="col-sm-6 col-md-6">
          <div class="text-left">
            <div class="btn-group">
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
          <div class="text-right">
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-lg dropdown-toggle dropdown-icon" data-toggle="dropdown"
                aria-expanded="false">
                @lang('admin.orders.filter')
              </button>
              <div class="dropdown-menu" style="">
                <a class="dropdown-item"
                  href="{{ route('admin.packages.orderfilter.products.corporate.status', ['parameter' => 'paid']) }}">@lang('admin.orders.filterOrder.paid')</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.orderfilter.products.corporate.status', ['parameter' => 'pending']) }}">@lang('admin.orders.filterOrder.pending')</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.orderfilter.products.corporate.status', ['parameter' => 'cancelled']) }}">@lang('admin.orders.filterOrder.canceled')</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <div class="row">

        <table class="table table-hover table-bordered results">
          <thead>
            <tr>
              <th>#</th>
              <th>Status</th>
              <th>User Name</th>
              <th>Id corporate</th>
              <th>Name Corporate</th>
              <th>@lang('admin.orders.order.col3')</th>
              <th>Date</th>
              <th>Payment</th>
              <th></th>
              <th>Edit</th>
              <th>Shipping Label</th>
              <th>Order Slip</th>
              <th>track</th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
            </tr>
          </thead>
          <tbody>
            @forelse($orderpackages as $orderpackage)
              {{-- @if ($orderpackage->payment_method == 'admin') --}}
              <tr>
                @php
                  $status_order = '';
                  if (isset($orderpackage)) {
                      $id_user = $orderpackage->id_user;

                      if (isset($id_user)) {
                          if (isset($orderpackage->number_order)) {
                              $orderFinal = DB::select("SELECT * FROM ecomm_orders WHERE number_order=$orderpackage->number_order");
                              if (isset($orderFinal[0]->{'client_backoffice'})) {
                                  if (isset($orderFinal[0]->{'status_order'})) {
                                      $status_order = $orderFinal[0]->{'status_order'};
                                  }
                                  if ($orderFinal[0]->{'client_backoffice'} == 1) {
                                      $userGet = DB::select("SELECT * FROM users WHERE id=$id_user") ?? '';
                                  } else {
                                      $userGet = DB::select("SELECT * FROM ecomm_registers WHERE id=$id_user") ?? '';
                                      // dd($userGet);
                                  }
                              }
                          }
                      }
                  }

                  $billing = DB::select("SELECT * FROM address_billing WHERE user_id = $orderpackage->id_user") ?? null;
                  $sendedfak = DB::select("SELECT * FROM invoices_fakturoid WHERE number_order = $orderpackage->number_order");
                  $SendedFakturoid = !empty($sendedfak);

                @endphp
                <th>{{ $orderpackage->number_order }}</th>
                @if (isset($status_order) && $status_order != '')
                  @if ($status_order == 'order placed')
                    <th>{{ $status_order ?? '' }}</th>
                  @else
                    <th style="color:#07ca00;">{{ $status_order ?? '' }}</th>
                  @endif
                @else
                  <th>{{ $status_order ?? '' }}</th>
                @endif
                <th>
                  @if (isset($userGet[0]->{'name'}))
                    @if (isset($userGet[0]->{'last_name'}))
                      {{ $userGet[0]->{'name'} }} {{ $userGet[0]->{'last_name'} }}
                    @else
                      {{ $userGet[0]->{'name'} }}
                    @endif
                  @else
                    {{ '' }}
                  @endif
                </th>

                <td>{{ $userGet[0]->{'id_corporate'} }}</td>
                <td>{{ $userGet[0]->{'corporate_nome'} }}</td>

                <td>{{ $orderpackage->total_price }}</td>
                <td>{{ date('d/m/Y h:i:s', strtotime($orderpackage->created_at)) }}</td>
                <td>
                  @if (strtolower($orderpackage->status) == 'cancelled' || strtolower($orderpackage->status) == 'expired')
                    <button class="btn btn-success btn-sm m-0">@lang('admin.btn.canceled')</button>
                  @elseif(strtolower($orderpackage->status) == 'paid')
                    <button class="btn btn-warning btn-sm m-0">@lang('admin.btn.paid')</button>
                  @else
                    <button class="btn btn-primary btn-sm m-0">@lang('admin.btn.pending')</button>
                  @endif
                </td>
                <td>
                  @if (isset($billing[0]->{'id'}))
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                      data-bs-target="#exampleModalUser{{ $orderpackage->id_user }}">
                      Address Billing
                    </button>
                  @endif
                </td>
                <td>
                  <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                    data-bs-target="#exampleModal{{ $orderpackage->id }}">
                    Edit
                  </button>
                </td>

                @if (strtolower($orderpackage->status) == 'paid')
                  <td>
                    <a type="button" class="btn btn-primary btn-sm m-0"
                      href="{{ route('admin.packages.order_products_label', $orderpackage->number_order) }}">
                      shipping label
                    </a>
                  </td>
                @else
                  <td>
                    <span type="button" class="btn btn-primary btn-sm m-0">
                      Unpaid
                    </span>
                  </td>
                @endif

                <td>
                  <a type="button" class="btn btn-primary btn-sm m-0"
                    href="{{ route('orderslipPDF', $orderpackage->number_order) }}">
                    Order Slip
                  </a>
                </td>
                <td>
                  <a type="button" class="btn btn-primary btn-sm m-0"
                    href="{{ route('admin.tracking') . "?order=$orderpackage->number_order" }}">
                    Track
                  </a>
                </td>

                <div class="modal fade" id="exampleModal{{ $orderpackage->id }}" tabindex="-1"
                  aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Order {{ $orderpackage->number_order }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <form
                          action="{{ route('admin.packages.orderfilter.products.corporate.save', $orderpackage->id) }}"
                          method="POST" id="form-{{ $orderpackage->id }}">
                          @csrf
                          <select name="methodPayment" id="selectMethod" onchange="changeMetodo(event)"
                            style="font-size: 1rem"
                            class="form-control form-control-lg @error('payment') is-invalid @enderror" required>
                            <option value="">Choose a method</option>
                            @foreach ($metodos as $item)
                              <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                          </select>
                          <br>
                          <select name="status" id="selectMethod" onchange="changeMetodo(event)" style="font-size: 1rem"
                            class="form-control form-control-lg @error('payment') is-invalid @enderror" required>
                            <option value="">Change status</option>
                            <option value="paid">Pay</option>
                            <option value="cancelled">Cancell</option>
                          </select>
                          <br>
                          <button type="submit" class="btn btn-primary">Save
                            changes</button>
                        </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade" id="exampleModalUser{{ $orderpackage->id_user }}" tabindex="-1"
                  aria-labelledby="exampleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Address Billing
                          {{ $userGet[0]->{'corporate_nome'} }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">

                        @php
                          $billing = DB::select("SELECT * FROM address_billing WHERE user_id = $orderpackage->id_user") ?? '';
                        @endphp

                        @if (isset($billing[0]->{'id'}))
                          <div class="col-md-6">
                            <label for="inputname" class="form-label">City<span style="color: brown">*</span></label>
                            <input readonly type="text" class="form-control" id="city" required name="city"
                              value="{{ $billing[0]->{'city'} ?? '' }}">
                          </div>

                          <div class="col-md-6">
                            <label for="inputname" class="form-label">number_residence<span
                                style="color: brown">*</span></label>
                            <input readonly type="text" class="form-control" id="number_residence" required
                              name="number" value="{{ $billing[0]->{'number_residence'} ?? '' }}">
                          </div>

                          <div class="col-md-6">
                            <label for="inputname" class="form-label">state<span style="color: brown">*</span></label>
                            <input readonly type="text" class="form-control" id="state" required name="state"
                              value="{{ $billing[0]->{'state'} ?? '' }}">
                          </div>
                          <div class="col-md-6">
                            <label for="inputname" class="form-label">Address<span
                                style="color: brown">*</span></label>
                            <input readonly type="text" class="form-control" id="address1" required
                              name="address1" value="{{ $billing[0]->{'address'} ?? '' }}">
                          </div>

                          <div class="col-md-6">
                            <label for="inputname" class="form-label">@lang('user.postcode')<span
                                style="color: brown">*</span></label>
                            <input readonly type="text" class="form-control" id="postcode" required
                              name="postcode" value="{{ $billing[0]->{'zip'} ?? '' }}">
                          </div>

                          <div class="col-md-6">
                            <label for="inputAddress" class="form-label">@lang('user.country')<span
                                style="color: brown">*</span></label>
                            <select class="form-control" id="country" required name="country" tabindex="7"
                              readonly>
                              @php
                                $country2 = $billing[0]->{'country'} ?? '';
                              @endphp
                              <option value="{{ $country2 }}" selected="selected">
                                {{ $country2 }}</option>
                            </select>
                          </div>
                        @endif
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>
              </tr>
              {{-- @endif --}}
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
    <script>
      function typeCONFIRM(input, button, inputOrder, order) {
        ip = document.querySelector(`#${input}`);
        btn = document.querySelector(`#${button}`);
        input_order = document.querySelector(`#${inputOrder}`).value = order;

        if (ip.value == 'CONFIRM') {
          btn.style.display = 'initial';
        } else {
          btn.style.display = 'none';
        }
      }
    </script>
  @stop
