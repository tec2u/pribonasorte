@extends('layouts.header')
@section('content')
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    $(function() {

      'use strict'
      var salesChartCanvas = $('#salesChart').get(0).getContext('2d')
      var salesChartData = {
        labels: {
          !!$label!!
        },
        datasets: [{
            label: 'Balance Entries',
            backgroundColor: 'rgba(255,160,25,0.9)',
            borderColor: 'rgba(255,160,25,0.8)',
            pointRadius: false,
            pointColor: '#ffa019',
            pointStrokeColor: 'rgba(255,160,25,1)',
            pointHighlightFill: '#ffa019',
            pointHighlightStroke: 'rgba(255,160,25,1)',
            data: {
              !!$data!!
            }
          },
          {
            label: 'Balance Out',
            backgroundColor: 'rgba(210, 214, 222, 1)',
            borderColor: 'rgba(210, 214, 222, 1)',
            pointRadius: false,
            pointColor: 'rgba(210, 214, 222, 1)',
            pointStrokeColor: '#c1c7d1',
            pointHighlightFill: '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data: {
              !!$datasaida!!
            }
          }
        ]
      }
      var salesChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
          display: false
        },
        scales: {
          xAxes: [{
            gridLines: {
              display: false
            }
          }],
          yAxes: [{
            gridLines: {
              display: false
            }
          }]
        }
      }
      var salesChart = new Chart(
        salesChartCanvas, {
          type: 'line',
          data: salesChartData,
          options: salesChartOptions
        }
      )
      var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
      var pieData = {
        labels: ['Chrome', 'IE', 'FireFox', 'Safari', 'Opera', 'Navigator'],
        datasets: [{
          data: [700, 500, 400, 600, 300, 100],
          backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de']
        }]
      }
      var pieOptions = {
        legend: {
          display: false
        }
      }
      var pieChart = new Chart(pieChartCanvas, {
        type: 'doughnut',
        data: pieData,
        options: pieOptions
      })

      $('#world-map-markers').mapael({
        map: {
          name: 'usa_states',
          zoom: {
            enabled: true,
            maxLevel: 10
          }
        }
      })
    })

    $(function() {

      $('#carouselEcommerc img:eq(0)').addClass("ativo").show();
      setInterval(slide, 5000);

      function slide() {

        //Se a proxima imagem existir
        if ($('.ativo').next().length) {

          $('.ativo').removeClass("ativo").next().addClass("ativo");

        } else { //Se for a ultima img do carrosel

          $('.ativo').removeClass("ativo");
          $('#carouselEcommerc img:eq(0)').addClass("ativo");

        }

      }
    });
  </script>

  <script>
    function FunctionCopy1() {

      var copyText = document.getElementById("referral_ecomm");


      copyText.select();
      copyText.setSelectionRange(0, 99999); // For mobile devices

      navigator.clipboard.writeText(copyText.value);

      // alert("Copied the text: " + copyText.value);
    }

    function FunctionCopy2() {

      var copyText = document.getElementById("referral");


      copyText.select();
      copyText.setSelectionRange(0, 99999); // For mobile devices

      navigator.clipboard.writeText(copyText.value);

      // alert("Copied the text: " + copyText.value);
    }
    jQuery(document).ready(function($) {

      $('#checkbox').change(function() {
        setInterval(function() {
          moveRight();
        }, 3000);
      });

      var slideCount = $('#slider ul li').length;
      var slideWidth = $('#slider ul li').width();
      var slideHeight = $('#slider ul li').height();
      var sliderUlWidth = slideCount * slideWidth;

      $('#slider').css({
        width: slideWidth,
        height: slideHeight
      });

      $('#slider ul').css({
        width: sliderUlWidth,
        marginLeft: -slideWidth
      });

      $('#slider ul li:last-child').prependTo('#slider ul');

      function moveLeft() {
        $('#slider ul').animate({
          left: +slideWidth
        }, 200, function() {
          $('#slider ul li:last-child').prependTo('#slider ul');
          $('#slider ul').css('left', '');
        });
      };

      function moveRight() {
        $('#slider ul').animate({
          left: -slideWidth
        }, 200, function() {
          $('#slider ul li:first-child').appendTo('#slider ul');
          $('#slider ul').css('left', '');
        });
      };

      $('a.control_prev').click(function() {
        moveLeft();
      });

      $('a.control_next').click(function() {
        moveRight();
      });

    });
  </script>

  <style>
    .txtcolor {
      color: #f1af09;
    }

    .video {
      aspect-ratio: 16 / 9;
      width: 100%;
    }

    .img-home {
      max-width: 80%;
    }

    .box-info {
      font-size: 1rem;
      background-color: #d26075;
      background-position: center;
    }

    #slider {
      position: relative;
      overflow: hidden;
      margin: 20px auto 0 auto;
      border-radius: 4px;
    }

    #slider ul {
      position: relative;
      margin: 0;
      padding: 0;

      list-style: none;
    }

    #slider ul li {
      position: relative;
      display: block;
      float: left;
      margin: 0;
      padding: 0;
      width: 100%;
      background: #ccc;
      text-align: center;
      line-height: 300px;
    }

    a.control_prev,
    a.control_next {
      position: absolute;
      top: 40%;
      z-index: 999;
      display: block;
      padding: 4% 3%;
      width: auto;
      height: auto;
      background: #2a2a2a;
      color: #fff;
      text-decoration: none;
      font-weight: 600;
      font-size: 18px;
      opacity: 0.8;
      cursor: pointer;
    }

    a.control_prev:hover,
    a.control_next:hover {
      opacity: 1;
      -webkit-transition: all 0.2s ease;
    }

    a.control_prev {
      border-radius: 0 2px 2px 0;
    }

    a.control_next {
      right: 0;
      border-radius: 2px 0 0 2px;
    }

    .slider_option {
      position: relative;
      margin: 10px auto;
      width: 160px;
      font-size: 18px;
    }

    .img-career {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background-color: white;
    }

    .img-career img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
    }

    .box-career {
      display: flex;
      align-items: center;
      padding-left: 10px;
    }

    .box-career .info-box-text {
      font-size: 1rem;
      margin-bottom: -10px
    }

    .box-career .info-box-number {
      font-size: .7rem;
      color: #2b2b2b !important;
      font-weight: 900;
    }

    /* carousel */

    /* Slick Slider Css Ruls */

    .slick-slider {
      position: relative;
      display: block;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
      user-select: none;
      -webkit-touch-callout: none;
      -khtml-user-select: none;
      -ms-touch-action: pan-y;
      touch-action: pan-y;
      -webkit-tap-highlight-color: transparent
    }

    .slick-list {
      position: relative;
      display: block;
      overflow: hidden;
      margin: 0;
      padding: 0
    }

    .slick-list:focus {
      outline: none
    }

    .slick-list.dragging {
      cursor: hand
    }

    .slick-slider .slick-track,
    .slick-slider .slick-list {
      -webkit-transform: translate3d(0, 0, 0);
      -ms-transform: translate3d(0, 0, 0);
      transform: translate3d(0, 0, 0)
    }

    .slick-track {
      position: relative;
      top: 0;
      left: 0;
      display: block
    }

    .slick-track:before,
    .slick-track:after {
      display: table;
      content: ''
    }

    .slick-track:after {
      clear: both
    }

    .slick-loading .slick-track {
      visibility: hidden
    }

    .slick-slide {
      display: none;
      float: left;
      height: 100%;
      min-height: 1px
    }

    .slick-slide.dragging img {
      pointer-events: none
    }

    .slick-initialized .slick-slide {
      display: block
    }

    .slick-loading .slick-slide {
      visibility: hidden
    }

    .slick-vertical .slick-slide {
      display: block;
      height: auto;
      border: 1px solid transparent
    }

    .img-fill {
      width: 100%;
      display: block;
      overflow: hidden;
      position: relative;
      text-align: center
    }

    .img-fill img {
      height: 100%;
      min-width: 100%;
      position: relative;
      display: inline-block;
      width: 100%;
    }

    /* Slider Theme Style */

    .Container-carousel {
      padding: 0 15px;
    }

    .Container-carousel:after,
    .Container-carousel .Head-carousel:after {
      content: '';
      display: block;
      clear: both;
    }

    .Container-carousel .Head-carousel {
      font: 20px/50px NeoSansR;
      color: #222;
      height: 52px;
      over-flow: hidden;
      border-bottom: 1px solid rgba(0, 0, 0, .25);
    }

    .Container-carousel .Head-carousel .Arrows {
      float: right;
    }

    .Container-carousel .Head-carousel .Slick-Next,
    .Container-carousel .Head-carousel .Slick-Prev {
      display: inline-block;
      height: 38px;
      margin-top: 6px;
      background: #2b2b2b;
      color: #FFF;
      margin-left: 5px;
      cursor: pointer;
      font: 18px/36px FontAwesome;
      text-align: center;
      transition: all 0.5s;
    }

    .Container-carousel .Head-carousel .Slick-Next:hover,
    .Container-carousel .Head-carousel .Slick-Prev:hover {
      background: #470D53;
    }

    .Slick-Next,
    .Slick-Prev {
      width: fit-content;
      padding: 5px 10px;
    }


    .SlickCarousel {
      margin: 0 -7.5px;
      margin-top: 10px;
    }

    .ProductBlock {
      padding: 0 7.5px;
      width: 250px;
    }

    .ProductBlock .img-fill {
      height: 250px;
      width: 100%;
    }

    .ProductBlock h3 {
      font: 15px/36px RalewayR;
      color: #393939;
      margin-top: 5px;
      text-align: center;
      border: 1px solid rgba(0, 0, 0, .25);
    }

    *,
    *:before,
    *:after {
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.04);
    }

    .image-card-random {
        display: block;
        width: 100%;
        height: 250px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }

    .card-product-random {
        height: 360px;
    }

    @media (min-width: 800px) {
      .geochart {
        width: 17px !important;
      }
    }

    /*  */
  </style>
  @php

    $user_id = ucwords(auth()->user()->id);

    $diretos_qr = Illuminate\Support\Facades\DB::select(
        "SELECT count(distinct(user_id_from)) as total FROM historic_score where user_id=$user_id and level_from=1;",
    );
    $diretos = isset($diretos_qr[0]->{'total'}) ? $diretos_qr[0]->{'total'} : 0;

    $indiretos_qr = Illuminate\Support\Facades\DB::select(
        "SELECT count(distinct(user_id_from)) as total FROM historic_score where user_id=$user_id and level_from>1;",
    );
    $indiretos = isset($indiretos_qr[0]->{'total'}) ? $indiretos_qr[0]->{'total'} : 0;

    $totalMembros = $diretos + $indiretos;

    $directVolume = Illuminate\Support\Facades\DB::select(
        "SELECT sum(score) as total FROM historic_score where user_id=$user_id and level_from=1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')",
    );
    $directVolume = isset($directVolume[0]->{'total'}) ? $directVolume[0]->{'total'} : 0;

    $indirectVolume = Illuminate\Support\Facades\DB::select(
        "SELECT sum(score) as total FROM historic_score where user_id=$user_id and level_from>1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')",
    );
    $indirectVolume = isset($indirectVolume[0]->{'total'}) ? $indirectVolume[0]->{'total'} : 0;

    $totalVolume = $indirectVolume + $directVolume;

    $directVolumelast = Illuminate\Support\Facades\DB::select(
        "SELECT sum(score) as total FROM historic_score where user_id=$user_id and level_from=1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')",
    );
    $directVolumelast = isset($directVolumelast[0]->{'total'}) ? $directVolumelast[0]->{'total'} : 0;

    $indirectVolumelast = Illuminate\Support\Facades\DB::select(
        "SELECT sum(score) as total FROM historic_score where user_id=$user_id and level_from>1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')",
    );
    $indirectVolumelast = isset($indirectVolumelast[0]->{'total'}) ? $indirectVolumelast[0]->{'total'} : 0;

    // dd($directVolumelast, $indirectVolumelast);
    // exit();

    $totalVolumelast = $indirectVolumelast + $directVolumelast;

    $personalVolume = Illuminate\Support\Facades\DB::select(
        "SELECT sum(score) as total FROM historic_score where user_id=$user_id and level_from=0 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')",
    );
    $personalVolume = isset($personalVolume[0]->{'total'}) ? $personalVolume[0]->{'total'} : 0;

    $directCustomers = Illuminate\Support\Facades\DB::select(
        "SELECT id FROM ecomm_registers WHERE recommendation_user_id = $user_id",
    );
    $personalVolumeEcomm = 0;

    if (count($directCustomers) > 0) {
        foreach ($directCustomers as $value) {
            $idEcomm = $value->id;
            $qvEcomm = Illuminate\Support\Facades\DB::select(
                "SELECT SUM(qv) AS total FROM ecomm_orders WHERE id_user=$idEcomm AND client_backoffice = 0 AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') AND number_order IN (SELECT number_order FROM payments_order_ecomms WHERE status = 'paid')",
            );
            $personalVolumeEcomm += isset($qvEcomm[0]->{'total'}) ? $qvEcomm[0]->{'total'} : 0;
        }
    }

    // dd($personalVolumeEcomm);
    // exit();

    $personalVolume = $personalVolume + $personalVolumeEcomm;

    $personalVolumelast = Illuminate\Support\Facades\DB::select(
        "SELECT sum(score) as total FROM historic_score where user_id=$user_id and level_from=0 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m')",
    );
    $personalVolumelast = isset($personalVolumelast[0]->{'total'}) ? $personalVolumelast[0]->{'total'} : 0;

    $personalVolumelastEcomm = 0;

    if (count($directCustomers) > 0) {
        foreach ($directCustomers as $value) {
            $idEcomm = $value->id;
            $qvEcomm = Illuminate\Support\Facades\DB::select(
                "SELECT SUM(qv) AS total FROM ecomm_orders WHERE id_user=$idEcomm AND client_backoffice = 0 AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(NOW() - INTERVAL 1 MONTH, '%Y-%m') AND number_order IN (SELECT number_order FROM payments_order_ecomms WHERE status = 'paid')",
            );
            $personalVolumelastEcomm += isset($qvEcomm[0]->{'total'}) ? $qvEcomm[0]->{'total'} : 0;
        }
    }

    $personalVolumelast = $personalVolumelast + $personalVolumelastEcomm;

    // $availableComission = Illuminate\Support\Facades\DB::select("select sum(price) from banco where user_id=$user_id");
    // $availableComission = isset($availableComission[0]->{'sum(price)'}) ? $availableComission[0]->{'sum(price)'} : 0;

    use App\Models\User;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;
    use App\Models\Product;
    use Illuminate\Support\Facades\Auth;

    $products = Product::get();
    $currentDate = Carbon::now();
    $dayThreshold = 15;
    $cardAvailable = false;
    $subMonth = $currentDate->subMonth()->month;
    $currentYear = $currentDate->year;
    $currentMonth = $currentDate->month;

    if ($subMonth >= 2) {
        $subMonth = $subMonth - 1;
        $currentYear = $currentYear;
    } else {
        $subMonth = 12;
        $currentYear = $currentYear - 1;
    }

    if ($subMonth < 9) {
        $subMonth = '0' . $subMonth;
    } else {
        $subMonth = $subMonth;
    }

    $farst_day = date('t', mktime(0, 0, 0, $subMonth, '01', $currentYear));

    $dateComplete = $currentYear . '-' . $subMonth . '-' . $farst_day . ' 23:59:59';
    $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

    if ($currentDate->day >= $dayThreshold) {
        $farst_day = Carbon::create($currentYear, $currentMonth)->endOfMonth()->day;
        $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

        $cardAvailable = true;

        $availableComission = DB::table('banco')
            ->where('user_id', User::find(Auth::id())->id)
            ->where('price', '>', 0)
            ->where('created_at', '<=', $dateComplete2)
            ->sum('price');
    } else {
        $availableComission = DB::table('banco')
            ->where('user_id', User::find(Auth::id())->id)
            ->where('price', '>', 0)
            ->where('created_at', '<=', $dateComplete)
            ->sum('price');
    }

    // dd('Se for 16 ou mais ' . $dateComplete2, 'Menor que 16 ' . $dateComplete);

    $retiradasTotais = DB::table('banco')
        ->where('user_id', User::find(Auth::id())->id)
        ->where('price', '<', 0) // Considera apenas valores negativos
        ->sum('price');

    $retiradasTotais = -$retiradasTotais;

    if ($retiradasTotais >= $availableComission) {
        $availableComission = 0;
    } else {
        $availableComission = $availableComission - $retiradasTotais;
    }

    // $totalComission = Illuminate\Support\Facades\DB::select("SELECT sum(price) FROM banco WHERE user_id = $user_id AND price > 0");
    // $totalComission = Illuminate\Support\Facades\DB::select("SELECT sum(price) FROM banco WHERE user_id = $user_id AND price > 0 AND created_at <= '$dateComplete2'");
    // $totalComission = isset($totalComission[0]->{'sum(price)'}) ? $totalComission[0]->{'sum(price)'} : 0;

    $totalComission = DB::table('banco')
        ->where('user_id', User::find(Auth::id())->id)
        ->where('price', '>', 0)
        ->where('created_at', '<=', $dateComplete2)
        ->sum('price');

    $active = Illuminate\Support\Facades\DB::select(
        "SELECT SUM(total_price) AS total FROM payments_order_ecomms WHERE DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') AND UPPER(status) = UPPER('paid') AND id_user = $user_id",
    );

    $active = $active[0]->{'total'};

    // if ($active >= 100) {
    //     $active = 'Active';
    // } else {
    //     $active = 'Inactive';
    // }

    $firstDayOfMonth = now()->firstOfMonth()->toDateString();
    $lastDayOfMonth = now()->lastOfMonth()->toDateString();

    $data_final = date('Y-m');

    $id_greatest_career = Illuminate\Support\Facades\DB::select(
        "SELECT career_id,created_at FROM career_users where user_id=$user_id order by career_id desc limit 1",
    );

    if (count($id_greatest_career) > 0) {
        $id_greatest_career = $id_greatest_career[0]->career_id;
        $greatest_career_user = Illuminate\Support\Facades\DB::select(
            "SELECT * FROM career WHERE id = '$id_greatest_career' ORDER BY id DESC LIMIT 1",
        )[0];

        // carreira atual

        $id_career_user = Illuminate\Support\Facades\DB::select(
            "SELECT career_id FROM career_users WHERE user_id = '$user_id' AND created_at like'%$data_final%' ORDER BY career_id DESC LIMIT 1",
        );

        if (count($id_career_user) > 0) {
            $id_career_user = $id_career_user[0]->career_id;
            $career_user = Illuminate\Support\Facades\DB::select(
                "SELECT * FROM career WHERE id = '$id_career_user' ORDER BY id DESC LIMIT 1",
            )[0];
        } else {
            $career_user = '';
        }
    } else {
        $greatest_career_user = '';
        $career_user = '';
    }

    $nomeMesAtual = Carbon::now()->monthName;
    $mesAnterior = Carbon::now()->subMonth()->monthName;
    $mesAntesDoAnterior = Carbon::now()->subMonths(2)->monthName;

  @endphp
  <script>
    $(function() {
        $('#carouselEcommerc img:eq(0)').addClass("ativo").show();
        setInterval(slide, 5000);

        function slide() {

            //Se a proxima imagem existir
            if ($('.ativo').next().length) {

                $('.ativo').removeClass("ativo").next().addClass("ativo");

            } else { //Se for a ultima img do carrosel

                $('.ativo').removeClass("ativo");
                $('#carouselEcommerc img:eq(0)').addClass("ativo");

            }

        }
    });
  </script>
  <main id="main" class="main mt-0">
    {{-- @include('flash::message') --}}
    <section id="home" class="content">
      <div class="fade">
        <div class="container-fluid">
        <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                @foreach($products as $key => $product)
                    <div class="carousel-item {{ $key==0 ? 'active' : '' }}">
                        <div class="container-fluid bg-white p-0 radius-15 section-banner"
                            style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); position: relative; background-image: url('img/products/{{ $product->img_1 }}'); height: 400px; background-size: cover; background-position: center;background-repeat: no-repeat;">
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Controles -->
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>
          <div class="row mb-3 mt-3">
            {{-- @if (auth()->user()->isAllowed()) --}}
            <div class="col-12 col-sm-12 col-md-4">
              <a href="{{ route('reports.bonus_group') }}">
                <div class="info-box mb-4 shadow c1 box-info">
                  <span class="info-box-icon"><i class="bi bi-arrow-down-up"></i></span>
                  <div class="info-box-content" style="color: #ffff;">
                    <span class="info-box-text">
                      @lang('home.total_commission')
                      {{-- Total Commissions --}}
                    </span>
                    <span class="info-box-number">{{ number_format($totalComission, 2, ',', '.') }} R$ </span>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-12 col-md-4">
              <div class="info-box mb-4 shadow c1 box-info">
                <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                <div class="info-box-content" style="color: #ffff;">
                  <span class="info-box-text">
                    @lang('home.available_comission')
                  </span>
                  <div style="font-size: .8rem">
                    @if ($cardAvailable)
                      <span class="info-box-number" style="color: green">@lang('home.available') - {{ $mesAnterior }}</span>
                      <span class="info-box-number" style="color: red">@lang('home.not_available') - {{ $nomeMesAtual }}</span>
                    @else
                      <span class="info-box-number" style="color: green">@lang('home.available') -
                        {{ $mesAntesDoAnterior }}</span>
                      <span class="info-box-number" style="color: red">@lang('home.not_available') - {{ $nomeMesAtual }},
                        {{ $mesAnterior }}</span>
                    @endif
                  </div>
                  <span class="info-box-number">{{ number_format($availableComission, 2, ',', '.') }} R$ </span>
                </div>

              </div>
            </div>


            {{-- <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-4 shadow elevation c1 box-info">
                <span class="info-box-icon "><i class="bi bi-credit-card-2-back-fill"></i></span>
                <div class="info-box-content" style="color: #ffff;">
                  <span class="info-box-text">YOUR STATUS</span>
                  <span class="info-box-number">{{ $active }}</span>
                </div>
              </div>
            </div> --}}

            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-4 shadow elevation c1 box-info">
                <span class="info-box-icon" style="margin-top: -15px;"><i class="bi bi-person-plus-fill"></i></span>
                <div class="info-box-content" style="color: #ffff;">
                  <span class="info-box-text">@lang('home.indirect_distributors')</span>
                  <span class="info-box-number">{{ $indiretos }}</span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-4 shadow elevation c1 box-info">
                <span class="info-box-icon" style="margin-top: -15px;"><i class="bi bi-reception-4"></i></span>
                <div class="info-box-content" style="color: #ffff;">
                  <span class="info-box-text">@lang('home.total_distributors')</span>
                  <span class="info-box-number">{{ $totalMembros }}</span>
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4">
              <div class="info-box mb-4 shadow elevation c1 box-info">
                <span class="info-box-icon" style="margin-top: -15px;"><i class="bi bi-person-plus-fill"></i></span>
                <div class="info-box-content" style="color: #ffff;">
                  <span class="info-box-text">@lang('home.direct_distributors')</span>
                  <span class="info-box-number">{{ $diretos }}</span>
                </div>
              </div>
            </div>

            {{-- <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-4 shadow elevation c1 box-info">
                <span class="info-box-icon "><i class="bi bi-display"></i></span>
                <div class="info-box-content" style="color: #ffff;">
                  <span class="info-box-text">CAREER PLAN</span>
                  <span class="info-box-number">Active</span>
                </div>
              </div>
            </div> --}}

            {{-- <div class="col-12 col-sm-6 col-md-3">
                                <div class="info-box mb-4 shadow elevation c1"
                                    style="background-image: linear-gradient(to right, #c18c07, #f1af09, #f6f6f6);">
                                    <span class="info-box-icon "><i class="bi bi-person-fill"></i></span>
                                    <div class="info-box-content" style="color: #ffff;">
                                        <span class="info-box-text">@lang('home.user_details')</span>
                                        @if ($user->getTypeActivated($user->id) == 'AllCards')
                                            <span class="info-box-number">@lang('network.active')</span>
                                        @elseif ($user->getTypeActivated($user->id) == 'PreRegistration')
                                            <span class="info-box-number">@lang('network.PreRegistration')</span>
                                        @else
                                            <span class="info-box-number">@lang('network.inactive')</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif --}}


            {{-- <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-4 shadow c1 btcolor3">
                            <div class="info-box-content">
                                <span class="info-box-text">@lang('home.current_package')</span>
                                <span class="info-box-number">{{$name}}</span>
                </div>
            </div>
        </div> --}}

            <!-- <div class="clearfix hidden-md-up"></div> -->
            {{-- <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box mb-4 shadow c1 btcolor1">
                            <div class="info-box-content col-8">
                                <span class="info-box-text">@lang('home.current_rank')</span>
                                <span class="info-box-number">{{$carrer->name}}</span>
        </div>
        <div class="info-box-content col-4">
            aa<img src="/images/Badges/{{$carrer->id}}.png" alt="pin" class="rounded-circle">
        </div>
        </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-4 shadow c1 btcolor2">
                <div class="info-box-content">
                    <span class="info-box-text">@lang('home.membership_status')</span>
                    @if ($user->activated == 1)
                    <span class="info-box-number">@lang('home.active')</span>
                    @else
                    <span class="info-box-number">@lang('home.disabled')</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-4 shadow c1 btcolor4">
                <div class="info-box-content">
                    <span class="info-box-text">@lang('home.inactive_users')</span>
                    @if ($inactiverights > 0)
                    <span class="info-box-number text-danger">{{$inactiverights}}</span>
                    @else
                    <span class="info-box-number">{{$inactiverights}}</span>
                    @endif
                </div>
            </div>
        </div>
        </div> --}}

            <!-- <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-4 shadow elevation c1 box-info box-career">
                <div class="img-career">
                  <img src="/images/Badges/{{ $greatest_career_user->id }}.png"
                    alt="{{ $greatest_career_user->name }}">
                </div>
                <div class="info-box-content" style="color: #000;">
                  <span class="info-box-number">@lang('home.greatest_career_acheived')</span>
                  @if (isset($greatest_career_user->name))
                    <span class="info-box-text">{{ $greatest_career_user->name }}</span>
                    @if ($greatest_career_user->created_at)
                      <span
                        class="info-box-number">{{ date('Y-m-d', strtotime($greatest_career_user->created_at)) }}</span>
                    @endif
                  @else
                    <span class="info-box-text">{{ $greatest_career_user }}</span>
                  @endif
                </div>
              </div>
            </div> -->

            <!-- <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-4 shadow elevation c1 box-info box-career">

                <div class="img-career">
                  @if (isset($career_user->id))
                    <img src="/images/Badges/{{ $career_user->id }}.png" alt="{{ $career_user->name }}">
                </div>
                <div class="info-box-content" style="color: #000;">
                  <span class="info-box-number">@lang('home.career_performance')</span>
                  @if (isset($career_user->name))
                    <span class="info-box-text">{{ $career_user->name }}</span>
                    @if ($career_user->created_at)
                      <span class="info-box-number">{{ date('Y-m-d', strtotime($career_user->created_at)) }}</span>
                    @endif
                  @else
                    <span class="info-box-text">{{ $greatest_career_user->name }}</span>
                  @endif
                </div>
                <div class="d-flex">
                  <div role="progressbar" aria-valuenow="{{ $percentProgress }}" aria-valuemin="0" max="100"
                    aria-valuemax="{{ 100 }}" style="--value:{{ $percentProgress }}">
                  </div>

                  <div
                    style="display: flex;flex-direction:column; justify-content: center; font-weight: 700; margin-left:1rem;text-align: center">
                    <span>{{ $soma }}</span>
                    <span>----</span>
                    <span></span>
                  </div>
                </div>
              @else
                <img src="/images/nolimitslogo.png" alt="">
              </div>
              @endif


            </div> -->
          </div>

          <div class="card p-3">
            <div class="d-flex flex-wrap">
                @forelse($products as $product)
                <div class="col-sm-3 hover">
                    <div class="card-product-random card p-3">
                        <div>
                            <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}" class="image-card-random" style="background-image: url('/img/products/{{ $product->img_1 }}')"></a>
                        </div>

                        @php
                        $new_price = $product->backoffice_price;
                        @endphp

                        <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">
                            <h5 class="tittle-name">{{ $product->name }}</h5>
                        </a>
                        <h6 class="text-price">R$  {{ $new_price }}</h6>


                        <div class="container-description">
                            <h6 class="text-description">
                                <a href="{{ route('packages.detail_products', ['id' => $product->id]) }}">Ver mais...</a>
                            </h6>
                        </div>
                    </div>
                </div>

                @empty
                <p>@lang('package.any_products_registered')</p>
                @endforelse

            </div>
          </div>

          {{-- <div class="col-12">
              <div class="info-box mb-4 shadow c1">
                <span class="info-box-icon"><i class="bi bi-send-fill"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">@lang('home.landing_page_link')</span>
                  <div class="row">
                    <div class="col-10">
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" id="landing"
                          value="https://landingpage.Pribonasorte.eu/{{ auth()->user()->login }}">
                        <button class=" btn btn-dark orderbtn linkcopy px-4" type="button"
                          onclick="FunctionCopy1()">Copy</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> --}}

          <div class="col-12">
            <div class="info-box mb-4 shadow c1">
              <span class="info-box-icon"><i class="bi bi-star-fill"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">@lang('home.registration_link')</span>
                <div class="row">
                  <div class="col-10">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" id="referral"
                        value="{{ route('indication.register', auth()->user()->id) }}">
                      <button class=" btn btn-dark orderbtn linkcopy px-4" type="button"
                        onclick=" FunctionCopy2()">Copy</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="info-box mb-4 shadow c1">
              <span class="info-box-icon"><i class="bi bi-star-fill"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">@lang('home.registration_link_customers')</span>
                <div class="row">
                  <div class="col-10">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" id="referral_ecomm"
                        value="{{ route('register.user.ecomm.referral', auth()->user()->id) }}">
                      <button class=" btn btn-dark orderbtn linkcopy px-4" type="button"
                        onclick=" FunctionCopy1()">Copy</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <script>
            $(document).ready(function() {
              $(".SlickCarousel").slick({
                rtl: false, // If RTL Make it true & .slick-slide{float:right;}
                autoplay: true,
                autoplaySpeed: 5000, //  Slide Delay
                speed: 800, // Transition Speed
                slidesToShow: 3, // Number Of Carousel
                slidesToScroll: 1, // Slide To Move
                pauseOnHover: false,
                appendArrows: $(".Container-carousel .Head-carousel .Arrows"), // Class For Arrows Buttons
                prevArrow: '<span class="Slick-Prev">Prev</span>',
                nextArrow: '<span class="Slick-Next">Next</span>',
                easing: "linear",
                responsive: [{
                    breakpoint: 801,
                    settings: {
                      slidesToShow: 3,
                    }
                  },
                  {
                    breakpoint: 641,
                    settings: {
                      slidesToShow: 2,
                    }
                  },
                  {
                    breakpoint: 481,
                    settings: {
                      slidesToShow: 1,
                    }
                  },
                ],
              })
            })
          </script>

    </section>
  </main>
  {{-- @if (isset($popup->activated) and $popup->activated == 1)
        <script>
            if (screen.width > 810) {
                var widthImage = 810;
                var heightImage = widthImage / 1.787;
            } else {
                var widthImage = screen.width;
                var heightImage = screen.width / 1.787;
            }
            setTimeout(() => {
                Swal.fire({
                    "title": "",
                    "text": "",
                    "width": widthImage,
                    "heightAuto": true,
                    "padding": "1.25rem",
                    "showConfirmButton": true,
                    "showCloseButton": false,
                    "timerProgressBar": false,
                    "customClass": {
                        "container": null,
                        "popup": null,
                        "header": null,
                        "title": null,
                        "closeButton": null,
                        "icon": null,
                        "image": null,
                        "content": null,
                        "input": null,
                        "actions": null,
                        "confirmButton": null,
                        "cancelButton": null,
                        "footer": null
                    },
                    "imageUrl": "{{ asset('storage/' . $popup->path) }}",
                    "imageWidth": widthImage,
                    "imageHeight": heightImage,
                    "imageAlt": "",
                    "animation": false
                });
            }, 7000);
        </script>
    @else
    @endif --}}

  <style>
    @keyframes growProgressBar {

      0%,
      33% {
        --pgPercentage: 0;
      }

      100% {
        --pgPercentage: var(--value);
      }
    }

    @property --pgPercentage {
      syntax: '<number>';
      inherits: false;
      initial-value: 0;
    }

    div[role="progressbar"] {
      --size: 6rem;
      --fg: #480C54;
      --bg: transparent;
      --pgPercentage: var(--value);
      animation: growProgressBar 3s 1 forwards;
      width: var(--size);
      height: var(--size);
      border-radius: 50%;
      display: grid;
      place-items: center;
      background:
        radial-gradient(closest-side, white 80%, transparent 0 99.9%, white 0),
        conic-gradient(var(--fg) calc(var(--pgPercentage) * 1%), var(--bg) 0);
      font-family: Helvetica, Arial, sans-serif;
      font-size: calc(var(--size) / 5);
      color: var(--fg);
    }

    div[role="progressbar"]::before {
      counter-reset: percentage var(--value);
      content: counter(percentage) '%';
    }
  </style>
@endsection
