@extends('adminlte::page')

@section('title', 'Configuration Popup')

@section('content_header')
<div class="alignHeader">
    <h4>@lang('admin.popup.subtitle')</h4>
</div>
<i class="fa fa-home ml-3"></i> - @lang('admin.popup.title')
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <div class="alignPackage">
            <h3>@lang('admin.popup.edit')</h3>
        </div>
    </div>
    <div class="row d-flex justify-content-center ">
        <div class="col-lg-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">@lang('admin.popup.edit')</h3>
                </div>
                <form action="{{ route('admin.settings.updatepop', ['id' => $popup->id]) }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="title">@lang('admin.popup.table.col1')</label>
                            <input type="title" class="form-control form-control-lg @error('title') is-invalid @enderror" id="title" name="title" placeholder="@lang('admin.popup.table.col1')" value="{{ $popup->title }}">
                            @error('popup')
                            <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col md 6">
                            <div class="form-group">
                                <label for="path">@lang('admin.editPackage.edit.image')</label>
                                <input type="file" name="path" id="path" class="form-control form-control-lg @error('path.*') is-invalid @enderror">
                                @error('path.*')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-right">
        <button type="submit" class="btn brn-lager btn-success">@lang('admin.popup.save')</button>
    </div>
    </form>
</div>
@stop
@section('css')
<link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')
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