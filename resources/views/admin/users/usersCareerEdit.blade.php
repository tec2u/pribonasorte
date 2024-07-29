@extends('adminlte::page')

@section('title', 'Career')

@section('content_header')
  <h4>Career</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Career users</h3>

        <a href="{{ route('admin.users.index') }}">
          <button class="btn btn-warning btn-sm" type="button">
            Back to users
          </button>
        </a>
      </div>
    </div>
    <div class="card-body table-responsive">
      <div class="row">
        <div class="col-sm-12 col-md-12">
          {{-- <div class="text-right"> --}}

          <div style="display: flex; justify-content:space-between">

            <div class="card-tools" style="width: 100%;display:flex;justify-content: center">

              <div class="input-group input-group-sm my-1"
                style="width: 100%;display:flex;justify-content: center; flex-direction: column; align-items: center;">
                <div style="width: fit-content; display: block; margin-bottom: 10px;"><span>Login or ID user</span>
                </div>
                <form method="POST" class="d-flex" style="width: fit-content; flex-direction: column; gap:1rem"
                  action="{{ route('admin.users.careerUserUpdate') }}">
                  @csrf
                  <input type="text" name="user" style="padding-left: 10px;" placeholder="Login user"
                    value="{{ $user->login ?? '' }}">

                  <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupSelect01">Career</label>
                    <select class="form-select" id="inputGroupSelect01" name="career_id">
                      @foreach ($carreiras as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="input-group-append">
                    <button type="submit" style="color: #ffffff;" class="btn btn-warning">
                      <i class="bi bi-search">Edit</i>
                    </button>
                  </div>
                </form>
              </div>

            </div>
          </div>

          {{-- </div> --}}
        </div>
        {{-- <div class="col-sm-12 col-md-12" style="display: flex;justify-content: right;">
          <button id="btnExport" onclick="excell()" class="btn btn-warning btn-sm"
            style="height: fit-content; background-color: green ">Excel</button>
        </div> --}}
      </div>
      <span class="counter float-right"></span>

    </div>
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

  <script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>

  <script>
    $(document).ready(function() {
      $('#maskdata').mask('99/99/9999');
      $('#maskdata2').mask('99/99/9999');
      return false;
    });


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
        a.download = 'Career_lifeprosper.xlsx'; // Nome do arquivo Excel
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
