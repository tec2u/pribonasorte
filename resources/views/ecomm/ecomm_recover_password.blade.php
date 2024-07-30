@extends('layouts.header_newsite')
@section('title', 'Lifeprosper | E-commerce')
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

    .box-log {
      width: 100%;
      display: inline-block;
      padding: 50px;
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
      width: 400px;
      padding: 30px;
      display: inline-block;
      border: 1px solid #cdcdcd;
      background: #fafafa;
      border-radius: 10px;
    }

    .block-detal-login {
      width: 100%;
      padding: 30px;
      float: left;
      display: inline-block;
      border-radius: 10px;
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
      width: 400px;
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
      width: 400px;
      height: 50px;
      font-size: 15px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    @media all and (min-width:2px) and (max-width:820px) {}
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
                <p style="font-size: 17px; margin: 30px 0px; line-height: 20px;">Você perdeu o acesso? Insira seu e-mail de
                  registro para recuperar sua senha.</p>
                <form action="{{ route('recover_validade.ecomm') }}" method="POST" style="margin-bottom: 30px;">
                  @csrf
                  <input class="form-input-finalize" placeholder="E-mail" type="text" name="email">
                  {{--  --}}
                  <button type="submit" class="button-log-form">Recuperar</button>
                </form>
                <div style="width: 100%; display: inline-block;">
                  <a href="{{ route('page.login.ecomm') }}">
                    <p style="float: right;">Voltar</p>
                  </a>
                </div>
              </div>
            </center>
          </div>

          <div class="block-detal-login">
            <center>
              <a href="{{ route('ecomm') }}"><button class="back-button">Back to products</button></a>
            </center>
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
