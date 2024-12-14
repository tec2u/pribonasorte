<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pri Bonasorte</title>
  <!-- font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@100;300;400;900&family=Shadows+Into+Light&display=swap"
    rel="stylesheet">
  <link rel="icon" type="image/png" sizes="400x400" href="/images/favicon.png">
  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <!-- css -->
  <link rel="stylesheet" href="/css/style.css">
  <link rel="stylesheet" href="/css/normalize.css">
  <!-- scripts -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <!-- icones -->
  <script src="https://kit.fontawesome.com/c595f91d8a.js" crossorigin="anonymous"></script>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css'>
  <!-- progressbar -->
  <style src="/js/progressbar.min.js"></style>
  <style src="/js/script.js"></style>
</head>

<body>
  <header>
    <div class="container" id="nav-container">
      {{--  --}}
      <nav class="navbar navbar-expand-lg fixed-top" style="background-color: #27032f;" id="navbar-top">
        <div id="headerBackground" class="top-complete"></div>
        <div style="width: 10%; display: inline-block;">
          <a href="/" class="navbar-brand">
            <img src="/img/Logo_AuraWay.png" id="logo" alt="Pribonasorte">
          </a>
        </div>
        <ul class="btns-header cart-mobile" style="margin: 15px 0px 0px 100px;">
          <li>
            <a href="{{ route('index.cart') }}"><i class="fa-sharp fa-solid fa-bag-shopping"
                style="color: #ffffff; font-size: 20px;"></i></a>
          </li>
          <li>
            <p style="color: #ffffff; font-size: 15px; margin-left: -20px;">{{ $count_order ?? '' }}</p>
          </li>
        </ul>
        <button class="navbar-toggler action-toggle" type="button" data-taggle="collapse" data-target="#navbar-links"
          aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navegation">
          <i class="fi fi-ss-menu-burger" style="color: #ffffff;"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar-links" style="width: 35%;">
          <div class="navbar-nav" id="menu-top-toggle">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  Products
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('detals', ['id' => 9]) }}">Omega
                      3</a>
                  </li>
                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('detals', ['id' => 10]) }}">Melatonin</a></li>
                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('detals', ['id' => 11]) }}">Omega</a></li>
                </ul>
              </li>
              {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('question') }}" style="width: max-content">LP FAQ</a>
              </li> --}}
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  About US
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('about') }}#Pribonasorte">About
                      Pribonasorte</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('about') }}#vision">Our
                      vision</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('about') }}#management">Our
                      management</a></li>
                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('general_terms_conditions') }}">Terms & Conditions</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('return_policy') }}">Return and
                      Complaint</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('gdpr_policy') }}">GDPR
                      Policy</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('payment_policy') }}">Payment
                      Policy</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('question') }}">
                      LP FAQ</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('news') }}" tabindex="-1" aria-disabled="true">News</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('blog') }}" tabindex="-1" aria-disabled="true">Blog</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('contact') }}" tabindex="-1" aria-disabled="true">Contact</a>
              </li>
            </ul>
          </div>
          <input class="input-search" type="text" placeholder="Search and press enter" id="search-top-toggle">
        </div>
        <div class="collapse navbar-collapse justify-content-end" style="width: 43%;">
          <ul class="btns-header" style="margin-top: 30px;">
            <li>
              <i class="fa-solid fa-magnifying-glass" id="btn-search-toggle-in"
                style="color: #ffffff; font-size: 17px; float-right; cursor: pointer;"></i>
              <i class="fa-solid fa-magnifying-glass" id="btn-search-toggle-out"
                style="color: #ffffff; font-size: 17px; float-right; cursor: pointer;"></i>
            </li>
            <li>
              <a href="{{ route('index.cart') }}"><i class="fa-sharp fa-solid fa-bag-shopping"
                  style="color: #ffffff; font-size: 20px;"></i></a>
            </li>
            <li>
              <p style="color: #ffffff; font-size: 15px; margin-left: -20px;">{{ $count_order ?? '' }}</p>
            </li>
          </ul>
          {{--  --}}
          {{-- <div class="btn-group" style="margin-left: 50px;">
            <button style="background: #ffffff; color: #212121;" class="btn dropdown-toggle btn-lang" type="button"
              data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
              @lang('header.language')
            </button>
            <ul class="dropdown-menu">
              <li>
                <a class="dropdown-item" href="/setlocale/en"><img src="../assetsWelcome/images/flaguk.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.english')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/es"><img src="../assetsWelcome/images/flagspa.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.spanish')</a>
              </li>
              <li>
                <a class="dropdown-item" href="/setlocale/de"><img src="../assetsWelcome/images/flagger.png"
                    style="width: 18px;margin-right:10px" alt="...">@lang('header.german')</a>
              </li>
              <li>
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
              </li>
            </ul>
          </div> --}}
        </div>
        <div class="collapse navbar-collapse justify-content-end" style="width: 22%;">

          {{-- <p style="color: #ffffff; margin-top: 15px; float-right;">{{ $user->name }}</p> --}}

          @if (session()->has('buyer'))
            <li class="nav-item dropdown" style="display: flex; align-items:center">
              <a class="nav-link dropdown-toggle button-panel" id="navbarScrollingDropdown" role="button"
                style="display: flex; align-items:center" data-bs-toggle="dropdown" aria-expanded="false">
                {{ explode(' ', session()->get('buyer')->name)[0] }}
              </a>
              <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">

                <li><a class="dropdown-item" style="color:#212121" href="{{ route('page.panel.ecomm') }}">Panel</a>
                </li>
                <li><a class="dropdown-item" style="color:#212121" href="{{ route('ecomm') }}">Shop Now</a></li>
                <li><a class="dropdown-item" style="color:#212121" href="{{ route('logout.user.ecomm') }}">Exit</a>
                </li>

              </ul>
            </li>
          @else
            <div>
              <a href="{{ route('page.login.ecomm') }}"><button class="button-panel-2">Login</button></a>
            </div>
          @endif
        </div>
      </nav>

      <div class="w-[100%] inline-block">
        <div class="model-menu">
          <div class="box-model-menu">
            <form action="" method="">
              @csrf
              <input class="input-search" placeholder="Search" name="" type="text">
            </form>
            <div class="navbar-model-menu">
              <a href="/" id="home-menu">Home</a>
              <a href="#" id="home-menu">Products</a>
              <ul class="products-itens">
                <li><a href="{{ route('detals', ['id' => 9]) }}">Omega 3</a></li>
                <li><a href="{{ route('detals', ['id' => 10]) }}">Melatonin</a></li>
                <li><a href="{{ route('detals', ['id' => 11]) }}">Omega</a></li>
              </ul>
              <a href="{{ route('question') }}">LP FAQ</a>
              <a href="{{ route('about') }}" id="time-menu">About us</a>
              <a href="{{ route('news') }}" id="products-menu">News</a>
              <a href="{{ route('blog') }}" id="products-menu">Blog</a>
              <a href="{{ route('contact') }}" id="contact-menu">Contact</a>
              <a class="dropdown-item" href="{{ route('login') }}">Login Backoffice</a>
              <a class="dropdown-item" href="{{ route('page.login.ecomm') }}">Customer Login</a>
              <a href="https://webportal.omegaquant.com/" target="_blank"><button class="btn btn-primary btn-sm"
                  id="btn1">Blood
                  test</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </header>
  <!--  -->
  {{-- <section id="background-top">
        <video autoplay muted loop class="bg_video" id="autoPlayForce">
            <source src="/videos/fitnessAuraWay.mp4" type="video/mp4">
        </video>
        <video autoplay muted loop class="bg_video_vertical" id="autoPlayForce2">
            <source src="/videos/fitnessAuraWay.mp4" type="video/mp4">
        </video>
        <!--  -->
        <div id="box-inf">
            <p id="title-welcome">Welcome!</p>
            <p id="title-landing">Pribonasorte</p>
            <center><button class="btn-shop-now">Shop now</button></center>
        </div>
    </section> --}}
  <!--  -->
  @yield('content')
  <!-- bloco blog -->
  @include('layouts.footer')
  <!-- script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>
  <script>
    $(document).ready(function() {
      $(".action-toggle").click(function(event) {
        event.preventDefault();
        $(".model-menu").fadeToggle();
      });
    });

    $(document).ready(function() {
      $("#btn-search-toggle-in").click(function(event) {
        event.preventDefault();
        $("#search-top-toggle").fadeIn();
        $("#menu-top-toggle").hide();
        $("#btn-search-toggle-out").show();
        $("#btn-search-toggle-in").hide();
      });
    });

    $(document).ready(function() {
      $("#btn-search-toggle-out").click(function(event) {
        event.preventDefault();
        $("#menu-top-toggle").fadeIn();
        $("#search-top-toggle").hide();
        $("#btn-search-toggle-in").show();
        $("#btn-search-toggle-out").hide();
      });
    });

    $(document).ready(function() {
      $(".home-menu, #list-cascata").mouseover(function(event) {
        event.preventDefault();
        $(".sub-menu-btns").show();

      });
    });

    $(document).ready(function() {
      $("#about-menu, #contact-menu, #news-menu, #blog-menu, #faq-menu").mouseover(function(event) {
        event.preventDefault();
        $(".sub-menu-btns").hide();
      });
    });

    $(document).ready(function() {
      $("#list-cascata").mouseout(function(event) {
        event.preventDefault();
        $(".sub-menu-btns").hide();
      });
    });

    $(document).ready(function() {
      $("#about-menu, #list-cascata2").mouseover(function(event) {
        event.preventDefault();
        $(".sub-menu-btns2").show();
      });
    });

    $(document).ready(function() {
      $(".home-menu, #contact-menu, #news-menu, #blog-menu, #faq-menu").mouseover(function(event) {
        event.preventDefault();
        $(".sub-menu-btns2").hide();
      });
    });

    $(document).ready(function() {
      $("#list-cascata2").mouseout(function(event) {
        event.preventDefault();
        $(".sub-menu-btns2").hide();
      });
    });

    $(document).ready(function() {
      $("#img1").click(function(event) {
        event.preventDefault();
        $("#image1").fadeIn();
        $(".base-img").fadeIn();
        $("#image2").hide();
        $("#image3").hide();
        $("#video").hide();
      });
    });

    $(document).ready(function() {
      $("#img2").click(function(event) {
        event.preventDefault();
        $("#image2").fadeIn();
        $(".base-img").fadeIn();
        $("#image1").hide();
        $("#image3").hide();
        $("#video").hide();
      });
    });

    $(document).ready(function() {
      $("#img3").click(function(event) {
        event.preventDefault();
        $("#image3").fadeIn();
        $(".base-img").fadeIn();
        $("#image2").hide();
        $("#image1").hide();
        $("#video").hide();
      });
    });

    $(document).ready(function() {
      $("#video_min").click(function(event) {
        event.preventDefault();
        $("#video").fadeIn();
        $(".base-img").hide();
      });
    });


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
</body>

</html>
