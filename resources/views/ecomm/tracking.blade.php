@extends('layouts.header_newsite')
@section('title', 'Pribonasorte | E-commerce')
@section('content')

  <style>
    .text-title {
      font-size: 50px;
      font-weight: bold;
      color: #ffffff;
      text-transform: uppercase;
    }

    .line-content {
      margin-bottom: 30px;
    }

    .container-ecomm {
      width: 100%;
      padding: 5% 9%;
      background: #f1f1f1;
    }

    .raw {
      width: 100%;
      display: inline-block;
    }

    .card-ecomm {
      width: 23%;
      display: inline-block;
      margin: 0px 1%;
      background: #ffffff;
      border-radius: 5px;
      border: 1px solid #eeeeee;
      float: left;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 2px 2px 5px 1px rgba(0, 0, 0, 0.1);
      transition: 200ms linear;
    }

    .card-ecomm:hover {
      width: 23%;
      display: inline-block;
      margin: 0px 1%;
      background: #ffffff;
      border-radius: 5px;
      border: 1px solid #eeeeee;
      float: left;
      padding: 30px;
      margin-bottom: 30px;
      box-shadow: 2px 2px 20px 3px rgba(0, 0, 0, 0.1);
      transition: 200ms linear;
    }

    .moldure {
      width: 100%;
      height: 600px;
      background-size: 100%;
      overflow: hidden;
      cursor: pointer;
    }

    img.imagem_zoon {
      max-width: 100%;
      -moz-transition: all 0.3s;
      -webkit-transition: all 0.3s;
      transition: all 0.3s;
    }

    img.imagem_zoon:hover {
      -moz-transform: scale(1.1);
      -webkit-transform: scale(1.1);
      transform: scale(1.1);
    }

    h5.tittle-name {
      font-weight: bold;
    }

    button.btn-ecomm {
      width: 50%;
      display: inline-block;
      height: 35px;
      border-radius: 100px;
      background: #d26075;
      color: #ffffff;
      margin-top: 30px;
      border: 0px;
      font-size: 14px;
      transition: 200ms linear;
    }

    button.btn-ecomm:hover {
      width: 50%;
      display: inline-block;
      height: 35px;
      border-radius: 100px;
      background: #eeeeee;
      color: #d26075;
      margin-top: 30px;
      border: 0px;
      font-size: 14px;
      transition: 200ms linear;
    }

    .img-detals {
      width: 50%;
      padding: 40px;
      float: left;
      display: inline-block;
      background: #ffffff;
      border-radius: 5px;
      box-shadow: 2px 2px 20px 3px rgba(0, 0, 0, 0.1);
    }

    .info-detals {
      width: 50%;
      float: left;
      padding-left: 5%;
      display: inline-block;
    }

    h5.tittle-name {
      font-size: 30px;
      font-weight: bold;
    }

    h6.text-price {
      font-size: 20px;
    }

    .quantify-buttom {
      width: 100px;
      height: 40px;
      border-radius: 100px;
      padding: 10px;
      border: 1px solid #919191;
      margin-top: 20px;
    }

    button.button-cart {
      width: 100%;
      height: 45px;
      border-radius: 5px;
      background-color: #d26075;
      color: #ffffff;
      font-size: 14px;
      margin-top: 30px;
      font-weight: bold;
      border: 1px #d26075 solid;
      text-transform: uppercase;
      transition: 200ms linear;
    }

    button.button-cart:hover {
      width: 100%;
      height: 45px;
      border-radius: 5px;
      background-color: #eeeeee;
      color: #d26075;
      font-size: 14px;
      margin-top: 30px;
      font-weight: bold;
      border: 1px #d26075 solid;
      text-transform: uppercase;
      transition: 200ms linear;
    }

    .line-detals {
      margin: 50px 0px 20px 0px;
    }

    .back-button {
      font-size: 17px;
      font-weight: bold;
      color: #d26075;
    }

    @media all and (min-width:2px) and (max-width:820px) {
      .img-detals {
        width: 100%;
        padding: 40px;
        float: left;
        display: inline-block;
        background: #ffffff;
        margin-bottom: 30px;
        border-radius: 5px;
        box-shadow: 2px 2px 20px 3px rgba(0, 0, 0, 0.1);
      }

      .info-detals {
        width: 100%;
        float: left;
        padding-left: 0%;
        display: inline-block;
      }

      .moldure {
        width: 100%;
        height: auto;
        background-size: 100%;
        overflow: hidden;
        cursor: pointer;
      }
    }

    @import url("https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600&display=swap");

    .root {
      padding: 1rem;
      border-radius: 5px;
      box-shadow: 0 2rem 6rem rgba(0, 0, 0, 0.3);
    }

    figure {
      display: flex;
    }

    figure img {
      width: 8rem;
      height: 8rem;
      border-radius: 15%;
      border: 1.5px solid #27032F;
      margin-right: 1.5rem;
      padding: 1rem;
    }

    figure figcaption {
      display: flex;
      flex-direction: column;
      justify-content: space-evenly;
    }

    figure figcaption h4 {
      font-size: 1.4rem;
      font-weight: 500;
    }

    figure figcaption h6 {
      font-size: 1rem;
      font-weight: 300;
    }

    figure figcaption h2 {
      font-size: 1.6rem;
      font-weight: 500;
    }

    .order-track {
      margin-top: 2rem;
      padding: 0 1rem;
      border-top: 1px dashed #2c3e50;
      padding-top: 2.5rem;
      display: flex;
      flex-direction: column;
    }

    .order-track-step {
      display: flex;
      height: 7rem;
    }

    .order-track-step:last-child {
      overflow: hidden;
      height: 4rem;
    }

    .order-track-step:last-child .order-track-status span:last-of-type {
      display: none;
    }

    .order-track-status {
      margin-right: 1.5rem;
      position: relative;
    }

    .order-track-status-dot {
      display: block;
      width: 2.2rem;
      height: 2.2rem;
      border-radius: 50%;
      background: #27032F;
    }

    .order-track-status-line {
      display: block;
      margin: 0 auto;
      width: 2px;
      height: 7rem;
      background: #27032F;
    }

    .order-track-text-stat {
      font-size: 1.3rem;
      font-weight: 500;
      margin-bottom: 3px;
    }

    .order-track-text-sub {
      font-size: 1rem;
      font-weight: 300;
    }

    .order-track {
      transition: all .3s height 0.3s;
      transform-origin: top center;
      display: flex;
      /* flex-direction: column-reverse; */

    }

    .search-box {
      display: flex;
      font-size: 1rem;
      padding: 5px 10px;
      border: 1px solid #C1C1C1;
      background-color: white;
      width: auto;
      border-radius: 10px;
      transition: .2s;

    }

    .search-box:hover {
      border-color: #AAAAAA;
    }

    .search-box:focus-within {
      border-color: #26022E;
      box-shadow: 0 0 0 5px rgba(38, 2, 46, 0.103);
    }

    input {
      font-family: Proxima Nova;
      letter-spacing: -0.2px;
      font-size: 1rem;
      border: none;
      color: #323232;
      width: auto;
    }

    button:hover {
      cursor: pointer;
    }

    input:focus {
      outline: none;
    }

    input[type='search']::-webkit-search-cancel-button {
      -webkit-appearance: none;
    }

    .clear:not(:valid)~.search-clear {
      display: none;
    }
  </style>

  <main id="main" style="display: none;" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%; height: 50vh; background: #d26075;">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            {{-- <center><p class="text-title">Vitamin and Minerals</p></center> --}}
          </div>
        </div>
      </div>
    </section>
  </main>

  <main id="background-primary" style="margin-top: 30px;">
    <section class="container-ecomm">

      <div class="raw">
        <div class="band-title">
          <a href="{{ route('ecomm') }}">
            <p class="back-button"><i class="fi fi-rr-arrow-left"></i> Voltar</p>
          </a>
        </div>
      </div>

      <div class="raw" style="margin-top: 20px;">

        <section class="root" style="padding: 2rem;">
          <figure style="display: flex;width:100%; justify-content: space-between">
            @if (isset($orderNumber))
              <figcaption>
                <h4>Detalhes</h4>
                <h6>Pedido</h6>
                {{-- <h2>#{{ $orderNumber }}</h2> --}}
              </figcaption>
            @endif
            {{-- <div>
              <link rel="stylesheet"
                href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,200,0,0" />
              <form class="search-box">
                <button style="background: none; border: none; padding: 0"><span
                    style="font-size: 30px; vertical-align: -1px; color: #9B9B9B"
                    class="material-symbols-outlined">search</span></button>
                <input style="vertical-align: 4px;" name="order" type="search" name="focus"
                  placeholder="Order or CodeTracking" id="search-input" value="">
              </form>
            </div> --}}
          </figure>
          @if (isset($info) && count($info) > 0)
            <div class="order-track" style="padding-bottom: 2rem">
              @foreach ($info as $item)
                <div class="order-track-step" style="height: fit-content">
                  <div class="order-track-status">
                    <span class="order-track-status-dot"></span>
                    <span class="order-track-status-line"></span>
                  </div>
                  <div class="order-track-text">
                    @php
                      $data = $item['eventDate'];
                      // dd($item['code']);
                    @endphp
                    <p class="order-track-text-stat" style="font-size: 1rem;">
                      {{ $item['name'] ?? '' }}
                    </p>
                    <span class="order-track-text-sub">{{ date('d/m/Y H:i:s', strtotime($data)) }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="order-track" style="height: 250px;width:100%;">
              @if (isset($orderNumber))
                <h5>
                  Sem resultados
                </h5>
              @else
                <h5>
                  Preencha o campo
                </h5>
              @endif
            </div>
          @endif
        </section>

      </div>
    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>
  <script>
    $(document).ready(function() {
      $("#description-pro").click(function(event) {
        event.preventDefault();
        $(".block-description").fadeIn();
        $(".block-downloads").hide();
      });
    });

    $(document).ready(function() {
      $("#downloads-pro").click(function(event) {
        event.preventDefault();
        $(".block-downloads").fadeIn();
        $(".block-description").hide();
      });
    });
  </script>

@endsection
