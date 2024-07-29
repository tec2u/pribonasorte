@extends('adminlte::page')

@section('title', 'Bonus')

@section('content_header')
  <div class="alignHeader">
    <h4>Bonus</h4>
  </div>
  <i class="fa fa-home ml-3"></i> - @lang('admin.transaction.title2')
@stop

@section('content')

  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col-12 col-md-8">
          <form class="row g-3" method="GET" action="{{ route('admin.reports.getDatereportBonus') }}">
            @csrf
            <div class="col-auto">
              <label>Date:</label>
            </div>
            <div class="col">
              <input type="month" class="form-control" name="date" required value="{{ $date }}">
            </div>
            {{-- <div class="col-auto">
              <label>Year:</label>
            </div>
            <div class="col">
              <input type="date" class="form-control" name="sdate">
            </div> --}}
            <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info">
            <button id="btnExport" onclick="excell()" class="btn btn-warning btn-sm ml-3"
              style="height: fit-content; background-color: green; padding: 10px 20px 10px 20px;">Report Excel</button>
          </form>
        </div>
      </div>
    </div>
    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <table class="table table-hover table-bordered results" id="tabela-bonus">
        <thead>
          <tr>
            <th>User ID</th>
            <th>User Name</th>
            <th>Career</th>
            <th>Volume Required</th>
            <th>Personal Vol.</th>
            <th>Personal Vol. Extern.</th>
            <th>Team Vol.</th>
            <th>TOTAL COMM</th>
            <th>LEVEL COMM</th>
            <th>FAST START BONUS</th>
            <th>FAST START TEAM</th>
            <th>FIRST ORDER BONUS</th>
            <th>CUSTOMER COMM</th>
            <th>PERSONAL BONUS</th>
            <th>GENERATION COMM</th>

            <th>POWER OF 3 BONUS</th>
            <th>LIFESTYLE BONUS</th>
            <th>LEADER BONUS</th>

            <th>ADVANCEMENT BONUS</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.btn.noresults')</td>
          </tr>
        </thead>
        <tbody>

          @forelse($transactions as $transaction)
            @if ($transaction->total_price != 0)
              <tr>
                <td>{{ $transaction->id }}</td>
                <td>{{ $transaction->login }}</td>
                <td>
                  @if (isset($career[$transaction->id][0]->name))
                    {{ $career[$transaction->id][0]->name }}
                  @endif
                </td>
                <td>
                  @if (isset($career[$transaction->id][0]->volumeRequired))
                    {{ $career[$transaction->id][0]->volumeRequired }}
                  @endif
                </td>
                <td>
                  @if (isset($personal_volume[$transaction->id][0]->total))
                    {{ $personal_volume[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($PersonalVolumeExternal[$transaction->id][0]->total))
                    {{ $PersonalVolumeExternal[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($team_volume[$transaction->id][0]->total))
                    {{ $team_volume[$transaction->id][0]->total }}
                  @endif
                </td>



                <td style='color:green'>
                  @if (isset($totalcomm[$transaction->id]))
                    {{ $totalcomm[$transaction->id] }}
                  @endif
                </td>
                <!-- BONUS -->
                <td>
                  @if (isset($level_bonus[$transaction->id][0]->total))
                    {{ $level_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($faststart_bonus[$transaction->id][0]->total))
                    {{ $faststart_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($faststart_team[$transaction->id][0]->total))
                    {{ $faststart_team[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($firstorder_bonus[$transaction->id][0]->total))
                    {{ $firstorder_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($customer_bonus[$transaction->id][0]->total))
                    {{ $customer_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($personal_bonus[$transaction->id][0]->total))
                    {{ $personal_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($generation_bonus[$transaction->id][0]->total))
                    {{ $generation_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($power3_bonus[$transaction->id][0]->total))
                    {{ $power3_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($lifestyle_bonus[$transaction->id][0]->total))
                    {{ $lifestyle_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($leader_bonus[$transaction->id][0]->total))
                    {{ $leader_bonus[$transaction->id][0]->total }}
                  @endif
                </td>
                <td>
                  @if (isset($advancement_bonus[$transaction->id][0]->total))
                    {{ $advancement_bonus[$transaction->id][0]->total }}
                  @endif
                </td>














                {{-- <td><a class="dropdown-item"
                    href="{{ route('admin.users.transactions', ['id' => $transaction->id, 'date' => $date]) }}"><i
                      class="fa fa-percent"></i>&ensp;Virtual Bank</a>
                </td> --}}
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
    {{ $transactions->links() }}
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

    function excell() {
      const workbook = new ExcelJS.Workbook();
      const worksheet = workbook.addWorksheet('Sheet 1');

      // Seleciona a tabela de dados
      const tabela = document.getElementById('tabela-bonus');
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
        a.download = 'bonus_report.xlsx'; // Nome do arquivo Excel
        a.click();
        window.URL.revokeObjectURL(url);
      });

    }
  </script>
@stop
