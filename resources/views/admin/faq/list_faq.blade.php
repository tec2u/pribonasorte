@extends('adminlte::page')

@section('title', 'List Posts')

@section('content_header')
    <h4>Faqs</h4>
@stop

@section('content')
    {{-- @include('flash::message') --}}
    <div class="card">
        <div class="card-header">
            <div class="alignPackage">
                <h3>Faq Questions</h3>
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
                        <th>Question</th>
                        <th>Category</th>
                        <th></th>
                    </tr>
                    <tr class="warning no-result">
                        <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs as $faq)
                        <tr>
                            <th>{{ $faq->question }}</th>
                            <td>{{ $faq->category }}</td>
                            <td>
                                <a href="{{ route('admin.faq.FaqEdit', ['id' => $faq->id]) }}"><button type="button">Edit post</button></a>
                                <a href="{{ route('admin.faq.FaqDelete', ['id' => $faq->id]) }}"><button type="button">Delete post</button></a>
                            </td>
                        </tr>
                    @empty
                        <p>@lang('admin.withdrawrequest.request.empty')</p>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix py-3">
            {{ $faqs->links() }}
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
