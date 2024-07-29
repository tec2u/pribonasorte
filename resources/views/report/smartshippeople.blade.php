@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('network.smrtship_distributors')</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('network.all_distributors')</h3>
                </div>
                <div class="card-header py-3">
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                  <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                  <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                  <div class="card-tools">
                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                      <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                        placeholder="@lang('withdraw.search')">
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
                        <th>@lang('network.name')</th>
                        <th>Login</th>
                        <th>E-mail</th>
                        <th>@lang('network.phone')</th>
                        <th>@lang('network.country')</th>
                        <th>CV</th>
                        <th>QV</th>
                        <th>@lang('network.city')</th>
                        <th>@lang('network.next_pay')</th>
                        <th>@lang('network.active')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($smartshippeople as $recruits)
                        @php
                          $id_user = $recruits->id;
                          $qv = DB::select(
                              "SELECT SUM(qv) AS total FROM ecomm_orders WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND id_user = '$id_user' AND smartshipping = 1",
                          );
                          $cv = DB::select(
                              "SELECT SUM(cv) AS total FROM ecomm_orders WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND id_user = '$id_user' AND smartshipping = 1",
                          );
                          $cv = isset($cv[0]->{'total'}) ? $cv[0]->{'total'} : 0;
                          $qv = isset($qv[0]->{'total'}) ? $qv[0]->{'total'} : 0;
                        @endphp
                        <tr>
                          <th>{{ $recruits->name }} {{ $recruits->last_name ?? '' }}</th>
                          <th>{{ $recruits->login }}</th>
                          <th>{{ $recruits->email }}</th>
                          <th>{{ $recruits->cell ?? $recruits->telephone }}</th>
                          <th>{{ $recruits->country }}</th>
                          <th>{{ $cv }}</th>
                          <th>{{ $qv }}</th>
                          <th>{{ $recruits->city }}</th>
                          <th>{{ date('d/m/Y', strtotime($recruits->cobranca ?? '')) }}</th>
                          <th>{{ $recruits->ativo ?? '' }}</th>
                        </tr>
                      @empty
                        <p class="m-4 fst-italic">@lang('network.no_record_smart')</p>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                <div class="card-footer clearfix py-3">
                  <ul class="pagination pagination-sm m-0 float-right">
                    {{-- {{$newrecruits->links()}} --}}
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="row" style="margin-top: 40px;">
            <div class="col-12">
              <h1>@lang('network.no_record_smart')</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('network.all_direct_customers')</h3>
                </div>
                <div class="card-header py-3">
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                  <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                  <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                  <div class="card-tools">
                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                      <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                        placeholder="@lang('withdraw.search')">
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
                        <th>@lang('network.name')</th>
                        <th>E-mail</th>
                        <th>@lang('network.phone')</th>
                        <th>@lang('network.country')</th>
                        <th>CV</th>
                        <th>QV</th>
                        <th>@lang('network.city')</th>
                        <th>@lang('network.next_pay')</th>
                        <th>@lang('network.active')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($costumer as $itens)
                        @php
                          $id_user = $itens->id;
                          $all_resgister = DB::select(
                              "SELECT * FROM ecomm_orders WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND id_user = '$id_user' AND smartshipping = 1",
                          );
                          $register_count = count($all_resgister);
                          $qv = DB::select(
                              "SELECT SUM(qv) AS total FROM ecomm_orders WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND id_user = '$id_user' AND smartshipping = 1",
                          );
                          $cv = DB::select(
                              "SELECT SUM(cv) AS total FROM ecomm_orders WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND id_user = '$id_user' AND smartshipping = 1",
                          );
                          $cv = isset($cv[0]->{'total'}) ? $cv[0]->{'total'} : 0;
                          $qv = isset($qv[0]->{'total'}) ? $qv[0]->{'total'} : 0;
                        @endphp

                        @if ($register_count > 0)
                          <tr>
                            <th>{{ $itens->name }} {{ $itens->last_name }}</th>
                            <th>{{ $itens->email }}</th>
                            <th>{{ $itens->phone }}</th>
                            <th>{{ $itens->country }}</th>
                            <th>{{ number_format($cv, 2, ',', '.') }}</th>
                            <th>{{ number_format($qv, 2, ',', '.') }}</th>
                            <th>{{ $itens->city }}</th>
                            <th>{{ date('d/m/Y', strtotime($itens->cobranca ?? '')) }}</th>
                            <th>{{ $itens->ativo ?? '' }}</th>
                          </tr>
                        @endif

                      @empty
                        <p class="m-4 fst-italic">@lang('network.no_record_smart') records for new smartship</p>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                <div class="card-footer clearfix py-3">
                  <ul class="pagination pagination-sm m-0 float-right">
                    {{-- {{ $newrecruits->links() }} --}}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
