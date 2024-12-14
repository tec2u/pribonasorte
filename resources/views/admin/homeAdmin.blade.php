@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')

  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css'>
  <link rel='stylesheet'
    href='https://cdn-uicons.flaticon.com/2.0.0/uicons-regular-straight/css/uicons-regular-straight.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/2.0.0/uicons-solid-straight/css/uicons-solid-straight.css'>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <script type="text/javascript">
    google.charts.load("current", {
      packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
      var data = google.visualization.arrayToDataTable(
        1

      );

      var options = {
        title: '',
        is3D: true,
      };

      var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
      chart.draw(data, options);
    }
  </script>

  <style>
    #pop-div {
      width: 100% !important;
      height: 600px !important;
    }

    .box1 {
      width: 100%;
      display: inline-block;
    }

    .box2 {
      width: 50%;
      display: inline-block;
      float: left;
    }

    .notific_icon {
      float: right;
      font-size: 18px;
      margin-top: 10px;
      cursor: pointer;
    }

    .modal_notificac {
      background-color: rgba(0, 0, 0, 0.2);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1000;
    }

    .x1 {
      float: right;
      width: 300px;
      padding: 30px;
      background-color: #ffffff;
      height: 100vh;
      ;
    }

    .title_notifc {
      font-weight: bold;
      font-size: 20px;
      margin-top: 40px;
      float: left;
    }

    .alerts {
      float: right;
      position: relative;
      width: 10px;
      height: 10px;
      border-radius: 100px;
      margin: 10px 0px 0px 15px;
      background-color: #d26075;
    }

    @media (min-width: 800px) {
      .geochart {
        width: 17px !important;
      }
    }

    .cardadmimg {
      width: 60px;
    }
  </style>
  <div class="box1">
    <div class="box2">
      <h1>@lang('admin.dashboard.title')</h1>
    </div>
    <div class="box2">
      <p id="btn_notificac" class="notific_icon">
        <i class="fi fi-ss-cowbell"></i>
        @if ($count_support > 0 or $count_user > 0 or $count_stok > 0)
          <div class="alerts"></div>
        @endif
      </p>
    </div>
  </div>

@stop

