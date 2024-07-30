<!DOCTYPE html>
<html lang="en">

<style>
  .navbar li {
    width: max-content;
    display: flex;
  }

  header .navbar {
    padding: 10px 5%;
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

  .modal-backdrop {
    z-index: 0 !important;
  }
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
  <link rel="stylesheet" href="/css/newsite.css?v1.3">
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
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
    rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
  <style src="/js/progressbar.min.js"></style>
</head>

<body style="overflow-x: hidden;" class="body-image">
  <a href="https://api.whatsapp.com/send/?phone=5511960719101&text&type=phone_number&app_absent=0" target="blank_"
    class="content-btn-wpp">
    <i class="fa-brands fa-whatsapp"></i>
  </a>
  <header>
    <div class="container" id="nav-container">
      <nav class="navbar navbar-expand-lg fixed-top scrolled-revert" id="navbar-top">
        <div id="headerBackground" class="top-complete"></div>
        <div style="width: fit-content; display: inline-block;">
          <a href="/" class="navbar-brand">
            <img src="/img/logo-2.png" id="logo" alt="Pri Bonasorte" style="width: 100px">
          </a>
        </div>
        <button class="navbar-toggler action-toggle" type="button" data-bs-toggle="modal"
          data-bs-target="#exampleModal" aria-controls="navbar-links" aria-expanded="false"
          aria-label="Toggle navegation">
          <i class="fi fi-ss-menu-burger" style="color: #000;"></i>
        </button>

        <div class="collapse navbar-collapse justify-content-end" id="navbar-links" style="margin-left:0%;">
          <div class="navbar-nav" id="menu-top-toggle" style="width: 100%;">
            <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="/">Inicio</a>
              </li>
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="{{ route('ecomm') }}">Loja</a>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="{{ route('ecomm') }}" id="navbarScrollingDropdown"
                  role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Sobre
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                  <li><a class="dropdown-item btn-sub-menu" style="color:#212121"
                      href="{{ route('general_terms_conditions') }}">Termos</a></li>
                  <li><a class="dropdown-item btn-sub-menu" style="color:#212121"
                      href="{{ route('contact') }}">Contato</a></li>
                  <li><a class="dropdown-item btn-sub-menu" style="color:#212121"
                      href="{{ route('return_policy') }}">Política de cancelamento</a></li>
                  <li><a class="dropdown-item btn-sub-menu" style="color:#212121"
                      href="{{ route('gdpr_policy') }}">Política de privacidade</a></li>
                </ul>
              </li>

            </ul>
          </div>
          {{-- <input class="input-search" type="text" placeholder="Search and press enter" id="search-top-toggle"> --}}
        </div>
        <div class="collapse navbar-collapse justify-content-end" style="width: fit-content;">

          <div>
            <li class="nav-item dropdown">
              <a href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown"
                aria-expanded="false" target="_blank"><button class="btn-home"><i
                    class="fa fa-paper-plane"></i></button></a>

              <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                <li>
                  <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                    href="https://www.instagram.com/pribonasortematrizes/" target="_blank"> <i
                      class="fa-brands fa-instagram" style="font-size: 1rem;"> </i> Follow us on
                    Instagram</a>
                </li>

                <li>
                  <a style="display: flex; align-items: center; gap:.5rem" class="dropdown-item"
                    href="https://api.whatsapp.com/send/?phone=5511960719101&text&type=phone_number&app_absent=0"
                    target="_blank">
                    <i class="fa-brands fa-whatsapp" style="font-size: 1rem;"></i>Follow us on
                    Whatsapp</a>
                </li>

              </ul>
            </li>
          </div>

          <div class="ms-2">
            <a href="{{ route('login') }}" target="_blank"><button class="btn-home">Backoffice</button></a>
          </div>
          <div class="mx-2 d-flex" style="gap: .5rem; align-items: center">
            <a href="{{ route('index.cart') }}"><i class="fa-sharp fa-solid fa-bag-shopping"
                style="color: #D26075; font-size: 20px;"></i></a>

            @if (isset(session()->get('buyer')->name))
              <li class="nav-item dropdown" style="display: flex; align-items:center">
                <a class="nav-link dropdown-toggle btn-home" id="navbarScrollingDropdown" role="button"
                  style="display: flex; align-items:center" data-bs-toggle="dropdown" aria-expanded="false"
                  style="color: white">
                  {{ explode(' ', session()->get('buyer')->name)[0] }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">

                  <li>
                    <a class="dropdown-item" style="color:#212121"
                      href="{{ route('orders.panel.ecomm') }}">Orders</a>
                  </li>

                  <li>
                    <a class="dropdown-item" style="color:#212121" href="{{ route('page.panel.ecomm') }}">Menu</a>
                  </li>

                  <li>
                    <a class="dropdown-item" style="color:#212121"
                      href="{{ route('orders.smartshipReport.ecomm') }}">Smartship</a>
                  </li>

                  <li>
                    <a class="dropdown-item" style="color:#212121"
                      href="{{ route('orders.settings.ecomm') }}">Settings</a>
                  </li>

                  <li>
                    <a class="dropdown-item" style="color:#212121" href="{{ route('ecomm') }}">Shop Now</a>
                  </li>

                  <li><a class="dropdown-item" style="color:#212121" href="{{ route('logout.user.ecomm') }}">Log
                      Out</a>
                  </li>

                </ul>
              </li>
            @else
              <a href="{{ route('page.login.ecomm') }}" target="_blank"><button
                  class="btn-home">Customer</button></a>
            @endif
          </div>

        </div>
      </nav>



      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Menu</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="navbar-model-menu">
                <a href="/" id="home-menu">Home</a>
                <a href="{{ route('ecomm') }}" id="home-menu">Shop</a>

                <a href="{{ route('question') }}">LP FAQ</a>
                <a href="{{ route('about') }}" id="time-menu">About us</a>
                <a href="{{ route('news') }}" id="products-menu">News</a>
                <a href="{{ route('contact') }}" id="contact-menu">Contact</a>
                <a class="dropdown-item" href="{{ route('login') }}">Login Backoffice</a>
                <a class="dropdown-item" style="color:#212121" href="{{ route('index.cart') }}">Cart</a>
                @if (isset(session()->get('buyer')->name))
                  <li class="nav-item dropdown" style="display: flex; align-items:center">
                    <a class="nav-link dropdown-toggle button-panel" id="navbarScrollingDropdown" role="button"
                      style="display: flex; align-items:center" data-bs-toggle="dropdown" aria-expanded="false"
                      style="color: white">
                      {{ explode(' ', session()->get('buyer')->name)[0] }}
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                      <li>
                        <a class="dropdown-item" style="color:#212121"
                          href="{{ route('orders.panel.ecomm') }}">Orders</a>
                      </li>

                      <li>
                        <a class="dropdown-item" style="color:#212121"
                          href="{{ route('page.panel.ecomm') }}">Menu</a>
                      </li>

                      <li>
                        <a class="dropdown-item" style="color:#212121"
                          href="{{ route('orders.smartshipReport.ecomm') }}">Smartship</a>
                      </li>

                      <li>
                        <a class="dropdown-item" style="color:#212121"
                          href="{{ route('orders.settings.ecomm') }}">Settings</a>
                      </li>

                      <li>
                        <a class="dropdown-item" style="color:#212121" href="{{ route('ecomm') }}">Shop Now</a>
                      </li>

                      <li><a class="dropdown-item" style="color:#212121" href="{{ route('logout.user.ecomm') }}">Log
                          Out</a>
                      </li>

                    </ul>
                  </li>
                @else
                  <a href="{{ route('page.login.ecomm') }}" target="_blank"><button
                      class="btn-home">Customer</button></a>
                @endif
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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

    document.addEventListener('DOMContentLoaded', () => {
      const header = document.getElementById('navbar-top');

      window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
          header.classList.remove('scrolled-revert');
          header.classList.add('scrolled');
        } else {
          header.classList.remove('scrolled');
          header.classList.add('scrolled-revert');
        }
      });
    });
  </script>
</body>

</html>
