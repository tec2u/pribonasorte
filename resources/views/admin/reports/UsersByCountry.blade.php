@extends('adminlte::page')

@section('title', 'Registrations Report')

@section('content_header')
<div class="alignHeader">
   <h4>Registrations Report</h4>
</div>
<i class="fa fa-home ml-3"></i>  @lang('admin.level.subtitle')
@stop

@section('content')

<style>
    .modal_country{
        background-color: rgba(0, 0, 0, 0.2);
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1000;
    }
    .box_list_country{
        width: 700px;
        height: 450px;
        padding: 30px;
        border-radius: 10px;
        background: #ffffff;
        margin: 10% auto 0px auto;
    }
</style>

<div class="card">
   <div class="card-header">
      <div class="alignPackage">
      <h3>Registrations by Country Report</h3>
      <h5>Registrations by Country</h5>
      </div>
   </div>
   <div class="card-body table-responsive">
      <div class="row">
         <div class="col-sm-12 col-md-4">
           <!-- <form action="{{route('admin.UsernameUpToMaster.searchUserToMaster')}}" method="POST">
               @csrf
               <div class="input-group input-group-lg">
                  <input type="text" name="search" class="form-control @error('search') is-invalid @enderror" placeholder="@lang('admin.btn.search')">
                  </br>

                  <span class="input-group-append">
                     <button type="submit" class="btn btn-info btn-flat">@lang('admin.btn.search')</button>
                  </span>
                  </br>

                  </br>
                  @error('search')
                  <span class="error invalid-feedback">{{$message}}</span>
                  @enderror
               </div>
            </form>-->
         </div>
         <canvas id="myChart" height="100px"></canvas><br> <br> <br>
      <table class="table table-hover table-bordered results">
         <thead>
            <tr>
            <th>country</th>
            <th>Total Registered</th>
            <th></th>
            </tr>
         </thead>
         <tbody>



            @forelse($data as $r)

                @if ($r['country'] != "Select Country")
                <tr>
                    <th><img src="{{$r["flag"]}}" style="width:20px; margin-right: 10px;">{{$r["country"]}}</th>
                    <th>{{$r["amount"]}}</th>
                    @php
                        $flag_country = strtolower($r["country"]);
                        $flag_country = str_replace(' ', '_', $flag_country);
                    @endphp
                    <th><center><a href="{{ route('admin.UsersByCountry.list_country', ['id' => $flag_country]) }}">View list</a></th></center>
                </tr>
                @endif

            @empty
            <p>@lang('admin.level.table.empty')</p>
            @endforelse
         </tbody>
      </table>
   </div>

    @if (isset($modal_country))
    {{-- MODAL --}}
    <div class="modal_country">
        <div class="box_list_country">
            <div style="width: 100%; display: inline-block">
                <div style="width: 50%; display: inline-block; float: left;">
                    <h3>{{ $name_coutry }}</h3>
                </div>
                <div style="width: 50%; display: inline-block; float: left;">
                    <a style="float: right;" href="{{ route('admin.UsersByCountry.index') }}">close list</a>
                </div>
            </div>

            <div style="overflow: scroll; width: 100%; height: 350px; display: inline-block; overflow-x: hidden;">
                <table class="table table-hover table-bordered results">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($modal_country as $user_country)
                        <tr>
                            <th>{{ $user_country->name }}</th>
                            <th>{{ $user_country->email }}</th>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

   <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script type="text/javascript">

        var labels =  {{ Js::from($labelsChart) }};
      var users =  {{ Js::from($dataChart) }};

      const data = {
        labels: labels,
        datasets: [{
          label: 'Users Registration Progression',
          backgroundColor: 'rgb(67, 156, 53)',
          borderColor: 'rgb(67, 156, 53)',
          data: users,
        }]
      };

      const config = {
        type: 'line',
        data: data,
        options: {}
      };

      const myChart = new Chart(
        document.getElementById('myChart'),
        config
      );

</script>
      @stop
