@extends('adminlte::page')

@section('title', 'Report orders payed')

@section('content_header')
  <h4>Orders</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>All Orders Payed</h3>

        <a href="{{ route('admin.reports.ordersPayed') }}">
          <button class="btn btn-warning btn-sm" type="button">
            See All
          </button>
        </a>
      </div>
    </div>
    <div class="card-body table-responsive">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <div class="input-group input-group-sm my-1" style="width: 250px;">
            <form method="GET" class="d-flex" action="{{ route('admin.reports.ordersPayed') }}">
              <input type="month" name="date" required value="{{ $date ?? null }}">
              <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                  <i class="bi bi-search">Search</i>
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="col-sm-12 col-md-6" style="display: flex;justify-content: right">
          <button id="btnExport" onclick="excell()" class="btn btn-warning btn-sm"
            style="height: fit-content; background-color: green ">Excel</button>
        </div>
      </div>
      <span class="counter float-right"></span>
      <table class="table table-hover table-bordered results" id="tabela-career">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>login</th>
            <th>Order</th>
            <th>Price</th>
            <th>Date</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
          </tr>
        </thead>
        <tbody>
          @if (isset($orders) and !empty($orders))

            @foreach ($orders as $order)
              <tr>
                <td>{{ $order->id_user }}</td>
                <td>{{ $order->username }}</td>
                <td>{{ $order->login }}</td>
                <td>{{ $order->number_order }}</td>
                <td>{{ $order->total_price }}</td>
                <td>{{ \Carbon\Carbon::parse($order->updated_at)->format('d/m/Y') }}</td>
              </tr>
            @endforeach

          @endif

        </tbody>
      </table>

    </div>

    {{-- <div class="card-footer clearfix py-3">
      <ul class="pagination pagination-sm m-0 float-right">
         <div class="card-footer clearfix py-3">
            {{$users->appends([
               'search'=>request()->get('search','')
            ])->links()}}
         </div>
      </ul>
   </div> --}}
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
        a.download = 'all_orders_lifeprosper.xlsx'; // Nome do arquivo Excel
        a.click();
        window.URL.revokeObjectURL(url);
      });

    }
  </script>

  <script>
    $('#flash-overlay-modal').modal();
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
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
