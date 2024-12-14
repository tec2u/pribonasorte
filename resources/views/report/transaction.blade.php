@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    @include('flash::message')
    <section id="transaction" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('reports.transaction.title')</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('reports.transaction.title1')</h3>
                </div>
                <div class="card-header py-3">

                    <div class="row">
                        {{-- <div class="col-sm-6 col-md-4">
                        <form action="{{ route('reports.searchTransactions') }}" method="GET">
                            @csrf
                            <div class="input-group input-group-lg">
                            <input type="text" name="search" style="width: 100px; height: 40px;" class="form-control @error('search') is-invalid @enderror"
                                placeholder="@lang('admin.transaction.searchuser')">
                            <span class="input-group-append">
                                <button type="submit" style="height: 40px; font-size: 12px;" class="btn btn-info btn-flat">@lang('admin.btn.search')</button>
                            </span>
                            @error('search') --}}
                                {{-- <span class="error invalid-feedback">{{ $message }}</span> --}}
                            {{-- @enderror
                            </div>
                        </form>
                        </div> --}}

                        <div class="col-12 col-md-8" style="margin-top: -15px; width: 100%;">
                        <form class="row g-3" method="GET" action="{{ route('reports.getDateTransactions') }}">
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
                            <p style="float: left; margin: 10px 0px 0px 30px;">Total : € {{ number_format($totalTrans,2,",",".")}}</p>
                            </div>
                        </form>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-12 d-flex" style="width: 100%; margin-top: 20px; margin-bottom: 10px;">
                            <form method="GET" class="d-flex" style="float: left; width: 300px;" action="{{ route('reports.transactionsFilters') }}">
                                <select class="form-select" aria-label="Default select example" name="filter" required>
                                    @if (isset($filter) && !$filter)
                                        <option value="" selected>Select type</option>
                                    @endif

                                    @foreach ($bonus as $items)
                                    <option value="{{ $items->id }}">{{ $items->description }}</option>
                                    @endforeach
                                    {{-- <option value="99" @if (isset($filter) && $filter == 99) selected @endif>Buy Products</option>
                                    <option value="12" @if (isset($filter) && $filter == 99) selected @endif>1st Order Bonus</option>
                                    <option value="1" @if (isset($filter) && $filter == 99) selected @endif>Level Bonus</option>
                                    <option value="2" @if (isset($filter) && $filter == 99) selected @endif>Generation Bonus</option> --}}
                                </select>
                                <div class="input-group-append">
                                <button type="submit" class="btn btn-info" style="width: 150px;">
                                    Search
                                </button>
                                </div>
                            </form>
                        </div>
                    </div>
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                                                  <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                                                  <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->

                  {{-- <div style="display: flex; justify-content:space-between">
                    <div class="card-tools">
                      <span>Type AND Date</span>
                      <div class="input-group input-group-sm my-1" style="width: 250px;">
                        <form method="GET" class="d-flex" action="{{ route('reports.transactions') }}">
                          <select class="form-select" aria-label="Default select example" name="filter" required>
                            @if (!$filter)
                              <option value="" selected>Select type</option>
                            @endif

                            @foreach ($bonus as $item)
                              <option value="{{ $item->id }}" @if (isset($filter) && $filter == $item->id) selected @endif>
                                {{ $item->description }}</option>
                            @endforeach
                            <option value="99" @if (isset($filter) && $filter == 99) selected @endif>Buy Products</option>
                          </select>
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                              <i class="bi bi-search"></i>
                            </button>
                          </div>
                          </form>
                      </div> --}}
                      {{-- <div class="input-group input-group-sm my-1" style="width: 250px;"> --}}
                        {{-- <form method="GET" class="d-flex" action="{{ route('reports.transactions') }}"> --}}
                        {{-- <input type="month" name="date" required value="{{ $date ?? null }}">
                        <div class="input-group-append"> --}}
                          {{-- <button type="submit" class="btn btn-default">
                            <i class="bi bi-search"></i>
                          </button> --}}
                        {{-- </div>
                        </form>
                      </div>
                    </div>
                    <div class="card-tools">
                      <span>Type OR Date</span>
                      <div class="input-group input-group-sm my-1" style="width: 250px;">
                        <form method="GET" class="d-flex" action="{{ route('reports.transactions') }}">
                          <select class="form-select" aria-label="Default select example" name="filter" required>
                            @if (!$filter)
                              <option value="" selected>Select type</option>
                            @endif

                            @foreach ($bonus as $item)
                              <option value="{{ $item->id }}" @if (isset($filter) && $filter == $item->id) selected @endif>
                                {{ $item->description }}</option>
                            @endforeach
                            <option value="99" @if (isset($filter) && $filter == 99) selected @endif>Buy Products</option>
                          </select>
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                              <i class="bi bi-search"></i>
                            </button>
                          </div>
                        </form>
                      </div>
                      <div class="input-group input-group-sm my-1" style="width: 250px;">
                        <form method="GET" class="d-flex" action="{{ route('reports.transactions') }}">
                          <input type="month" name="date" required value="{{ $date ?? null }}">
                          <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                              <i class="bi bi-search"></i>
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>@lang('reports.transaction.descr')</th>
                        <th>@lang('reports.transaction.price')</th>
                        <th>@lang('reports.transaction.from')</th>
                        <th>Package</th>
                        <th>Package Value</th>
                        <th>QV</th>
                        <th>CV</th>
                        <th>Level</th>
                        <th>@lang('reports.transaction.id_order')</th>
                        <th>@lang('reports.transaction.date')</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tbody>
                      @forelse($transactions as $transaction)
                        <tr>
                          <th>{{ $transaction->id }}</th>
                          @if ($transaction->description != 99)
                            <td>
                              {{ $transaction->config_bonus->description }}
                            </td>
                          @else
                            <td>Payment order</td>
                          @endif
                          <td><span class="rounded-pill bg-success px-4 py-1">€
                              {{ number_format($transaction->price, 2, ',', '.') }}</span></td>
                          <td>{{ $transaction->getUserOrderLogin($transaction->order_id) }}</td>
                          <td><span class="rounded-pill bg-warning px-4 py-1">Product Purchase</span></td>
                          <td><span class="rounded-pill bg-primary px-4 py-1">€
                              {{ number_format($transaction->getUserOrderPackageValue($transaction->order_id), 2, ',', '.') }}</span>
                          </td>
                          <td>{{ $transaction->qv }}</td>
                          <td>{{ $transaction->cv }}</td>
                          <td>{{ $transaction->level_from }}</td>
                          <td>{{ $transaction->order_id }}</td>
                          <td>{{ date('d/m/Y', strtotime($transaction->created_at)) }}</td>
                        </tr>
                      @empty
                        <p class="m-4 fst-italic">@lang('reports.transaction.empyt')</p>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                <div class="card-footer clearfix py-3">
                  {{ $transactions->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
