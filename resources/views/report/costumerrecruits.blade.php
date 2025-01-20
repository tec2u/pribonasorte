@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>New Costumer Recruits</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">New Costumer Recruits</h3>
                </div>
                <div class="card-header py-3">
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                              <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                              <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                  <div class="card-tools">
                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                      <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                        placeholder="@lang('withdraw.search')">
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
                        <th>Id</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Username</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Register</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($array_user as $recruits)
                        <tr>
                          <th>{{ $recruits['id'] }}</th>
                          <th>{{ $recruits['name'] }} {{ $recruits['last_name'] }}</th>
                          <th>{{ $recruits['email'] }}</th>
                          <th>{{ $recruits['username'] }}</th>
                          <th>{{ $recruits['country'] }}</th>
                          <th>{{ $recruits['city'] }}</th>
                          <th>{{ $recruits['created_at'] }}</th>
                        </tr>
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
