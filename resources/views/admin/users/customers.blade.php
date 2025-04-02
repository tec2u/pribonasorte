@extends('adminlte::page')

@section('title', 'Customers')

@section('content_header')
  <h4>Customers</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <div class="alignPackage">
        <h3>Customers</h3>
      </div>
    </div>
    <div class="card-body table-responsive">
      <div class="row">
        <div class="col-sm-12 col-md-6">
          <form action="{{ route('admin.users.searchUsersCustomers') }}" method="POST">
            @csrf
            <div class="input-group input-group-lg">
              <input type="text" name="search" class="form-control @error('search') is-invalid @enderror"
                placeholder="@lang('admin.members.search')">
              <span class="input-group-append">
                <button type="submit"
                  style="float: left; height: 45px; margin: -3px 0px 0px 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; padding: 0px 15px;">@lang('admin.btn.search')</button>
              </span>
              @error('search')
                <span class="error invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </form>
        </div>
        <div class="col-sm-12 col-md-6">
          <div class="text-right">
            @if (isset($all_states) and !empty($all_states))
              <form action="{{ route('admin.users.filterStatesCustomers') }}" method="GET">
                @csrf
                <select name="states"
                  style="float: left; height: 45px; padding-left: 10px; margin: -15px 10px 0px 20px; border-radius: 5px;">
                  <option value="">Filter for Country</option>
                  @foreach ($all_states as $no_stts)
                    <option value="{{ $no_stts }}">{{ $no_stts }}</option>
                  @endforeach
                </select>
                <button type="submit"
                  style="float: left; height: 45px; margin: -15px 0px 0px 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; padding: 0px 15px;">@lang('admin.btn.search')</button>
              </form>
            @endif

            {{-- <div class="btn-group" style="float: right;">
                  <button type="button" class="btn btn-default btn-lg dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                     @lang('admin.members.filter')
                  </button>
                  <div class="dropdown-menu">
                        <a class="dropdown-item" href="{{ route('admin.users.index') }}">All</a>
                        <a class="dropdown-item" href="{{ route('admin.users.filter', ['parameter' => 'activated']) }}">@lang('admin.members.filterUser.active')Activated</a>
                        <a class="dropdown-item" href="{{ route('admin.users.filter', ['parameter' => 'desactivated']) }}">@lang('admin.members.filterUser.desactive')Desactivated</a>
                  </div>
               </div> --}}
          </div>
        </div>
      </div>
      <span class="counter float-right"></span>
      <table class="table table-hover table-bordered results">
        <thead>
          <tr>
            <th>ID</th>
            <th>@lang('admin.members.memberlist.col2')</th>
            <th>Country</th>
            <th>Sponsor</th>
            <th>Country Sponsor</th>
            <th>@lang('admin.members.memberlist.col3')</th>
          </tr>
          <tr class="warning no-result">
            <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
          </tr>
        </thead>
        <tbody>
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
                <br>{{ $user->login ?? '' }}<br>{{ $user->email }}
              </td>
              <td>{{ $user->country }}</td>
              <td>{{ $user->sponsor }}</td>
              <td>{{ $user->country_sponsor }}</td>
              <td>{{ $user->phone }}</td>

              <td>
                <div class="btn-group">
                  <button class="btn btn-warning btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    @lang('admin.members.btn_action')
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.users.editEcomm', ['id' => $user->id]) }}"><i
                          class="fa fa-eye"></i>&ensp;@lang('admin.members.active.view')</a></li>
                    <li><a class="dropdown-item" target="_blank"
                        href="{{ route('admin.users.dashboardEcomm', ['id' => $user->id]) }}"><i
                          class="fa fa-home"></i>&ensp;@lang('admin.members.active.dashboard')</a></li>
                    <li>
                      <hr class="dropdown-divider">
                    </li>

                  </ul>
                </div>
              </td>
            </tr>
            {{-- @empty
                    <p>@lang('admin.members.memberlist.empty')</p> --}}
          @endforeach
        </tbody>
      </table>
      @if (!isset($parameter) and empty($parameter) and !isset($filter_users) and empty($filter_users))
        <div class="card-footer clearfix py-3">
          {{ $users->links() }}
        </div>
      @endif
    </div>

    {{-- <div class="card-footer clearfix py-3">
      <ul class="pagination pagination-sm m-0 float-right">
         <div class="card-footer clearfix py-3">
            {{$users->appends([
               'search'=>request()->get('search','')
            ])->links()}}
         </div>
      </ul>
   </div> --}}
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
    $(document).ready(function() {
      $(".search").keyup(function() {
        var searchTerm = $(".search").val();
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

        $.extend($.expr[':'], {
          'containsi': function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "")
              .toLowerCase()) >= 0;
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
