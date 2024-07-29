@extends('adminlte::page')

@section('title', 'Stock')

@section('content_header')
  <h4>Stock movement Download</h4>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Stock Report</h3>
        @php
          $date_now = date('Y-m-d');
        @endphp
        <div style="float: right;">

        </div>
      </div>
    </div>

    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <div class="row">
        <form method="POST" action="{{ route('admin.packages.stock_admin_report_download') }}" class="row g-3">
          @csrf
          <input type="text" style="display: none;" name="type_report" value="day">
          <label>Report for Day</label>
          <div class="col-auto">
            <input type="date" id="data" name="data_day" required>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Generate</button>
          </div>
        </form>

        <form method="POST" action="{{ route('admin.packages.stock_admin_report_download') }}" class="row g-3">
          @csrf

          <input type="text" style="display: none;" name="type_report" value="week">
          <label>Report for Week</label>
          <div class="col-auto">
            <input type="week" id="data" name="data_week" required>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Generate</button>
          </div>
        </form>


        <form method="POST" action="{{ route('admin.packages.stock_admin_report_download') }}" class="row g-3">
          @csrf
          <input type="text" style="display: none;" name="type_report" value="month">
          <label>Report for Month</label>
          <div class="col-auto">
            <input type="month" id="data" name="data_month" required>
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary mb-3">Generate</button>
          </div>
        </form>


      </div>
      {{-- <div class="row d-flex justify-content-center ">
      {{$orderpackages->links()}}
  </div> --}}
    </div>
    <!-- Modal -->

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
