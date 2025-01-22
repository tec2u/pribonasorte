<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="font-family: Poppins, sans-serif;">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pri Bonasorte</title>
    <link rel="icon" type="image/png" href="/images/favicon.png" />

    <!-- Fonts -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://maxcdn.icons8.com/fonts/line-awesome/1.1/css/line-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.7/flipclock.css">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flipclock/0.7.7/flipclock.js"></script>
    <script type="text/javascript" src="https://www.ppl.cz/sources/map/main.js" async></script>
    <link rel="stylesheet" href="https://www.ppl.cz/sources/map/main.css">

    <style>
        .profile-aside {
            display: flex;
            align-items: center;
            flex-direction: column;
            justify-content: center;
            gap: .4rem;
            padding-bottom: 1rem;
        }

        .profile-aside img {
            border-radius: 50%;
            width: 75px;
            height: 75px;
            background-size: cover;
        }

        .header-nav .nav-profile img {
            width: 36px !important;
            height: 36px !important;
            border-radius: 50%;
            background-size: cover;
        }

        .banner_inactive {
            position: fixed;
            width: 100%;
            height: 30px;
            z-index: 997;
            align-items: center;
            display: flex;
            justify-content: center;
            font-size: 17px;
        }
    </style>

</head>

<body>
    @php

    if (Session::has('redirect_buy')) {
    Session::forget('redirect_buy');
    }

    $user_id = ucwords(auth()->user()->id);
    $corporate_activated = auth()->user()->activated_corporate;
    $id_corporate = auth()->user()->id_corporate;

    $query_cart = Illuminate\Support\Facades\DB::select("SELECT * FROM cart_orders WHERE id_user = $user_id");
    $count_cart = count($query_cart);

    $orderPackages = Illuminate\Support\Facades\DB::select(
    "SELECT * FROM orders_package WHERE user_id = '$user_id' AND payment_status = 1 AND status = 1",
    );
    $countPackage = count($orderPackages);

    $firstDayOfMonth = now()->firstOfMonth()->toDateString();
    $lastDayOfMonth = now()->lastOfMonth()->toDateString();

    $id_career_user = Illuminate\Support\Facades\DB::select(
    "SELECT career_id FROM career_users WHERE user_id = '$user_id' AND created_at >= '$firstDayOfMonth' AND created_at <= '$lastDayOfMonth' ORDER BY created_at DESC LIMIT 1",
        );

        if (count($id_career_user)> 0) {
        $id_career_user = $id_career_user[0]->career_id;
        $career_user = Illuminate\Support\Facades\DB::select(
        "SELECT name FROM career WHERE id = '$id_career_user' ORDER BY id DESC LIMIT 1",
        )[0]->name;
        } else {
        $career_user = '';
        }

        $OpenMenu = \App\Models\OrderPackage::where('user_id', $user_id)
        ->where('payment_status', 1)
        ->where('status', 1)
        ->get();

        $OpenMenuPackage = \App\Models\OrderPackage::where('user_id', $user_id)
        ->where('payment_status', 1)
        ->where('status', 1)
        ->orderBy('id', 'desc')
        ->first();

        $diasFaltantes = null;

        if (isset($OpenMenuPackage)) {
        $dataAtual = \Illuminate\Support\Carbon::now();
        $dataAtualizacao = $OpenMenuPackage->updated_at;

        $dataUmMesDepois = $dataAtualizacao->addYear();

        $diasFaltantes = $dataAtual->diffInDays($dataUmMesDepois);
        // dd($diasFaltantes);
        }

        // $libre_menu = Illuminate\Support\Facades\DB::select("SELECT * FROM orders_package WHERE user_id = '$user_id' AND payment_status = 1 AND status = 1");
        if (isset($OpenMenu)) {
        $OpenMenu = count($OpenMenu);
        } else {
        $OpenMenu = 0;
        }

        // $categorias = \App\Models\Categoria::get();

        @endphp
        <!-- ======= Header ======= -->
        {{-- @include('sweetalert::alert') --}}
        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <i class="bi bi-list toggle-sidebar-btn"></i>
            </div><!-- End Logo -->
            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">
                    @if (isset($diasFaltantes))
                    <li class=" pe-4">
                        <h6 class="text-dark-50 joinhead" style="font-size: 1rem;"><strong
                                style="color: #d26075;">{{ $diasFaltantes }}</strong> @lang('home.days_expiration')</h6>
                    </li>
                    @endif

                    <li class="pe-4">
                        <a href="https://t.me/+4pRjuBp4Pw1kYjY0" class=""><i class="lab la-telegram iconhead"></i></a>
                    </li>

                    <li class="nav-item dropdown pe-3">
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-lang " type="button" data-bs-toggle="dropdown"
                                data-bs-auto-close="true" aria-expanded="false">
                                @lang('header.language')
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="/setlocale/en"><img src="../assetsWelcome/images/flaguk.png"
                                            style="width: 18px;margin-right:10px" alt="...">@lang('header.english')</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/setlocale/pt"><img src="../assetsWelcome/images/flagbr.png"
                                            style="width: 18px;margin-right:10px" alt="...">@lang('header.portuguese')</a>
                                </li>
                                {{-- <li>
                <a class="dropdown-item" href="/setlocale/es"><img src="../assetsWelcome/images/flagspa.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.spanish')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/de"><img src="../assetsWelcome/images/flagger.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.german')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/po"><img src="../assetsWelcome/images/flagpo.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.polish')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/sl"><img src="../assetsWelcome/images/flagsl.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.slovak')</a>
              </li> --}}
                                {{-- <li>
                <a class="dropdown-item" href="/setlocale/fr"><img src="../assetsWelcome/images/flagfr.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.french')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/ar"><img src="../assetsWelcome/images/flagar.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.arabic')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/in"><img src="../assetsWelcome/images/flagin.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.hindi')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/ru"><img src="../../assetsWelcome/images/flagru.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.russian')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/tr"><img src="../../assetsWelcome/images/flagtr.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.turkiye')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/nl"><img src="../../assetsWelcome/images/flagnl.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.netherlands')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/it"><img src="../../assetsWelcome/images/flagit.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.italy')</a>
              </li> --}}
                            </ul>
                        </div>
                    </li>
                    @if ($OpenMenu > 0 || $user_id == 1 || (auth()->user()->activated == 1 && isset($id_corporate)))
                    <a href="{{ route('packages.cart_buy') }}" style="font-size: 25px; margin: 0px 40px 0px 0px;">
                        <ul>
                            <li style="display: inline-block;"><i class="bi bi-cart3"></i></li>
                            <li style="display: inline-block;">
                                <p class="position-absolute"
                                    style="font-size: 15px; padding: 0px 10px; margin: -20px 0px 0px 0px; border-radius: 5px; background: #d26075; color: #ffffff;">
                                    {{ $count_cart }}
                                </p>
                            </li>
                        </ul>
                    </a>
                    @endif
                    <li class="nav-item dropdown pe-3">

                        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">

                            @if (!empty(auth()->user()->image_path))
                            <img src="{{ asset(auth()->user()->image_path) }}" alt="Profile" class="rounded-circle">
                            @else
                            <img src="../../../assetsWelcome/images/favicon.png" alt="Profile" class="rounded-circle">
                            @endif

                            <span class="d-none d-md-block dropdown-toggle ps-2">{{ ucwords(auth()->user()->name) }}</span>
                        </a><!-- End Profile Iamge Icon -->

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                            <li class="dropdown-header">
                                <h6>{{ ucwords(auth()->user()->name) }}</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item d-flex align-items-center alog" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                     document.getElementById('logout-form').submit();">
                                    <i class="bi bi-box-arrow-right iconlog"></i>
                                    <span>@lang('header.sign_out')</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul><!-- End Profile Dropdown Items -->
                    </li><!-- End Profile Nav -->
                </ul>
            </nav><!-- End Icons Navigation -->

        </header><!-- End Header -->
        @if ($corporate_activated == 0 && $id_corporate)
        <div class="banner_inactive bg-warning">Some menu options are hidden, your account is pending activation by the
            administrator.</div>
        @endif
        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">
            <a href="{{ route('home.home') }}">
                <img class="imagetest2" src="{{ asset('/img/logo-2-gradient.png') }}" style="width: 200px;" alt="">
            </a>
            <div class="profile-aside">
                @if (!empty(auth()->user()->image_path))
                <img src="{{ asset(auth()->user()->image_path) }}" alt="Profile" class="rounded-circle">
                @else
                <img src="../../../assetsWelcome/images/favicon.png" alt="Profile" class="rounded-circle">
                @endif
                <h5>{{ auth()->user()->login }}</h5>
                <span>{{ $career_user }}</span>
            </div>
            <ul class="sidebar-nav" id="sidebar-nav">
                @if (true)
                <li class="nav-item">
                    <a class="nav-link " href="{{ route('home.home') }}">
                        <i class="bi bi-grid"></i>
                        <span>@lang('header.dashboard')</span>
                    </a>
                </li><!-- End Dashboard Nav -->
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#minting-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-clipboard2-minus"></i><span>@lang('header.purchase')</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="minting-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        @if (true)

                        <li>
                            <a href="{{ route('packages.index_products') }}">
                                <i class="bi bi-circle"></i><span>@lang('header.products')</span>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#minting-nav-categories" data-bs-toggle="collapse">
                  <i class="bi bi-clipboard2-minus"></i><span>@lang('header.products')</span><i
                    class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="minting-nav-categories" class="nav-content collapse "
                  data-bs-parent="#minting-nav-categories" style="font-size: .8rem;padding-left:1.5rem">

                  <li>
                    <a href="{{ route('packages.index_products') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.products')</span>
                        </a>
                </li>

                @foreach ($categorias as $cat)
                <li>
                    <a href="{{ route('packages.index_categoria', $cat->id) }}">
                        <i class="bi bi-circle"></i><span
                            style="text-transform: uppercase">{{ $cat->nome }}</span>
                    </a>
                </li>
                @endforeach

            </ul>
            </li> --}}
            <!-- End Components Nav -->
            {{-- @endif --}}

            {{-- @endif --}}
            @endif
            {{-- <li>
              <a href="https://infinityclubcardmembers.com">
                  <i class="bi bi-circle"></i><span>@lang('header.club')</span>
              </a>
          </li> --}}
            @if (true)
            <li>
                <a href="{{ route('packages.packagelog') }}">
                    <i class="bi bi-circle"></i><span>@lang('header.order_history')</span>
                </a>
            </li>
            @endif
            </ul>
            </li>

            <!-- End Components Nav -->

            {{-- @if (auth()->user()->payFirstOrder()) --}}
            @if (true)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#products-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-wallet2"></i><span>@lang('header.withdraw')</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="products-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('withdraws.withdrawRequests') }}">
                            <i class="bi bi-circle"></i><span>@lang('header.withdrawal_request')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('withdraws.withdrawLog') }}">
                            <i class="bi bi-circle"></i><span>@lang('header.withdrawal_history')</span>
                        </a>
                    </li>
                    {{-- <li>
            <a href="{{ route('withdraws.withdrawBonus') }}">
                    <i class="bi bi-circle"></i><span>@lang('header.withdraw_bonus')</span>
                    </a>
            </li> --}}
            </ul>
            </li>
            @endif
            {{-- <!-- End Products Nav --> --}}

            @if (true)
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#networks-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-people"></i><span>@lang('header.networks')</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
            <ul id="networks-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('networks.mytree', ['parameter' => auth()->user()->id]) }}">
                            <i class="bi bi-circle"></i><span>@lang('header.my_tree')</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('networks.associatesReport') }}">
                            <i class="bi bi-circle"></i><span>@lang('header.associates')</span>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            @endif

            @if (true)


            <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#media-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-gear"></i><span>Media</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="media-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('videos.index') }}">
              <i class="bi bi-circle"></i><span>Videos</span>
            </a>
          </li>

          <li>
            <a href="{{ route('documents.index') }}">
              <i class="bi bi-circle"></i><span>Documents</span>
            </a>
          </li>
        </ul>
      </li>
            <li class="nav-item">
                <a class="nav-link " href="{{ route('supports.supporttickets') }}">
                    <i class="bi bi-question-octagon"></i>
                    <span>@lang('header.support')</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('supports.tutorials') }}">
                    <i class="bi bi-question-octagon"></i>
                    <span>@lang('header.tutorials')</span>
                </a>
            </li>

            {{-- <li class="nav-item">
            <a class="nav-link " href="{{ url('/marketing') }}">
            <i class="bi bi-bag"></i>
            <span>@lang('header.marketing')</span>
            </a>
            </li> --}}
            @if ($corporate_activated == 1 || !$id_corporate)
            <div style="display: initial;">
                @if (auth()->user()->isAllowed())
                <li class="nav-item">
                    <a class="nav-link collapsed" data-bs-target="#report-nav" data-bs-toggle="collapse" href="#">
                        <i class="bi bi-bar-chart"></i><span>@lang('header.report')</span><i
                            class="bi bi-chevron-down ms-auto"></i>
                    </a>
                    <ul id="report-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                        <!-- <li>
                <a href="{{ route('reports.signupcommission') }}">
                    <i class="bi bi-circle"></i><span>@lang('header.signup_commission')</span>
                </a>
                </li> -->
                        <!-- <li>
                <a href="{{ route('reports.levelIncome') }}">
                    <i class="bi bi-circle"></i><span>@lang('header.level_income')</span>
                </a>
                </li> -->
                        {{-- <li>
                    <a href="{{ route('reports.signupcommission') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.referral_comission')</span>
                        </a>
                </li>
                <li>
                    <a href="{{ route('reports.poolcommission') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.pool_commission')</span>
                    </a>
                </li> --}}
                {{-- <li>
                <a href="{{ route('reports.stakingRewards') }}">
                <i class="bi bi-circle"></i><span>@lang('header.stacking_rewards')</span>
                </a>
                </li>
                <li>
                    <a href="{{ route('reports.monthlyCoins') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.monthly_coins')</span>
                    </a>
                </li> --}}
                <!-- <li>
                <a href="{{ route('reports.rankReward') }}">
                    <i class="bi bi-circle"></i><span>@lang('header.rank_reward')</span>
                </a>
                </li> -->

                <li>
                    <a href="{{ route('reports.transactions') }}">
                        <i class="bi bi-circle"></i><span style="text-transform: uppercase">Commissions</span>
                        {{-- <i class="bi bi-circle"></i><span>@lang('header.transaction')</span> --}}
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.commissions_month') }}">
                        <i class="bi bi-circle"></i><span style="text-transform: uppercase">Commissions Per Month</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('networks.myreferrals') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.direct_distributors')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('networks.IndicationEcomm') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.direct_customers')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.newrecruits') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.news_recruits')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.costumerrecruits') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.news_customer')</span>
                    </a>
                </li>


                <li>
                    <a href="{{ route('reports.teamorders') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.team_orders')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.signupcommission') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.volume_report')</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('reports.bonus_group') }}">
                        <i class="bi bi-circle"></i><span>@lang('header.bonus_list')</span>
                    </a>
                </li>
                </ul>
                </li>
                @endif
            </div>
            @endif

            @endif
            {{-- @endif --}}
            <li class="nav-item">
                <a class="nav-link collapsed" data-bs-target="#settings-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-gear"></i><span>@lang('header.settings')</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="settings-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class="bi bi-circle"></i><span>@lang('header.my_info')</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('users.password') }}">
                            <i class="bi bi-circle"></i><span>@lang('header.password')</span>
                        </a>
                    </li>
                </ul>
            </li>
            @if (auth()->user()->rule === 'RULE_ADMIN' || 'RULE_SUPPORT')
            {{-- <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.home') }}">
            <i class="bi bi-lock-fill"></i>
            <span>@lang('header.admin')</span>
            </a>
            </li> --}}
            @endif
            <li class="nav-item">

                <a class="nav-link collapsed" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-left"></i>
                    <span>@lang('header.logout')</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
            </ul>
        </aside>
        @if (!isset($count_itens))
        <main id="main" class="main p-0">
            <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);"
                id="herosection">
                <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
                    style="width: 100%; height: 25vh; background: linear-gradient(rgba(0, 0, 0, 0), rgba(0, 0, 0, 0)), url(&quot;/img/logo-2-gradient.png&quot;) center / contain no-repeat;">
                    <div class="container h-100">
                        <div class="row justify-content-center align-items-center h-100">
                            <div
                                class="col-md-10 col-lg-10 col-xl-8 d-flex d-sm-flex d-md-flex justify-content-center align-items-center mx-auto justify-content-md-start align-items-md-center justify-content-xl-center">
                                <div class="text-center" style="margin: 0 auto;">
                                    {{-- <p data-aos="fade" data-aos-duration="1500" data-aos-delay="400" data-aos-once="true"
                    class="phero">@lang('leadpage.home.txt')</p>
                  <h2 class="text-uppercase fw-bold mb-3 hhero hherosm" data-aos="fade-up" data-aos-duration="1400"
                    data-aos-delay="800" data-aos-once="true">
                    Pribonasorte</h2> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        @endif

        @yield('content')

        {{-- <script src="{{ mix('js/app.js') }}" defer></script> --}}

        <main id="main" style="padding: 0">
            @include('layouts.footer')
        </main>

</body>

<script>
    (function() {
        "use strict";
        const select = (el, all = false) => {
            el = el.trim()
            if (all) {
                return [...document.querySelectorAll(el)]
            } else {
                return document.querySelector(el)
            }
        }
        const on = (type, el, listener, all = false) => {
            if (all) {
                select(el, all).forEach(e => e.addEventListener(type, listener))
            } else {
                select(el, all).addEventListener(type, listener)
            }
        }
        const onscroll = (el, listener) => {
            el.addEventListener('scroll', listener)
        }
        if (select('.toggle-sidebar-btn')) {
            on('click', '.toggle-sidebar-btn', function(e) {
                select('body').classList.toggle('toggle-sidebar')
            })
        }
    })();

    $(document).ready(function() {
        $("#modal_action_new").click(function(event) {
            event.preventDefault();
            $(".modal_news").fadeIn();
        });
    });
    $(document).ready(function() {
        $("#closed_popup").click(function(event) {
            event.preventDefault();
            $(".modal_news").fadeOut();
        });
    });
</script>

</html>
