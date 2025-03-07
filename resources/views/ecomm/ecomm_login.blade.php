@extends('layouts.header_newsite')
@section('title', 'Pribonasorte | E-commerce')
@section('content')

  @php
    if (Session::has('redirect_buy')) {
        Session::forget('redirect_buy');
    }
  @endphp

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
      max-width: 100vw;
      padding: 5% 9%;
      background: #f1f1f1;
    }

    .raw {
      width: 100%;
      display: inline-block;
    }

    .box-log {
      width: 100%;
      display: inline-block;
      /* padding: 50px; */
      border-radius: 20px;
      background: transparent;
    }

    .band-title {
      margin-top: 50px;
    }

    .title-ecomm {
      font-size: 30px;
      font-weight: bold;
      color: #212121;
    }

    .sub-box-log {
      width: 100%;
      display: inline-block;
    }

    .form-log {
      width: fit-content;
      padding: 30px;
      display: inline-block;
      border: 1px solid #cdcdcd;
      background: #fafafa;
      border-radius: 10px;
    }

    .block-detal-login {
      width: 100%;
      padding-top: 30px;
      display: inline-block;
      border-radius: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1rem;
      max-width: 400px;
      margin: 0 auto;
    }

    .block-detal-login a {
      width: 100% !important;
    }

    .form-input-finalize {
      width: 100%;
      height: 40px;
      margin-bottom: 15px;
      border: #cdcdcd solid 1px;
      background: #ffffff;
      padding: 0px 10px;
      border-radius: 5px;
      outline: none;
    }

    .button-log-form {
      width: 100%;
      height: 40px;
      font-size: 15px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    .button-log-form:hover {
      width: 100%;
      height: 40px;
      font-size: 15px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    button.back-button {
      width: 100% !important;
      height: 50px;
      font-size: 15px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    button.back-button:hover {
      width: 100% !important;
      height: 50px;
      font-size: 15px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    .container-ecomm {
      background: transparent !important;
    }

    body {
      background-image: url(../img/fundo-newhome.jpg) !important;
    }
  </style>

  <main id="background-primary">
    <section class="container-ecomm">

      <div class="raw">
        <div class="band-title"></div>
      </div>

      <div class="raw" style="margin-top: 20px;">
        <div class="box-log">
          <div class="sub-box-log">
            <center>
              <div class="form-log">
                @if (session('error'))
                  <div class="alert alert-danger">
                    {{ session('error') }}
                  </div>
                @endif
                <p class="title-ecomm">Entrar</p>
                <form action="{{ route('log.user.ecomm') }}" method="POST" style="margin-bottom: 30px;">
                  @csrf
                  <input class="form-input-finalize" placeholder="E-mail" type="text" name="email">
                  {{--  --}}
                  <input class="form-input-finalize" placeholder="Senha" type="password" maxlength="16" name="password">
                  {{--  --}}
                  <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                    <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
                      Eu concordo com os
                      <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                        termos</a>
                    </label>
                  </div>
                  <button type="submit" class="button-log-form">Entrar</button>
                </form>
                <div style="width: 100%; display: inline-block;">
                  <a href="{{ route('recover.ecomm') }}">
                    <p style="float: right;">Recuperar senha</p>
                  </a>
                </div>
              </div>
            </center>
          </div>

          <div class="block-detal-login">
            <a href="{{ route('register.user.ecomm') }}"><button class="back-button">Criar conta</button></a>
            <a href="{{ route('ecomm') }}"><button class="back-button">Ir aos produtos</button></a>
          </div>
        </div>
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
