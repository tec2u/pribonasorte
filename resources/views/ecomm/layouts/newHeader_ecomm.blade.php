<!DOCTYPE html>
<html lang="en">

<style>
  .navbar li {
    width: max-content;
    display: flex;
  }

  header .navbar {
    padding: 0 5%;
  }

  footer .container {
    padding: 35px 0 0 0;
  }

  #headerBackground {
    padding: 0 5% !important;
    background-color: #ffff;
    width: 100vw !important;
    margin: 0 !important;
    left: 0;
    right: 0;
  }

  #btn1 {
    display: initial !important;
    opacity: 1 !important;
  }

  nav {
    background-color: #ffffff !important;
  }

  .navbar-toggler .fi-ss-menu-burger {
    color: #000 !important;
    fill: #000 !important;
  }

  .cart-mobile-icon {}
</style>

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
  <link rel="stylesheet" href="/css/newsite.css">
  <!-- scripts -->
  <!-- icones -->
  <script src="https://kit.fontawesome.com/c595f91d8a.js" crossorigin="anonymous"></script>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-brands/css/uicons-brands.css'>
  <link rel='stylesheet' href='https://cdn-uicons.flaticon.com/uicons-solid-straight/css/uicons-solid-straight.css'>
  <!-- progressbar -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Slick Carousel -->
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" />
  <link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
  <style src="/js/progressbar.min.js"></style>
</head>

