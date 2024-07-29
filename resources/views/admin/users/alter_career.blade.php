@extends('adminlte::page')

@section('title', 'Alter users careers')

@section('content_header')
  <h4>Alter Career Users</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Alter Career</h3>
        <br>
      </div>

    </div>
    <div class="card-body table-responsive">
      <div class="row">
        <div class="col-12">
          <form action="{{ route('admin.users.alter_career') }}" method="GET" class="d-flex justify-content-between">
            @csrf
            <div class="input-group">
              <input type="text" name="search" class="form-control @error('search') is-invalid @enderror"
                value="@if (isset($search)) {{ $search }} @endif" placeholder="@lang('admin.members.search')">
              @error('search')
                <span class="error invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="input-group ml-2">
              <select name="status" class="form-control">
                <option value="-1">All</option>
                <option value="0" @if (isset($status) && $status == 0) selected @endif>@lang('admin.members.filterUser.active')</option>
                <option value="1" @if (isset($status) && $status == 1) selected @endif>@lang('admin.members.filterUser.desactive')</option>
              </select>
              <button type="submit"
                style="float: left; height: 45px; margin: -3px 0px 0px 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; padding: 0px 15px;">@lang('admin.btn.search')</button>
            </div>
          </form>
        </div>
      </div>
      <span class="counter float-right"></span>
      <table class="table table-hover table-bordered results">
        <thead>
          <tr>
            <th>ID</th>
            <th>@lang('admin.members.memberlist.col2')</th>
            <th>Current career</th>
            <th>@lang('admin.members.memberlist.col3')</th>
            <th>Career</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
          </tr>
        </thead>
        <tbody>
          @if (isset($users) and !empty($users))

            @foreach ($users as $user)
              <tr>
                <th>{{ $user->id }}</th>
                <td style="text-align: center; vertical-align: middle; display: none;">
                  @if (!empty($user->image_path))
                    <img style="max-width: 100px" src="{{ asset('storage/' . $user->image_path) }}" />
                  @else
                    <img style="max-width: 100px" src="../../../assetsWelcome/images/favicon.png" />
                  @endif
                </td>
                <td>
                  <b>{{ $user->name }}</b>
                  @if (!empty($user->last_name))
                    <b>{{ $user->last_name }}</b>
                  @endif
                  <br>{{ $user->login }}<br>{{ $user->email }}
                </td>
                <td>
                  @if (isset($user->lastMonthCareerUser->career->name))
                    {{ $user->lastMonthCareerUser->career->name }}
                  @endif
                </td>
                <td>{{ $user->cell }}</td>
                <td>
                  <form method="post" action="{{ route('admin.users.update_career') }}"
                    class="d-flex align-items-center" id="formData{{ $user->id }}" name="formData"><i
                      class="fa fa-briefcase mr-2"></i>
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                    <select name="career_change" class="form-control" onchange="sendForm('formData{{ $user->id }}')">
                      @foreach ($careers as $career)
                        <option value="{{ $career->id }}" @if (isset($user->lastMonthCareerUser->career_id) && $user->lastMonthCareerUser->career_id == $career->id) selected @endif>
                          {{ $career->name }}</option>
                      @endforeach
                    </select>
                  </form>
                </td>
              </tr>
              {{-- @empty
                        <p>@lang('admin.members.memberlist.empty')</p> --}}
            @endforeach
          @endif
        </tbody>
      </table>
      @if (!isset($parameter) and empty($parameter) and !isset($filter_users) and empty($filter_users))
        <div class="card-footer clearfix py-3">
          {{ $users->links() }}
        </div>
      @endif
    </div>
  </div>
@stop
@section('css')
  <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')
  <script>
    $('#flash-overlay-modal').modal();
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
  </script>
  <script>
    function sendForm(id) {
      const form = document.getElementById(id)

      form.submit()
    }
  </script>
@stop
