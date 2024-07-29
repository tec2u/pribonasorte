@extends('adminlte::page')

@section('title', 'PopUp Configuration')

@section('content')
@include('flash::message')
<div class="alignHeader">
    <h4>@lang('admin.popup.title')</h4>
</div>
<div class="card">
    <div class="card-header">
        <h3>@lang('admin.popup.subtitle')</h3>
    </div>
    <div class="col-sm-12 col-md-4">
    </div>
    <div class="card-body table-responsive">
        <div class="text-left">

            {{--<a href="{{route('admin.settings.create')}}" class="btn btn-lg bg-success" title="Create Configuration">
            <i class="fas fa-plus-circle"></i> Create Indication Page
            </a>--}}
            <div class="form-group float-right">
                <input type="text" class="search form-control" placeholder="@lang('admin.btn.search')">
            </div>
        </div>
        <span class="counter float-right"></span>
        <!-- Container (Contact Section) -->
        <table class="table table-hover table-bordered results">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>@lang('admin.popup.table.col1')</th>
                    <th>@lang('admin.popup.table.col2')</th>
                    <th>@lang('admin.popup.table.col3')</th>
                    <th>@lang('admin.popup.table.col4')</th>
                    <th>@lang('admin.popup.table.col5')</th>
                </tr>
                <tr class="warning no-result">
                    <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.btn.noresults')</td>
                </tr>
            </thead>
            <tbody>
                @forelse($popup as $pop)
                <tr>
                    <td>{{$pop->id}}</td>
                    <td>{{$pop->title}}</td>
                    <td><img src="{{asset('storage/'.$pop->path)}}" alt="package-cover" class="img-fluid" style="width: 200px;"></td>
                    @if ($pop->activated == 1)
                    <td class="approvd">@lang('admin.whitelist.table.active')</td>
                    @else
                    <td class="text-warning">@lang('admin.whitelist.table.desactive')</td>
                    @endif
                    <td>
                        <div class="btn-group">
                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('admin.settings.inactive', ['id' => $pop->id])}}"><i class="fa fa-power-off"></i>&ensp;@lang('admin.whitelist.table.markdesactive')</a></li>
                                <li><a class="dropdown-item" href="{{route('admin.settings.activated', ['id' => $pop->id])}}"><i class="fa fa-power-off"></i>&ensp;@lang('admin.whitelist.table.markactive')</a></li>
                            </ul>
                        </div>
                    </td>
                    <td><a href="{{route('admin.settings.editpop', ['id' => $pop->id])}}" class="btn bg-teal" title="Edit">
                            <i class="fas fa-edit"></i>
                    </td>
                </tr>
                @empty
                <p>@lang('admin.whitelist.table.empty')</p>
                @endforelse
            </tbody>
        </table>

    </div>
</div>

@stop
@section('css')
<link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')
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
               return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
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