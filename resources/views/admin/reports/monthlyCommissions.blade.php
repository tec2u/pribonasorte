@extends('adminlte::page')

@section('title', 'Monthly Commissions')

@section('content_header')
  <div class="alignHeader">
    <h4>Monthly Commissions</h4>
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

            <div class="col-12 mb-2">
            <form class="row g-3" method="GET" action="{{ route('admin.reports.monthlyCommissionsFilter') }}">
                @csrf
                <div class="col-12 d-flex">
                    <div class="col">
                        <label style="margin-top: 10px;">Name:</label>
                        <input type="text" class="form-control" name="name" placeholder="Name" value="{{isset($name) ? $name : ''}}">
                    </div>
                    <div class="col">
                        <label style="margin-top: 10px;">Last Name:</label>
                        <input type="text" class="form-control" name="lastname" placeholder="Last name" value="{{isset($lastname) ? $lastname : ''}}">
                    </div>
                </div>
                <div class="col-12 d-flex">
                    <div class="col-5">
                        <label style="margin-top: 10px;">@lang('admin.btn.firstdate'):</label>
                        <input type="date" class="form-control" name="fdate" value="{{isset($data_in) ? $data_in : ''}}">
                    </div>
                    <div class="col-5">
                        <label style="margin-top: 10px;">@lang('admin.btn.seconddate'):</label>
                        <input type="date" class="form-control" name="sdate" value="{{isset($data_out) ? $data_out : ''}}">
                    </div>
                    <div class="col d-flex align-items-end justify-content-end">
                        <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info" style="padding: 6px 28px;">
                    </div>
                </div>
            </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-12 d-flex" style="justify-content: end">
                <button id="btnExport" onclick="excell()" class="btn btn-warning btn-sm" style="height: fit-content; background-color: green; padding: 10px 20px 10px 20px;">Report Excel</button>
                {{-- <a href="{{ route('admin.reports.monthlyCommissionsExcel') }}"><p style="float: right; font-size: 18px;">Export Excel</p></a> --}}
                {{-- <p style="float: left; margin: 10px 0px 0px 30px;">Total : € 0.00</p> --}}
            </div>
        </div>
    </div>
    <div class="card-body table-responsive">
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results" id="tabela-career">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>User Name</th>
                <th>Total</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($commissions as $commission)
            <tr>
                @if ($commission->total != 0)
                    <td>{{ $commission->id }}</td>
                    <td>{{ $commission->name }}</td>
                    <td>{{ $commission->login }}</td>
                    <td>{{ $commission->total }}</td>
                    <td><a href="{{ route('admin.reports.monthlyCommissionsDetail', ['id' => $commission->id]) }}"><button class="btn btn-info" style="width: 100%;">Detail</button></a></td>
                @endif
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
@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js"
    integrity="sha512-dlPw+ytv/6JyepmelABrgeYgHI0O+frEwgfnPdXDTOIZz+eDgfW07QXG02/O8COfivBdGNINy+Vex+lYmJ5rxw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

    <script>
        function excell() {
        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Sheet 1');

        // Seleciona a tabela de dados
        const tabela = document.getElementById('tabela-career');
        const rows = tabela.querySelectorAll('tbody > tr');

        // Adiciona cabeçalhos à planilha
        const headers = [];
        tabela.querySelectorAll('thead > tr > th').forEach(header => {
            headers.push({
            header: header.innerText,
            key: header.innerText.toLowerCase().replace(' ', '_')
            });
        });
        worksheet.columns = headers;

        // Adiciona os dados à planilha
        rows.forEach(row => {
            const rowData = [];
            row.querySelectorAll('td').forEach(cell => {
            rowData.push(cell.innerText);
            });
            worksheet.addRow(rowData);
        });

        workbook.xlsx.writeBuffer().then(function(buffer) {
            const blob = new Blob([buffer], {
            type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'commission_Pribonasorte.xlsx'; // Nome do arquivo Excel
            a.click();
            window.URL.revokeObjectURL(url);
        });

        }
    </script>
@stop
