@extends('layouts.header')
@section('content')

<main id="main" class="main">
    <section id="withdrawhistory" class="content">
        <div class="fade">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <h1>Team Ranks</h1>
                        <div class="card shadow my-3">
                            <div class="card-header bbcolorp">
                                <h3 class="card-title">CURRENT CAREER</h3>
                                <a href="{{ route('reports.teamranks') }}"><button style="float: right;">HIGHEST CAREER</button></a>
                            </div>
                            <div class="card-header py-3">
                                <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                            <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                            <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                                <div class="card-tools">
                                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                                        <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3" placeholder="@lang('withdraw.search')">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-default">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Login</th>
                                            <th>E-mail</th>
                                            <th>Country</th>
                                            <th>City</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($rankteam as $team)

                                            @php
                                            $id_user    = $team->id;
                                            $career     = Illuminate\Support\Facades\DB::select("SELECT * FROM career_users WHERE created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND user_id = '$id_user' ORDER BY career_id DESC");
                                            $count_user = count($career);
                                            @endphp

                                            @if ($count_user > 0)
                                            <tr>
                                                <th>{{ $team->name }}</th>
                                                <th>{{ $team->login }}</th>
                                                <th>{{ $team->email }}</th>
                                                <th>{{ $team->country }}</th>
                                                <th>{{ $team->city }}</th>
                                            </tr>
                                            @endif
                                        @empty
                                        <p class="m-4 fst-italic">no records for news recruits</p>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer clearfix py-3">
                                <ul class="pagination pagination-sm m-0 float-right">
                                    {{-- {{$newrecruits->links()}} --}}
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
