@extends('layouts.header')
@section('content')

<main id="main" class="main">
    <section id="withdrawhistory" class="content">
        <div class="fade">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1>Media - Videos</h1>
                        <div class="card shadow my-3">
                            <div class="card-header bbcolorp">
                                <h3 class="card-title">Videos</h3>
                            </div>
                            <div class="card-header py-3 ">
                                <form class="row g-3" method="GET" action="{{route('videos.index')}}">
                                    @csrf
                                    <div class="col-auto">
                                        <label>@lang('admin.btn.firstdate'):</label>
                                    </div>
                                    <div class="col">
                                        <input type="date" class="form-control" name="fdate" value="{{ $fdate ?? '' }}">
                                    </div>
                                    <div class="col-auto">
                                        <label>@lang('admin.btn.seconddate'):</label>
                                    </div>
                                    <div class="col">
                                        <input type="date" class="form-control" name="sdate" value="{{ $sdate ?? '' }}">
                                    </div>
                                    <input type="submit" value="@lang('admin.btn.search')" class="btn btn-dark">
                                </form>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Title</th>
                                            <th>View</th>
                                            <th>@lang('withdraw.date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($videos as $video)
                                        <tr>
                                            <td>{{$video->id}}</td>
                                            <td>{{$video->title}}</td>
                                            <td><a href="{{ asset('storage/' . $video->path) }}" target="_blank">View</a></td>
                                            <td>{{date('d/m/Y ', strtotime($video->created_at))}}</td>
                                        </tr>
                                        @empty
                                        <p class="m-4 fst-italic">No document registered</p>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer clearfix py-3">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{$videos->links()}}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

@endsection
