@extends('adminlte::page')

@section('title', 'Support')

@section('content_header')
<h4>@lang('admin.support.title')</h4>
@stop

@section('content')

<div class="card">
    <div class="card-header">
        <h3>@lang('admin.support.unanswered')</h3>
    </div>
    <div class="card-body table-responsive">
        <div class="form-group float-right">
            <input type="text" class="search form-control" placeholder="@lang('admin.btn.search')">
        </div>
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results">
            <thead>
                <tr>
                    <th>@lang('admin.support.chat.col1')</th>
                    <th>@lang('admin.support.chat.col2')</th>
                    <th>@lang('admin.support.chat.col3')</th>
                    <th>@lang('admin.support.chat.col4')</th>
                    <th>@lang('admin.support.chat.col5')</th>
                </tr>
                <tr class="warning no-result">
                    <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.btn.noresults')</td>
                </tr>
            </thead>
            <tbody>
                @forelse($unanswereds as $unanswered)
                @php
                    $user_id1      = $unanswered->user_id;
                    $id_support1   = $unanswered->id;
                    $user_support1 = Illuminate\Support\Facades\DB::select("SELECT * FROM users WHERE id = '$user_id1'");
                    $info_support1 = Illuminate\Support\Facades\DB::select("SELECT * FROM message WHERE chat_id = '$id_support1'");
                @endphp
                @if (isset($info_support1[0]->text) AND !empty($info_support1[0]->text))
                <tr>
                    <th>{{$user_support1[0]->name}}</th>
                    <td>{{$unanswered->title}}</td>
                    <td>{{$info_support1[0]->text}}</td>

                    @if (isset($info_support1[0]->date) AND !empty($info_support1[0]->date))
                        <td>{{$info_support1[0]->date}}</td>
                    @else
                        <td>-</td>
                    @endif

                    @switch($unanswered->status)
                    @case(0)
                    <td>
                        <button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.answerChat', $unanswered->id) }}'">@lang('admin.support.chat.answer')</button>
                        <button class="btn btn-danger btn-sm m-0" onclick="location.href = '{{ route('admin.closeChat', $unanswered->id) }}'">@lang('admin.support.chat.close')</button>
                    </td>
                    @break
                    @case(1)
                    <td>
                        <button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.answerChat', $unanswered->id) }}'">@lang('admin.support.chat.answer')</button>
                        <button class="btn btn-danger btn-sm m-0" onclick="location.href = '{{ route('admin.closeChat', $unanswered->id) }}'">@lang('admin.support.chat.close')</button>
                    </td>
                    @break
                    @default
                    <td><button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.reopenChat', $unanswered->id) }}'">@lang('admin.support.chat.reopen')</button></td>
                    @endswitch
                </tr>
                @endif
                @empty
                <p>@lang('admin.support.chat.empty')</p>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix py-3">
        {{ $unanswereds->links() }}
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>@lang('admin.support.answered')</h3>
    </div>
    <div class="card-body table-responsive">
        <div class="form-group float-right">
            <input type="text" class="search form-control" placeholder="@lang('admin.btn.search')">
        </div>
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results">
            <thead>
                <tr>
                    <th>@lang('admin.support.chat.col1')</th>
                    <th>@lang('admin.support.chat.col2')</th>
                    <th>@lang('admin.support.chat.col3')</th>
                    <th>@lang('admin.support.chat.col4')</th>
                    <th>@lang('admin.support.chat.col5')</th>
                </tr>
                <tr class="warning no-result">
                    <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.btn.noresults')</td>
                </tr>
            </thead>
            <tbody>
                @forelse($answereds as $answered)
                @php
                    $user_id2      = $answered->user_id;
                    $id_support2   = $answered->id;
                    $user_support2 = Illuminate\Support\Facades\DB::select("SELECT * FROM users WHERE id = '$user_id2'");
                    $info_support2 = Illuminate\Support\Facades\DB::select("SELECT * FROM message WHERE chat_id = '$id_support2'");
                @endphp
                <tr>
                    <th>{{$user_support2[0]->name}}</th>
                    <td>{{$answered->title}}</td>
                    <td>{{$info_support2[0]->text}}</td>
                    <td>{{$info_support2[0]->date}}</td>
                    @switch($answered->status)
                    @case(0)
                    <td>
                        <button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.answerChat', $answered->id) }}'">@lang('admin.support.chat.answer')</button>
                        <button class="btn btn-danger btn-sm m-0" onclick="location.href = '{{ route('admin.closeChat', $answered->id) }}'">@lang('admin.support.chat.close')</button>
                    </td>
                    @break
                    @case(1)
                    <td>
                        <button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.answerChat', $answered->id) }}'">@lang('admin.support.chat.answer')</button>
                        <button class="btn btn-danger btn-sm m-0" onclick="location.href = '{{ route('admin.closeChat', $answered->id) }}'">@lang('admin.support.chat.close')</button>
                    </td>
                    @break
                    @default
                    <td><button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.reopenChat', $answered->id) }}'">@lang('admin.support.chat.reopen')</button></td>
                    @endswitch
                </tr>
                @empty
                <p>@lang('admin.support.chat.empty2')</p>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix py-3">
        {{ $answereds->links() }}
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>@lang('admin.support.closed')</h3>
    </div>
    <div class="card-body table-responsive">
        <div class="form-group float-right">
            <input type="text" class="search form-control" placeholder="@lang('admin.btn.search')">
        </div>
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results">
            <thead>
                <tr>
                    <th>@lang('admin.support.chat.col1')</th>
                    <th>@lang('admin.support.chat.col2')</th>
                    <th>@lang('admin.support.chat.col3')</th>
                    <th>@lang('admin.support.chat.col4')</th>
                    <th>@lang('admin.support.chat.col5')</th>
                </tr>
                <tr class="warning no-result">
                    <td colspan="4"><i class="fa fa-warning"></i>@lang('admin.btn.noresults')</td>
                </tr>
            </thead>
            <tbody>
                @forelse($closeds as $closed)
                @php
                    $user_id3      = $closed->user_id;
                    $id_support3   = $closed->id;
                    $user_support3 = Illuminate\Support\Facades\DB::select("SELECT * FROM users WHERE id = '$user_id3'");
                    $info_support3 = Illuminate\Support\Facades\DB::select("SELECT * FROM message WHERE chat_id = '$id_support3'");
                @endphp
                <tr>
                    <th>{{$user_support3[0]->name}}</th>
                    <td>{{$closed->title}}</td>
                    <td>{{$info_support3[0]->text}}</td>
                    <td>{{$info_support3[0]->date}}</td>
                    @switch($closed->status)
                    @case(0)
                    <td>
                        <button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.answerChat', $closed->id) }}'">@lang('admin.support.chat.answer')</button>
                        <button class="btn btn-danger btn-sm m-0" onclick="location.href = '{{ route('admin.closeChat', $closed->id) }}'">@lang('admin.support.chat.close')</button>
                    </td>
                    @break
                    @case(1)
                    <td>
                        <button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.answerChat', $closed->id) }}'">@lang('admin.support.chat.answer')</button>
                        <button class="btn btn-danger btn-sm m-0" onclick="location.href = '{{ route('admin.closeChat', $closed->id) }}'">@lang('admin.support.chat.close')</button>
                    </td>
                    @break
                    @default
                    <td><button class="btn btn-primary btn-sm m-0" onclick="location.href = '{{ route('admin.reopenChat', $closed->id) }}'">@lang('admin.support.chat.reopen')</button></td>
                    @endswitch
                </tr>
                @empty
                <p>@lang('admin.support.chat.empty3')</p>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix py-3">
        {{ $closeds->links() }}
    </div>
</div>

@stop
@section('css')
<link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop

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
