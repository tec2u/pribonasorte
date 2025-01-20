@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    @include('flash::message')
    <section id="userpackageinfo" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('package.user_package_info')</h1>

              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('package.info')</h3>
                </div>
                <div class="card-header py-3">
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                                                                                                                                        <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                                                                                                                                        <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                  <div class="card-tools">
                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                      <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                        placeholder="@lang('package.search')">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                          <i class="bi bi-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>@lang('package.order_id')</th>
                        <th>@lang('package.package_price')</th>
                        <th>@lang('package.date')</th>
                        <th>@lang('package.payment_status')</th>
                        <th>Pagamento</th>
                        <th>Entrega</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($orderProducts as $orderpackage)
                        <tr>
                          <td>{{ $orderpackage->number_order }}</td>
                          <td> <span class="rounded-pill bg-success px-4 py-1">R$  {{ $orderpackage->total_price }}</span>
                          </td>
                          <td>{{ date('d/m/Y', strtotime($orderpackage->created_at)) }}</td>
                          <td>
                            @if ($orderpackage->payment_status == 'paid')
                              <span class="rounded-pill bg-success px-4 py-1">@lang('package.paid')</span>
                            @elseif($orderpackage->payment_status == 'cancelled' || $orderpackage->payment_status == 'expired')
                              <span class="rounded-pill bg-warning px-4 py-1">@lang('package.cancelled')</button>
                              @else
                                <span class="rounded-pill bg-primary px-4 py-1">@lang('package.waiting')</span>
                            @endif
                          </td>
                          <td>
                            @if ($orderpackage->payment_status != 'paid' && $orderpackage->payment_status != 'cancelled')
                              <button class="btn rounded-pill bg-info px-4 py-1"><a
                                  href="{{ route('packages.payment_link_render', ['orderID' => $orderpackage->number_order]) }}">Pagar</a></button>
                            @endif
                          </td>
                          <td><span>{{ $orderpackage->method_shipping }}</span></td>
                          <td>
                            <button class="btn rounded-pill bg-success px-4 py-1"><a
                                href="{{ route('packages.ecommOrdersDetail', $orderpackage->number_order) }}">Detalhe</a></button>
                          </td>
                          @if ($orderpackage->payment_status == 'paid')
                            <td>
                              <a type="button" class="btn rounded-pill bg-success px-4 py-1" target="_blank"
                                href="{{ route('packages.tracking') . "?order=$orderpackage->number_order" }}">
                                Rastrear
                              </a>
                            </td>
                          @endif
                        @empty
                          <p>@lang('package.any_packages_registered')</p>
                      @endforelse
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer clearfix py-3">
                  {{ $orderProducts->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <br>
    <br>
    <br>
    <section id="userpackageinfo" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>Historico de compra de pacotes</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('package.info')</h3>
                </div>
                <div class="card-header py-3">
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                                                                                                                                        <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                                                                                                                                        <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                  <div class="card-tools">
                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                      <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                        placeholder="@lang('package.search')">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                          <i class="bi bi-search"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        {{-- <th>@lang('package.name')</th>
                        <th>Amount</th> --}}
                        <th>Id</th>
                        <th>Referencia</th>
                        <th>@lang('package.package_price')</th>
                        {{-- <th>@lang('package.daily_return')</th>
                                            <th>@lang('package.yearly_return_coin')</th>
                                            <th>@lang('package.total_return_coin')</th>
                                            <th>@lang('package.steaking_period')</th>
                                            <th>@lang('package.capping_coin')</th>
                                            <th>@lang('package.expected_total_return')</th> --}}
                        <th>@lang('package.date')</th>
                        <th>@lang('package.payment_status')</th>

                      </tr>
                    </thead>
                    <tbody>
                      @forelse($orderPackages as $orderpackage)
                        <tr>
                          {{-- <th>{{ $orderpackage->name_product }}</th>
                          <th>{{ $orderpackage->amount }}</th> --}}
                          <td>{{ $orderpackage->id }}</td>
                          <td>{{ $orderpackage->reference }}</td>
                          <td> <span class="rounded-pill bg-success px-4 py-1">R$
                              {{ number_format($orderpackage->price, 2, '.', '') }}</span>
                          </td>
                          {{-- <td>{{$orderpackage->package->daily_returns}}</td>
                                            <td>{{$orderpackage->package->yaerly_returns}}</td>
                                            <td>{{$orderpackage->package->total_returns}}</td>
                                            <td>{{$orderpackage->package->period_days}} @lang('package.months')</td>
                                            <td>{{$orderpackage->package->capping_coin}}</td>
                                            <td>{{number_format($orderpackage->package->packageTotal($orderpackage->package->id),2, ',', '.')}} </td> --}}
                          <td>{{ date('d/m/Y', strtotime($orderpackage->created_at)) }}</td>
                          <td>
                            @if ($orderpackage->payment_status == 1)
                              <span class="rounded-pill bg-success px-4 py-1">@lang('package.paid')</span>
                            @elseif($orderpackage->payment_status == 2)
                              <span class="rounded-pill bg-warning px-4 py-1">@lang('package.cancelled')</button>
                              @else
                                <span class="rounded-pill bg-primary px-4 py-1">@lang('package.waiting')</span>
                            @endif
                          </td>
                          <td>
                            @if ($orderpackage->payment_status == 1)
                              <button class="btn rounded-pill bg-info px-4 py-1"><a target="_blank"
                                  href="{{ route('packages.invoicePackage', ['id' => $orderpackage->id]) }}">invoice</a></button>
                            @endif
                          </td>


                        @empty
                          <p>@lang('package.any_packages_registered')</p>
                      @endforelse
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="card-footer clearfix py-3">
                  {{ $orderPackages->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
