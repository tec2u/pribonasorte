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
      <a href="{{ route('admin.packages.orderfilter.products.corporate') }}">
        <button class="btn btn-warning btn-sm" type="button">
          See Orders pending corporate
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
      </div>
      <div class="row">
        <div class="col-sm-12 col-md-6" style="float: left;">
          <form action="{{ route('admin.packages.orderFilterName.products', ['parameter' => $parameter ?? 0]) }}"
            method="GET">
            @csrf
            <div class="input-group input-group-lg">
              <input type="text" name="search" class="form-control"
                placeholder="Search order number, name user or login user">
              <span class="input-group-append">
                <button type="submit"
                  style="float: left; height: 45px; margin: -3px 0px 0px 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; padding: 0px 15px;">@lang('admin.btn.search')</button>
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
                  href="{{ route('admin.packages.orderfilter.products', ['parameter' => 'order placed']) }}">Not sent</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.orderfilter.products', ['parameter' => 'paid']) }}">@lang('admin.orders.filterOrder.paid')</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.orderfilter.products', ['parameter' => 'pending']) }}">@lang('admin.orders.filterOrder.pending')</a>
                <a class="dropdown-item"
                  href="{{ route('admin.packages.orderfilter.products', ['parameter' => 'cancelled']) }}">@lang('admin.orders.filterOrder.canceled')</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <div class="row">

        <table class="table table-hover table-bordered results" id="tabela-dados">
          <thead>
            <tr>
              <th>#</th>
              <th onclick="ordenarTabela(1)">Status</th>
              <th>Method</th>
              <th>User Name</th>
              <th>@lang('admin.orders.order.col3')</th>
              <th>Date</th>
              <th>Payment</th>
              <th>Shipping Label</th>
              <th>track</th>
              <th>Cancell</th>
              <th>Edit</th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
            </tr>
          </thead>
          <tbody>
            @forelse($orderpackages as $orderpackage)
              @if ($orderpackage->payment_method != 'admin' || $orderpackage->order_corporate == 0)
                <tr>
                  @php
                    $status_order = '';
                    if (isset($orderpackage)) {
                        $id_user = $orderpackage->id_user;

                        if (isset($id_user)) {
                            if (isset($orderpackage->number_order)) {
                                $orderFinal = DB::select(
                                    "SELECT * FROM ecomm_orders WHERE number_order=$orderpackage->number_order",
                                );
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

                    $sendedfak = DB::select(
                        "SELECT * FROM invoices_fakturoid WHERE number_order = $orderpackage->number_order",
                    );
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
                  <td>{{ $orderpackage->payment_method }}</td>
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
                  @if (strtolower($orderpackage->status) == 'paid')
                    <td>
                      <a type="button" class="btn btn-primary btn-sm m-0"
                        href="{{ route('admin.packages.order_products_label', $orderpackage->number_order) }}">
                        shipping label
                      </a>
                    </td>
                    <td>
                      <a type="button" class="btn btn-primary btn-sm m-0"
                        href="{{ route('admin.tracking') . "?order=$orderpackage->number_order" }}">
                        Track
                      </a>
                    </td>
                    <td>
                      <div class="modal fade" id="staticBackdropcancel{{ $orderpackage->number_order }}"
                        data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="staticBackdropLabel">Cancel order -
                                {{ $orderpackage->number_order }}</h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close">X</button>
                            </div>
                            <div class="modal-body">
                              <form action="{{ route('admin.packages.orderfilter.CancelOrders') }}" method="POST"
                                id="form-cancell-order-{{ $orderpackage->number_order }}"
                                style="display: flex;flex-direction: column;gap:1rem">
                                @csrf
                                @php
                                  $idInputCancel = "confirm-$orderpackage->number_order";
                                  $idBtnCancel = "btn-confirm-cancell-$orderpackage->number_order";
                                  $idinputOrder = "input-number-order-$orderpackage->number_order";
                                @endphp
                                <input type="hidden" id="{{ $idinputOrder }}" name="order">

                                <div class="input-group">
                                  <span class="input-group-text">Reason</span>
                                  <textarea class="form-control" aria-label="Reason" name="reason" required></textarea>
                                </div>
                                <div class="input-group flex-nowrap">
                                  <span class="input-group-text" id="addon-wrapping">type CONFIRM</span>
                                  <input type="text" class="form-control" placeholder="type CONFIRM" name="confirm"
                                    aria-describedby="addon-wrapping" required id="{{ $idInputCancel }}"
                                    onkeyup="typeCONFIRM('{{ $idInputCancel }}','{{ $idBtnCancel }}','{{ $idinputOrder }}','{{ $orderpackage->number_order }}')">
                                </div>
                              </form>
                            </div>
                            <div class="modal-footer">
                              <a type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</a>
                              <button type="button" class="btn btn-danger" id="{{ $idBtnCancel }}" type="submit"
                                style="display: none" form="form-cancell-order-{{ $orderpackage->number_order }}"
                                onclick="cancelarPedido('{{ $orderpackage->number_order }}')">Confirm</button>
                            </div>
                          </div>
                        </div>
                      </div>
                      {{-- <a type="button" class="btn btn-primary btn-sm m-0" style="background: red;"
                      href="{{ route("admin.packages.orderfilter.CancelOrders", ['id' => $orderpackage->number_order]) }}">
                      Cancel
                    </a> --}}
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#staticBackdropcancel{{ $orderpackage->number_order }}">
                        Cancel
                      </button>
                    </td>

                    <td>
                      <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#exampleModal{{ $orderpackage->id }}">
                        Edit
                      </button>

                      <div class="modal fade" id="exampleModal{{ $orderpackage->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Order
                                {{ $orderpackage->number_order }}
                              </h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form
                                action="{{ route('admin.packages.orderfilter.products.corporate.save', $orderpackage->id) }}"
                                method="POST" id="form-{{ $orderpackage->id }}">
                                @csrf

                                <br>
                                <select name="status" id="selectMethod" onchange="changeMetodo(event)"
                                  style="font-size: 1rem"
                                  class="form-control form-control-lg @error('payment') is-invalid @enderror" required>
                                  <option value="">Change status</option>
                                  @if (strtolower($orderpackage->status) != 'paid')
                                    <option value="paid">Pay</option>
                                  @endif
                                  <option value="Pending">Pending</option>
                                  {{-- <option value="cancelled">Cancell</option> --}}
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
                    </td>
                  @else
                    <td>
                    </td>

                    <td>
                    </td>

                    <td>
                    </td>

                    <td>
                      <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#exampleModal{{ $orderpackage->id }}">
                        Edit
                      </button>

                      <div class="modal fade" id="exampleModal{{ $orderpackage->id }}" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h1 class="modal-title fs-5" id="exampleModalLabel">Order
                                {{ $orderpackage->number_order }}
                              </h1>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <form
                                action="{{ route('admin.packages.orderfilter.products.corporate.save', $orderpackage->id) }}"
                                method="POST" id="form-{{ $orderpackage->id }}">
                                @csrf

                                <br>
                                <select name="status" id="selectMethod" onchange="changeMetodo(event)"
                                  style="font-size: 1rem"
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
      {{-- PAGINATE --}}
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  @section('js')
    <script>
      $(document).ready(function() {
        $('#tabela-dados').DataTable();
      });

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

      function cancelarPedido(order) {
        form = document.querySelector(`#form-cancell-order-${order}`);
        form.submit();
      }

      function ordenarTabela(coluna) {
        const tabela = document.getElementById("tabela-dados");
        const linhas = Array.from(tabela.getElementsByTagName("tr"));

        linhas.sort((a, b) => {
          const valorA = a.getElementsByTagName("td")[coluna].textContent.trim();
          const valorB = b.getElementsByTagName("td")[coluna].textContent.trim();

          if (isNaN(valorA) || isNaN(valorB)) {
            return valorA.localeCompare(valorB);
          } else {
            return parseInt(valorA) - parseInt(valorB);
          }
        });

        linhas.forEach((linha, index) => {
          tabela.appendChild(linha);
        });
      }
    </script>
  @stop