<body style="overflow-x: hidden;">
  <header>
    <div class="container" id="nav-container">
      {{-- --}}
      <nav class="navbar navbar-expand-lg fixed-top" id="navbar-top">
        <div id="headerBackground" class="top-complete"></div>
        <div style="width: fit-content; display: inline-block;">
          <a href="/" class="navbar-brand">
            <img src="/img/logo-2.png" id="logo" alt="LIFEPROSPER">
          </a>
        </div>

        <div class="cart-mobile-icon" style="display: none;">
          <a href="{{ route('index.cart') }}"><i class="fa-sharp fa-solid fa-bag-shopping"
              style="color: #000; font-size: 20px;"></i></a>
        </div>

        <button class="navbar-toggler action-toggle" type="button" data-taggle="collapse" data-target="#navbar-links"
          aria-controls="navbar-links" aria-expanded="false" aria-label="Toggle navegation">
          <i class="fi fi-ss-menu-burger" style="color: #ffffff;"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbar-links" style="margin-left:0%;">
          <div class="navbar-nav" id="menu-top-toggle" style="width: 100%;">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Home</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  Shop
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('detals', ['id' => 9]) }}">Omega
                      3</a>
                  </li>
                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('detals', ['id' => 10]) }}">Melatonin</a></li>
                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('detals', ['id' => 11]) }}">Omega</a></li>
                  {{-- <li><a class="dropdown-item" style="color:#212121" href="{{ route('molecule_of_life') }}">Molecule of
                                    life</a>
                            </li>
                            <li><a class="dropdown-item" style="color:#212121" href="{{ route('vitamin_and_minerals') }}">Vitamin
                                    and Minerals</a></li> --}}
                </ul>
              </li>
              {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('question') }}">LP FAQ</a>
                        </li> --}}
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button"
                  data-bs-toggle="dropdown" aria-expanded="false">
                  About US
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('about') }}#lifeprosper">About
                      LifeProsper</a></li>
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
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('question') }}">LP FAQ</a></li>
                </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('news') }}" tabindex="-1" aria-disabled="true">News</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('contact') }}" tabindex="-1" aria-disabled="true">Contact</a>
              </li>

            </ul>
          </div>
          <input class="input-search" type="text" placeholder="Search and press enter" id="search-top-toggle">
        </div>
        <div class="collapse navbar-collapse justify-content-end" style="width: fit-content;">
          <div class="mx-2">
            {{-- <li class="nav-item">
              <a class="nav-link" style="color:#212121; font-weight: 800" href="{{ route('index.cart') }}"
                tabindex="-1" aria-disabled="true">Cart</a>
            </li> --}}

            <li>
              <a href="{{ route('index.cart') }}"><i class="fa-sharp fa-solid fa-bag-shopping"
                  style="color: #000; font-size: 20px;"></i></a>
            </li>

            {{-- <a href="{{ route('index.cart') }}" id="contact-menu">Cart</a> --}}
          </div>
          <div class="collapse navbar-collapse justify-content-end" style="width: fit-content; margin-right: .5rem">
            <div>
              <li class="nav-item dropdown">
                <a href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown"
                  aria-expanded="false" target="_blank"><button class="btn-home btn-home-grad"><i
                      class="fa fa-paper-plane"></i></button></a>

                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li>
                    <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                      href="https://www.youtube.com/channel/UCfK89eNJjOYbwowYjdO8MnQ" target="_blank"> <i
                        class="fa-brands fa-youtube" style="font-size: 1rem;"></i> Follow us on
                      Youtube</a>
                  </li>

                  <li>
                    <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                      href="https://t.me/+4pRjuBp4Pw1kYjY0" target="_blank">
                      <i class="fa-brands fa-telegram" style="font-size: 1rem;"></i> Follow us on
                      Telegram</a>
                  </li>

                  <li>
                    <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                      href="https://www.facebook.com/profile.php?id=61557861185751" target="_blank"> <i
                        class="fa-brands fa-facebook" style="font-size: 1rem;"></i>Follow us on
                      Facebook</a>
                  </li>

                  <li>
                    <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                      href="https://www.instagram.com/lifeprosper_official/" target="_blank"> <i
                        class="fa-brands fa-instagram" style="font-size: 1rem;"> </i> Follow us on
                      Instagram</a>
                  </li>

                  <li>
                    <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                      href="https://chat.whatsapp.com/JDAxBWfoeFW0eYErsFc7br" target="_blank">
                      <i class="fa-brands fa-whatsapp" style="font-size: 1rem;"></i>Follow us on
                      Whatsapp</a>
                  </li>

                </ul>
              </li>
            </div>

          </div>
          <div>
            @if (isset(session()->get('buyer')->name))
              <li class="nav-item dropdown" style="display: flex; align-items:center">
                <a class="nav-link dropdown-toggle button-panel" id="navbarScrollingDropdown" role="button"
                  style="display: flex; align-items:center" data-bs-toggle="dropdown" aria-expanded="false">
                  {{ explode(' ', session()->get('buyer')->name)[0] }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">

                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('page.panel.ecomm') }}">Panel</a></li>
                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('ecomm') }}">Shop Now</a></li>
                  <li><a class="dropdown-item" style="color:#212121"
                      href="{{ route('logout.user.ecomm') }}">Exit</a></li>

                </ul>
              </li>
            @else
              <a href="{{ route('page.login.ecomm') }}" target="_blank"><button class="btn-home">Login</button></a>
            @endif
          </div>
        </div>
      </nav>



      <div class="w-[100%] inline-block">
        <div class="model-menu">
          <div class="box-model-menu" style="overflow-y: scroll">
            {{-- <form action="" method=""> --}}
            {{-- @csrf --}}
            {{-- <input class="input-search" placeholder="Search" name="" type="text"> --}}
            {{-- </form> --}}
            <div class="navbar-model-menu">
              @if (isset(session()->get('buyer')->name))
                <li class="nav-item dropdown" style="display: flex; align-items:center">
                  <a class="nav-link dropdown-toggle button-panel" id="navbarScrollingDropdown" role="button"
                    style="display: flex; align-items:center; color:white; width: fit-content"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    {{ explode(' ', session()->get('buyer')->name)[0] }}
                  </a>
                  <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">

                    <li><a class="dropdown-item" style="color:#212121"
                        href="{{ route('page.panel.ecomm') }}">Panel</a></li>
                    <li><a class="dropdown-item" style="color:#212121" href="{{ route('ecomm') }}">Shop Now</a></li>
                    <li><a class="dropdown-item" style="color:#212121"
                        href="{{ route('logout.user.ecomm') }}">Exit</a></li>

                  </ul>
                </li>
              @else
                <a href="{{ route('page.login.ecomm') }}" target="_blank"><button
                    class="btn-home">Login</button></a>
              @endif

              <a href="/" id="home-menu">Home</a>
              <a href="#" id="home-menu">Shop</a>
              <ul class="products-itens">
                <li><a href="{{ route('detals', ['id' => 9]) }}">Omega 3</a></li>
                <li><a href="{{ route('detals', ['id' => 10]) }}">Melatonin</a></li>
                <li><a href="{{ route('detals', ['id' => 11]) }}">Omega</a></li>
                {{-- <li><a href="{{ route('molecule_of_life') }}">Molecule of life</a></li>
                                <li><a href="{{ route('vitamin_and_minerals') }}">Vitamin and Minerals</a></li> --}}
              </ul>
              <a href="{{ route('question') }}">LP FAQ</a>
              <a href="{{ route('about') }}" id="time-menu">About us</a>
              <a href="{{ route('news') }}" id="products-menu">News</a>
              <a href="{{ route('contact') }}" id="contact-menu">Contact</a>

              <a href="{{ route('index.cart') }}" id="contact-menu">Cart</a>






            </div>
          </div>
        </div>
      </div>
    </div>
  </header>

  @yield('content')
  @include('layouts.footer_newsite')
  <!-- script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
      $("#about-menu, #contact-menu, #news-menu, #faq-menu").mouseover(function(event) {
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
      $(".home-menu, #contact-menu, #news-menu, #faq-menu").mouseover(function(event) {
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
