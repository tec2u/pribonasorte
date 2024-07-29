@extends('adminlte::page')

@section('title', 'Monthly Commissions')

@section('content_header')
  <div class="alignHeader">
    <h4>Detail Monthly Commissions</h4>
  </div>
  <i class="fa fa-home ml-3"></i> - @lang('admin.transaction.title2')
@stop

@section('content')

  <div class="card">
    <div class="card-header">

        <div class="row">
            {{-- <div class="col-sm-12 col-md-4">
                <form method="GET" class="d-flex" style="float: left;" action="{{ route('admin.reports.transactions') }}">
                    <select class="form-select" aria-label="Default select example" name="filter" required> --}}
                        {{-- @if (isset($filter) && !$filter)
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
                        <option value="1" @if (isset($filter) && $filter == 99) selected @endif>Level Bonus</option> --}}
                    {{-- </select>
                    <div class="input-group-append">
                    <button type="submit" class="btn btn-info">
                        Search
                    </button>
                    </div>
                </form>
            </div> --}}

            <div class="col-12 col-md-8">
                <p style="float:left; font-weight: bold;">User: {{ $user_comm->login }}</p>
                <a href="{{ route('admin.reports.monthlyCommissions') }}"><button class="btn btn-info" style="float: right;">All Commission</button></a>
            {{-- <form class="row g-3" method="GET" action="{{ route('admin.reports.monthlyCommissionsFilter') }}">
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
            </form> --}}
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12 d-flex" style="justify-content: end">

                {{-- <a href="{{ route('admin.reports.monthlyCommissionsExcel') }}"><p style="float: right; font-size: 18px;">Export Excel</p></a> --}}
                {{-- <p style="float: left; margin: 10px 0px 0px 30px;">Total : € 0.00</p> --}}
            </div>
        </div>
    </div>
    <div class="card-body table-responsive">
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results">
            <thead>
            <tr>
                <th>Order</th>
                <th>Description</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
            @forelse($commissions as $commission)
            <tr>
                <td>{{ $commission->order_id }}</td>
                @if ($commission->description == 1)
                <td>Level Bonus</td>
                @elseif ($commission->description == 10)
                <td>Fast Start New Recruit Personal Bonus</td>
                @elseif ($commission->description == 12)
                <td>1st Order Bonus</td>
                @endif
                <td>{{ $commission->price }}</td>
            </tr>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')
  {{-- <script>
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
  </script> --}}
@stop
