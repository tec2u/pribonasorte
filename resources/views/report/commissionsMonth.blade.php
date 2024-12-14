@extends('layouts.header')
@section('content')
<style>
    .flag-content {
        background-color: #fcc44c;
        width: 161px;
        height: 175px;
        padding-bottom: 7px;
        justify-content: end;
        color: #ffff;
        font-weight: bolder;
        line-height: 1.1;
        border-radius: 10px;
        box-shadow: 3px 4px 7px 1px #625b5b;
    }
</style>
<main id="main" class="main">
    <section id="withdrawhistory" class="content">
        <div class="fade">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12 d-flex justify-content-between relative align-items-center">
                            <div>
                                <h1>Commissions Per Month</h1>
                            </div>

                        </div>
                        <div class="card shadow my-3">
                            <div class="card-header bbcolorp">
                                <h3 class="card-title">Commissions Per Month</h3>
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
                                <table width="100%">
                                    <tbody>
                                        <tr>
                                            <td>
                                                @if(isset($total) && $total > 0)
                                                <div class="d-flex justify-content-center" style="position: relative;margin-top:15px;">
                                                    <div class="d-flex flex-column align-items-center flag-content">
                                                        <img src="{{asset('assets/img/flag-star.png')}}" alt="" width="80" style="position:absolute;top:-15px;">
                                                        <div style="font-size: 30px;">{{$total}} R$</div>
                                                        <div style="font-size: 20px;">@lang('network.total')</div>
                                                    </div>
                                                </div>
                                                @endif
                                            </td>
                                            <td>
                                                <table class="table table-hover text-nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th>Year/Month</th>
                                                            <th>Provize</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($commissions as $key => $commission)
                                                        <tr>
                                                            <td>{{ $key }}</td>
                                                            <td>{{ $commission }}</td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
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
