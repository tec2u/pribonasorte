@extends('adminlte::page')

@section('title', 'List Posts')

@section('content_header')
    <h4>Posts News</h4>
@stop

@section('content')
    {{-- @include('flash::message') --}}
    <div class="card">
        <div class="card-header">
            <div class="alignPackage">
                <h3>News Lifeproper</h3>
            </div>
        </div>
        <div class="card-body table-responsive">
            <div class="form-group float-right">
                <input type="text" class="search form-control" placeholder="@lang('admin.btn.search')">
            </div>
            <span class="counter float-right"></span>
            <table class="table table-hover table-bordered results">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Activated</th>
                        <th></th>
                    </tr>
                    <tr class="warning no-result">
                        <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $list)
                        <tr width="100%">
                            <th width="50%">{{ $list->title }}</th>
                            <td width="20%">{{ $list->activated }}</td>
                            <td width="30%">
                                <a href="{{ route('admin.news.PostsNewsEdit', ['id' => $list->id]) }}"><button type="button">Edit news</button></a>
                                <a href="{{ route('admin.news.PostsNewsDelete', ['id' => $list->id]) }}"><button type="button">Delete news</button></a>
                                @if ($list->activated == 1)
                                <a href="{{ route('admin.news.PostsNewsDisabled', ['id' => $list->id]) }}"><button type="button">Disabled news</button></a>
                                @else
                                <a href="{{ route('admin.news.PostsNewsActivated', ['id' => $list->id]) }}"><button type="button">Activated news</button></a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <p>@lang('admin.withdrawrequest.request.empty')</p>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix py-3">
            {{ $news->links() }}
        </div>
    </div>

@stop
@section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous">
</script>
@section('js')
    <script>
        $(document).ready(function() {
            $(".search").keyup(function() {
                var searchTerm = $(".search").val();
                var listItem = $('.results tbody').children('tr');
                var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

                $.extend($.expr[':'], {
                    'containsi': function(elem, i, match, array) {
                        return (elem.textContent || elem.innerText || '').toLowerCase().indexOf(
                            (match[3] || "").toLowerCase()) >= 0;
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
