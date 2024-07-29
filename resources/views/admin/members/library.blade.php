@extends('adminlte::page')

@section('title', 'Library')

@section('content_header')
  <h4>@lang('admin.library.list')</h4>
@stop

@section('content')

  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>@lang('admin.library.button_library')<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#libraryadd">
            Add +
          </button></h3>
      </div>
    </div>
    <div style="width: 100%; height: 400px;" class="card-body table-responsive">
      <div class="form-group float-right">
        <input type="text" class="search form-control" placeholder="Search">
      </div>
      <span class="counter float-right"></span>
      <table class="table table-hover table-bordered results">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
        <thead>
          <tr>
            <th>@lang('admin.library.title')</th>
            <th></th>
            <th>@lang('admin.library.action')</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.library.no_result')</td>
          </tr>
        </thead>
        <tbody>
          @if ($library)
            @foreach ($library as $pdf)
              <tr>
                <th>{{ $pdf->title }}</th>
                {{-- <td>{{ $pdf->email }}</td> --}}
                <td></td>
                <td>
                  <a href="{{ route('delete.library', ['id' => $pdf->id]) }}">
                    <button class="btn btn-danger btn-sm m-0">Delete</button>
                  </a>
                  <a href="{{ route('download.library', ['id' => $pdf->id]) }}">
                    <button class="btn btn-success btn-sm m-0">Download</button>
                  </a>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <p style="text-align: center; margin-top: 10px;">@lang('admin.library.no_input')</p>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  {{-- modal add --}}

  <div class="modal fade" id="libraryadd" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <form method="POST" action="{{ route('store.library') }}" enctype="multipart/form-data">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add PDF</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            @csrf

            <div class="form-group">
              <label for="title">@lang('admin.library.title'):</label>
              <input type="text" id="title" name="title" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="pdf_file">@lang('admin.library.choose_pdf'):</label>
              <input type="file" id="pdf_file" name="pdf_file" class="form-control-file" required>
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('admin.library.close')</button>
            <button type="submit" class="btn btn-primary">Add</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  {{--  --}}

@stop
@section('css')
  <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')
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