@section('content')
  <div class="card">
    <div class="card-body">
      @forelse($users as $user)
        <i class="fa fa-user ml-3"></i>
        <div class="alignHeader">
          {{ $user->name }}
        </div>
      @empty
        <p>@lang('admin.dashboard.no_users')</p>
      @endforelse
    </div>
  </div>

  <div class="row">
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ number_format($commissionSum, 2, ',', '.') }} €</h3>
          <p>@lang('admin.dashboard.commission')</p>
        </div>
        <div class="icon">
          <i class="fas fa-credit-card"></i>
        </div>
        {{-- <a href="{{ route('admin.reports.levelIncome') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ number_format($ecommOrders, 2, ',', '.') }} €</h3>
          <p>@lang('admin.dashboard.sales_comission')</p>
        </div>
        <div class="icon">
          <i class="fas fa-receipt"></i>
        </div>
        {{-- <a href="{{ route('admin.reports.poolcommission') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ number_format($ecommOrdersThisMonth, 2, ',', '.') }} €</h3>
          <p>@lang('admin.dashboard.sales_month')</p>
        </div>
        <div class="icon">
          <i class="fas fa-project-diagram"></i>
        </div>
        {{-- <a href="{{ route('admin.reports.poolcommission') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $usersCont }}</h3>
          <p>@lang('admin.dashboard.registered_dis')</p>
        </div>
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
        {{-- <a href="{{ route('admin.users.index') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $userpay }}</h3>
          <p>@lang('admin.dashboard.actived_dis')</p>
        </div>
        <div class="icon">
          <i class="fas fa-user-check"></i>
        </div>
        {{-- <a href="{{ route('admin.users.index') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $SSDistributor }}</h3>
          <p>@lang('admin.dashboard.smartshipping_dis')</p>
        </div>
        <div class="icon">
          <i class="fas fa-hand-holding-usd"></i>
        </div>
        {{-- <a href="{{ route('admin.withdraw.withdrawRequests') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $CountCustomers }}</h3>
          <p>@lang('admin.dashboard.registered_custom')</p>
        </div>
        <div class="icon">
          <i class="fas fa-hand-holding-usd"></i>
        </div>
        {{-- <a href="{{ route('admin.withdraw.withdrawRequests') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>
    <div class="col-lg-4 col-6">
      <a href="{{ route('admin.reports.smartShippingCustomers') }}">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $SSCustomers }}</h3>
            <p>@lang('admin.dashboard.smartshipping_custom')</p>
          </div>
          <div class="icon">
            <i class="fas fa-hand-holding-usd"></i>
          </div>
          {{-- <a href="{{ route('admin.withdraw.withdrawRequests') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
        </div>
      </a>
    </div>
    <div class="col-lg-4 col-6">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $NewRank }}</h3>
          <p>@lang('admin.dashboard.new_rank_month')</p>
        </div>
        <div class="icon">
          <i class="fas fa-hand-holding-usd"></i>
        </div>
        {{-- <a href="{{ route('admin.withdraw.withdrawRequests') }}" class="small-box-footer">@lang('admin.btn.moreinfo')<i
            class="fas fa-arrow-circle-right"></i></a> --}}
      </div>
    </div>

  </div>

  <div class="card">
    <div id="pop-div" class="geochart"></div>
    <?= $lava->render('GeoChart', 'Popularity', 'pop-div') ?>
  </div>

  <section class="content">

    <div class="card">
      <div class="card-header">
        <div class="alignPackage">
          <h3>@lang('admin.dashboard.purchased.title')</h3>
        </div>
      </div>
      <div class="card-body table-responsive">
        <div class="form-group float-right">
          <input type="text" class="search form-control" placeholder="Search">
        </div>
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results">
          <thead>
            <tr>
              <th>#</th>
              <th>@lang('admin.dashboard.purchased.col1')</th>
              <th>@lang('admin.dashboard.purchased.col2')</th>
              <th>@lang('admin.dashboard.purchased.col3')</th>
              <th>@lang('admin.dashboard.purchased.col4')</th>
              <th>@lang('admin.dashboard.purchased.col5')</th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> @lang('admin.btn.noresults')</td>
            </tr>
          </thead>
          <tbody>
            @forelse($orderpackages as $orderpackage)
              <tr>
                <th>{{ $orderpackage->id }}</th>
                <td>{{ $orderpackage->user->name }}</td>
                <td>{{ $orderpackage->package_name }}</td>
                <td>{{ number_format($orderpackage->price, 2, ',', '.') }}</td>
                <td>{{ date('d/m/Y h:i:s', strtotime($orderpackage->created_at)) }}</td>
                <td>
                  @if ($orderpackage->status == 2)
                    <button class="btn btn-success btn-sm m-0">@lang('admin.btn.canceled')</button>
                  @elseif($orderpackage->status == 1)
                    <button class="btn btn-warning btn-sm m-0">@lang('admin.btn.paid')</button>
                  @else
                    <button class="btn btn-primary btn-sm m-0">@lang('admin.btn.pending')</button>
                  @endif
                </td>
              </tr>
            @empty
              <p>@lang('admin.dashboard.purchased.empty')</p>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div style="display: none;" class="modal_notificac">
      <div id="mov_noti" style="width: 0px; transition: 500ms;" class="x1">
        <div class="w-[100%] inline-block">
          <div style="width: 100%; display: inline-block;">
            <div class="width: 50%; display: inline-block;">
              <p class="title_notifc">@lang('admin.dashboard.purchased.empty')Today's notifications</p>
            </div>
            <div class="width: 50%; display: inline-block;">
              <p id="modal_notificac_cls" style="float: right; margin-top: 40px; font-size: 17px; cursor: pointer;">✕</p>
            </div>
          </div>
          @if ($count_support > 0 or $count_user > 0 or $count_stok > 0)
            <div style="width: 100%; display: inline-block;">
              @if ($count_support > 0)
                <a href="{{ route('admin.support') }}">
                  <div style="width: 100%;">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>{{ $count_support }}</h3>
                        <p>Support request</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                </a>
              @endif
            </div>

            <div style="width: 100%; display: inline-block;">
              @if ($count_user > 0)
                <a href="{{ route('admin.users.index') }}">
                  <div style="width: 100%;">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>{{ $count_user }}</h3>
                        <p>Registered today</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-user-check"></i>
                      </div>
                    </div>
                  </div>
                </a>
              @endif
            </div>

            <div style="width: 100%; display: inline-block;">
              @if ($count_stok > 0)
                <a href="{{ route('admin.packages.stock_admin') }}">
                  <div style="width: 100%;">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>{{ $count_stok }}</h3>
                        <p>News request</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-hand-holding-usd"></i>
                      </div>
                    </div>
                  </div>
                </a>
              @endif
            </div>
          @else
            <center>
              <p style="margin-top: 50%;">no news at the moment</p>
            </center>
          @endif
        </div>
      </div>
    </div>


  </section>


@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')

@stop

<script src="https://code.jquery.com/jquery-3.0.0.js"></script>
<script src="https://code.jquery.com/jquery-migrate-3.3.2.js"></script>
<script>
  $(document).ready(function() {
    $("#btn_notificac").click(function(event) {
      event.preventDefault();
      $(".modal_notificac").fadeIn();
      document.getElementById('mov_noti').style.width = "350px";
      document.getElementById('mov_noti').style.transition = "500ms";
      setTimeout(function() {
        document.getElementById('bloco_notifica').style.display = "block";
      }, 500);
    });
  });

  $(document).ready(function() {
    $("#modal_notificac_cls").click(function(event) {
      event.preventDefault();
      document.getElementById('mov_noti').style.transition = "500ms";
      document.getElementById('mov_noti').style.width = "0px";
      $(".modal_notificac").fadeOut();
    });
  });
</script>
