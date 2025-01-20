@extends('adminlte::page')

@section('title', 'Transactions')

@section('content_header')
  <div class="alignHeader">
    <h4>@lang('admin.transaction.title')</h4>
  </div>
  <i class="fa fa-home ml-3"></i> - @lang('admin.transaction.title2')
@stop

@section('content')

  <div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-sm-12 col-md-12">
            {{-- <div class="text-right"> --}}

            <div style="display: flex; justify-content:space-between">

                @php
                    $date_in  = date('01/m/Y');
                    $date_end = date('t/m/Y');
                @endphp

                <div class="card-tools" style="width: 100%;">

                    <div class="input-group input-group-sm my-1" style="width: 100%; float: left;">
                        <div style="width: 100%; display: inline-block; margin-bottom: 10px;"><span>Search user for Type OR Date</span></div>
                        <form method="GET" class="d-flex" action="{{ route('admin.reports.transactions') }}">
                        <input type="text" name="user_name" style="padding-left: 10px;" placeholder="Login user" value="{{ $usern ?? '' }}">
                        <select class="form-select" aria-label="Default select example" name="filter">
                            <option value="" selected>Select description</option>
                            @if (isset($bonus) && !empty($bonus))
                                @foreach ($bonus as $item)
                                <option value="{{ $item->id }}" @if (isset($filter) && $filter == $item->id) selected @endif>{{ $item->description }}</option>
                                @endforeach
                            @endif
                        </select>

                        @if (!empty($date_1) AND isset($date_1))
                        <input type="text" name="date1" id="maskdata" style="width: 120px; text-align: center;" required value="{{ $date_1 }}">
                        <input type="text" name="date2" id="maskdata2" style="width: 120px; text-align: center;" required value="{{ $date_2 }}">
                        @else
                        <input type="text" name="date1" id="maskdata" style="width: 120px; text-align: center;" required value="{{ $date_in }}">
                        <input type="text" name="date2" id="maskdata2" style="width: 120px; text-align: center;" required value="{{ $date_end }}">
                        @endif

                        <div class="input-group-append">
                            <button type="submit" style="background: green; color: #ffffff;" class="btn btn-default">
                            <i class="bi bi-search">Search</i>
                            </button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- </div> --}}
            </div>
            <div class="col-sm-12 col-md-12" style="display: flex;justify-content: right;">
                <p style="float: left; margin: 10px 0px 0px 30px;">Total : R$  {{ number_format($totalTrans,2,",",".")}}</p>
            </div>
        </div>

        {{-- <div class="row">
            <div class="col-sm-12 col-md-4">
            <form action="{{ route('admin.reports.searchTransactions') }}" method="GET">
                @csrf
                <div class="input-group input-group-lg">
                <input type="text" name="search" style="height: 40px;" class="form-control @error('search') is-invalid @enderror"
                    placeholder="@lang('admin.transaction.searchuser')">
                <span class="input-group-append">
                    <button type="submit" style="height: 40px; font-size: 12px;" class="btn btn-info btn-flat">@lang('admin.btn.search')</button>
                </span>
                @error('search')
                    <span class="error invalid-feedback">{{ $message }}</span>
                @enderror
                </div>
            </form>
            </div>

            <div class="col-12 col-md-8">
            <form class="row g-3" method="GET" action="{{ route('admin.reports.getDateTransactions') }}">
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
                <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info">
            </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12 d-flex" style="justify-content: end">
                <form method="GET" class="d-flex" style="float: right;" action="{{ route('admin.reports.transactions') }}">
                    <select class="form-select" aria-label="Default select example" name="filter" required>
                        @if (isset($filter) && !$filter)
                            <option value="" selected>Select type</option>
                        @endif

                        @if (isset($bonus) && !$bonus)
                            @foreach ($bonus as $item)
                            <option value="{{ $item->id }}" @if (isset($filter) && $filter == $item->id) selected @endif>
                                {{ $item->description }}</option>
                            @endforeach
                        @endif
                        <option value="99" @if (isset($filter) && $filter == 99) selected @endif>Buy Products</option>
                        <option value="12" @if (isset($filter) && $filter == 99) selected @endif>1st Order Bonus</option>
                        <option value="1" @if (isset($filter) && $filter == 99) selected @endif>Level Bonus</option>
                    </select>
                    <div class="input-group-append">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                    </div>
                </form>

                <p style="float: left; margin: 10px 0px 0px 30px;">Total : R$  {{ number_format($totalTrans,2,",",".")}}</p>
            </div>
        </div> --}}
    </div>
    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <table class="table table-hover table-bordered results">
        <thead>
          <tr>
            <th>ID</th>
            <th>User Name</th>
            <th>@lang('reports.transaction.descr')</th>
            <th>@lang('reports.transaction.price')</th>
            <th>Package</th>
            <th>Level</th>
            <th>@lang('reports.transaction.id_order')</th>
            <th>@lang('reports.transaction.date')</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.btn.noresults')</td>
          </tr>
        </thead>
        <tbody>

          @forelse($transactions as $transaction)
            @if ($transaction->price != 0)
              <tr>
                <th>{{ $transaction->id }}</th>
                @if (isset($transaction->username))
                  <th>{{ $transaction->username }}</th>
                @else
                  <th>{{ isset($transaction->user) ? $transaction->user->login : $transaction->name }}</th>
                @endif
                @if ($transaction->description != 99)
                  @if (isset($transaction->config_bonus))
                    <td>
                      {{ $transaction->config_bonus->description }}
                    </td>
                  @else
                    <td>
                      {{ $transaction->config_bonus_description }}
                    </td>
                  @endif
                @else
                  <td>Payment order</td>
                @endif
                <td><span>R$
                    {{ number_format($transaction->price, 2, ',', '.') }}</span></td>
                <td><span>Product Purchase</span></td>
                <td>{{ $transaction->level_from }}</td>
                <td>{{ $transaction->order_id }}</td>
                @if (isset($transaction->banco_created))
                  <td>{{ date('d/m/Y', strtotime($transaction->banco_created)) }}</td>
                @else
                  <td>{{ date('d/m/Y', strtotime($transaction->created_at)) }}</td>
                @endif
              </tr>
            @endif
          @empty
            <p class="m-4 fst-italic">@lang('admin.transaction.table.empty')</p>
          @endforelse
        </tbody>

      </table>
    </div>
  </div>
  <div class="card-footer clearfix py-3">
    {{-- {{ $transactions->appends([
            'search' => request()->get('search', ''),
            'fdate' => request()->get('fdate', ''),
            'sdate' => request()->get('sdate', ''),
        ])->links() }} --}}
  </div>

@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
@section('js')

    <script>
        $(document).ready(function () {
            $('#maskdata').mask('99/99/9999');
            $('#maskdata2').mask('99/99/9999');
            return false;
        });
    </script>

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


