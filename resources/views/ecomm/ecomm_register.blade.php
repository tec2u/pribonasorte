@extends('layouts.header_newsite')
@section('title', 'Pribonasorte | Finalize Shop')
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

    p.title-ecomm {
      font-size: 35px;
      font-weight: bold;
      color: #212121;
    }

    .box-panel {
      width: 100%;
      /* padding: 30px; */
      background: transparent;
      border-radius: 20px;
    }

    .band-title {
      margin-top: 100px;
    }

    p.title-order-panel {
      font-size: 25px;
      font-weight: bold;
    }

    p.title-number-panel {
      font-size: 20px;
    }

    p.number-order {
      font-size: 40px;
      margin-top: -20px;
      font-weight: bold;
    }

    img.img-order {
      width: 100px;
    }

    .text-list-product {
      font-size: 15px;
      font-weight: bold;
    }

    .button-detal {
      padding: 0px 30px;
      height: 40px;
      border-radius: 5px;
      color: #ffffff;
      background: #d26075;
      border: 0px;
      transition: 500ms;
    }

    .button-detal:hover {
      padding: 0px 30px;
      height: 40px;
      border-radius: 5px;
      color: #ffffff;
      background: #d26075;
      border: 0px;
      transition: 500ms;
    }

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

    p.title-ecomm {
      font-size: 35px;
      font-weight: bold;
      color: #212121;
    }

    .box-register {
      width: 68%;
      background: #ffffff;
      margin-right: 2%;
      float: left;
      display: inline-block;
      padding: 50px 30px;
      border-radius: 20px;
      box-shadow: 2px 2px 20px 2px rgba(0, 0, 0, 0.1);
    }

    .box-resume {
      width: 28%;
      padding: 50px 30px;
      float: left;
      margin-left: 2%;
      background: #ffffff;
      border-radius: 20px;
      display: inline-block;
      box-shadow: 2px 2px 20px 2px rgba(0, 0, 0, 0.1);
    }

    .mid-form {
      width: 48%;
      margin: 0px 1%;
      display: inline-block;
      float: left;
    }

    .low-form {
      width: 31.3%;
      margin: 0px 1%;
      display: inline-block;
      float: left;
    }

    .small-form {
      width: 23%;
      margin: 0px 1%;
      display: inline-block;
      float: left;
    }

    .min-form {
      width: 13%;
      margin: 0px 1%;
      display: inline-block;
      float: left;
    }

    .mic-form {
      width: 8%;
      margin: 0px 1%;
      display: inline-block;
      float: left;
    }

    .lowm-form {
      width: 43%;
      margin: 0px 1%;
      display: inline-block;
      float: left;
    }

    .form-input-finalize {
      width: 100%;
      height: 40px;
      margin-bottom: 15px;
      border: #cdcdcd solid 1px;
      background: #fafafa;
      padding: 0px 10px;
      border-radius: 5px;
      outline: none;
    }

    .button-type-user {
      width: 100%;
      height: 40px;
      border-radius: 5px;
      font-weight: bold;
      border: 0px;
      background: #d26075;
      margin-bottom: 15px;
      color: #ffffff;
      transition: 200ms;
    }

    .button-type-user:hover {
      width: 100%;
      height: 40px;
      border-radius: 5px;
      font-weight: bold;
      border: 0px;
      background: #3b0547;
      margin-bottom: 15px;
      color: #ffffff;
      transition: 500ms;
    }

    .bloco-juridc {
      width: 100%;
      display: inline-block;
    }

    p.subtitle_form {
      font-size: 18px;
      font-weight: bold;
      margin-left: 8px;
    }

    ul.brands {
      margin-left: -20px;
    }

    ul.brands li {
      list-style: none;
      margin-right: 20px;
      display: inline-block;
    }

    .box-products {
      width: 100%;
      margin-top: 20px;
      display: inline-block;
    }

    p.name-product-cart {
      color: #d26075;
      text-transform: uppercase;
      font-size: 13px;
      font-weight: bold;
    }

    .price-product-cart {
      margin-top: -10px;
    }

    p.text-total {
      font-weight: bold;
    }

    .camp-text-total {
      width: 50%;
      float: left;
      display: inline-block;
    }

    .camp-value-total {
      width: 50%;
      float: left;
      display: inline-block;
    }

    p.text-value {
      float: right;
    }

    .line-card {
      margin: 15px 0px;
    }

    button.btn-finalize-pay {
      width: 100%;
      height: 40px;
      border-radius: 5px;
      border: 0px;
      background: black;
      color: #ffffff;
      font-weight: bold;
      margin-top: 20px;
      transition: 200ms;
    }

    .line-title-registration {
      width: 100%;
      display: inline-block;
    }

    .band-registration {
      width: 50%;
      display: inline-block;
      float: left;
    }

    p.link-login {
      float: right;
      cursor: pointer;
      margin: 5px 10px 0px 0px;
    }

    .box-form-log {
      width: 50%;
      display: inline-block;
      float: left;
    }

    .box-form-log-info {
      width: 50%;
      padding-left: 60px;
      display: inline-block;
      float: left;
    }

    .button-log-form {
      width: 50%;
      height: 40px;
      border: 0px;
      background: #d26075;
      color: #ffffff;
      border-radius: 5px;
    }

    .box-form-form {
      width: 100%;
      padding: 5px;
      display: none;
    }

    .title-block-log {
      font-size: 18px;
      font-weight: bold;
    }

    .button-return-register {
      width: 50%;
      height: 40px;
      border: 0px;
      border-radius: 5px;
      background: #212121;
      color: #ffffff;
    }

    .button-edit {

      border-radius: 5px;
      background: #d26075;
      float: left;
      margin-left: 10px;
      color: #ffffff;
      font-weight: bold;
      text-transform: uppercase;

      width: fit-content;
      height: 40px;

    }

    /* NEW FORM */
    .box-general-form {
      width: 100%;
      display: inline-block;
    }

    .block-info-form {
      width: 98%;
      margin: 0px 1%;
      display: inline-block;
    }

    .header-block-form {
      width: 100%;
      display: inline-block;
      padding: 15px;
      background-color: #d26075;
      border-radius: 5px;
    }

    .header-block-form p {
      font-weight: bold;
      font-size: 16px;
      color: #ffffff;
      margin: 0px;
      padding: 0px;
    }

    .box-form-imputs {
      width: 100%;
      margin-top: 20px;
      display: inline-block;
    }

    .line-info-form {
      width: 100%;
      display: inline-block;
    }

    .column-form1 {
      float: left;
      width: 33.3%;
      display: inline-block;
    }

    .column-form2 {
      display: flex;
      justify-content: space-between;
    }

    .column-form3 {
      float: left;
      width: 33.3%;
      display: inline-block;
    }

    p.title-inputs {
      font-weight: bold;
      margin-top: 5px;
    }

    .new-inputs {
      width: 100%;
      height: 40px;
      border-radius: 5px;
      border: solid 1px #cdcdcd;
      padding-left: 10px;
      outline: none;
    }

    .new-inputs-address {
      width: 79%;
      margin-right: 1%;
      height: 40px;
      border-radius: 5px;
      border: solid 1px #cdcdcd;
      padding-left: 10px;
      float: left;
      outline: none;
    }

    .new-inputs-number {
      width: 19%;
      margin-left: 1%;
      height: 40px;
      border-radius: 5px;
      border: solid 1px #cdcdcd;
      padding-left: 10px;
      float: left;
      outline: none;
    }

    @media all and (min-width:2px) and (max-width:820px) {
      .box-register {
        width: 100%;
        background: #ffffff;
        margin-right: 0%;
        float: left;
        display: inline-block;
        padding: 30px 20px;
        border-radius: 20px;
        box-shadow: 2px 2px 20px 2px rgba(0, 0, 0, 0.1);
      }

      .box-resume {
        width: 100%;
        padding: 30px 20px;
        float: left;
        margin-left: 0%;
        background: #ffffff;
        border-radius: 20px;
        margin-top: 20px;
        display: inline-block;
        box-shadow: 2px 2px 20px 2px rgba(0, 0, 0, 0.1);
      }
    }

    .container-ecomm {
      background: transparent !important;
    }

    body {
      background-image: url(../img/fundo-newhome.jpg) !important;
    }
  </style>

  <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}" />

  <main id="background-primary">
    <section class="container-ecomm" style="margin-top: 50px;">

      <div class="raw">
        <div class="box-panel">

          <div class="raw">
            <div>
              <p style="font-weight: bold; font-size: 35px;width: 100%;text-align: center">CRIAR CONTA
              </p>

              @if ($errors->any())
                @foreach ($errors->all() as $error)
                  <div class="alert alert-danger">
                    {{ $error }}
                  </div>
                @endforeach
              @endif

              @if (session('error'))
                <div class="alert alert-danger">
                  {{ session('error') }}
                </div>
              @endif

              @if (session('message'))
                <div class="alert alert-success">
                  {{ session('message') }}
                </div>
              @endif

              <div class="alert alert-alert" id="messagePass">
              </div>

              @if (session('erro'))
                <div class="alert alert-danger">
                  {{ session('erro') }}
                </div>
              @endif
              <form action="{{ route('finalize.register.order') }}" style="mt-[30px]" method="POST" id="formCheckout">
                @csrf
                <div class="box-general-form">
                  <div class="block-info-form">
                    <div class="header-block-form">
                      <p>Registro</p>
                    </div>
                    {{--  --}}
                    <div class="box-form-imputs">
                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Nome *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="name" required type="text">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>
                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Sobrenome *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="last_name" required type="text">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Email *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs required" type="email" name="email" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Senha *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs"type="password" maxlength="16" name="password" id="pass1" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Confirmar senha *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" maxlength="16" name="checkpass" id="pass2" type="password"
                            required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}

                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Apelido *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="username" type="text" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        {{--<div class="column-form1">
                          <p class="title-inputs">Patrocinador</p>
                        </div>--}}
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          @if (isset($userBack))
                            <input class="new-inputs required" type="hidden" name="recommendation_user_id"
                              value="{{ $userBack }}" style="background-color: #5918683a; font-weight: bold;"
                              required>
                          @else
                            <input class="new-inputs required" type="hidden" name="recommendation_user_id" value='master'>
                          @endif
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Genero *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <select class="new-inputs" name="sex" required>
                            {{-- <option value="">None</option> --}}
                            <option value="1">Masculino</option>
                            <option value="2">Feminino</option>
                          </select>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>


                    </div>
                  </div>
                  {{--  --}}
                  <div class="block-info-form" style="margin-top: 20px">
                    <div class="header-block-form">
                      <p>Endereço de faturamento</p>
                    </div>
                    {{--  --}}
                    <div class="box-form-imputs">

                      {{-- LINE --}}
                      <div class="line-info-form" style="display: none;">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Birth *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="" type="text">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}

                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Endereço *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs-address" name="address" type="text" required>
                          <input class="new-inputs-number" placeholder="N°" name="number" type="number" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Cidade *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="city" type="text" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Cep *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="zip" type="number" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      {{-- <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">State *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="state" type="text">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div> --}}
                      {{-- LINE --}}

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs required">Celular *</p>
                        </div>

                        <div class="column-form2">
                          <input id="cell" onkeypress="allowNumeric(event)" type="cell"
                            class="form-control form-register @error('cell') is-invalid @enderror" placeholder="Cell (00) 000000000"
                            name="phone" required autocomplete="cell" tabindex="10">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                    </div>

                    <div style="display: none; margin-top: 20px;" id="form_address_shipping">
                      <div class="header-block-form">
                        <p>Endereço de entrega</p>
                      </div>
                      {{--  --}}
                      <div class="box-form-imputs">

                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">Endereço *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs-address" name="address2" type="text">
                            <input class="new-inputs-number" placeholder="N°" name="number2" type="number">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">Cidade *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" name="city2" type="text">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">Cep *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" name="zip2" type="number">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        {{-- <div class="line-info-form">
                          <div class="column-form1">
                            <p class="title-inputs">State *</p>
                          </div>
                          <div class="column-form2">
                            <input class="new-inputs" name="state2" type="text">
                          </div>
                          <div class="column-form3">

                          </div>
                        </div> --}}
                        {{-- LINE --}}
                      </div>
                    </div>

                    <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                      <input class="form-check-input" type="checkbox" checked id="select_spipping"
                        name="address_shipping">
                      <label class="form-check-label" for="select_spipping" style="color: #212121; font-size: 15px;">
                        O endereço de faturamento é o mesmo de entrega </label>
                    </div>
                    <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                      <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size: 15px;">
                        Eu concordo com os
                        <a href="{{ route('general_terms_conditions') }}" style="color: #212121;"><b>termos</b>
                        </a>
                      </label>
                    </div>
                  </div>
                  {{--  --}}
                  <div class="line-info-form" style="margin-top: 40px;">
                    <div style="width: 100%; display: flex;justify-content: center;gap:1rem;">
                      <div class="width: 100%; display: inline-block;">
                        <div çlass="width: 100%; display: flex; justify-content:space-between">
                            <button type="submit" class="button-edit">Registrar</button>
                        </div>
                      </div>
                      {{-- end form --}}
              </form>
              {{-- back button --}}
              <button class="button-edit" id="redirectLogin">Voltar</button>
            </div>
          </div>
        </div>
        {{-- out form --}}
      </div>
      </div>
      </div>
      </div>
    </section>
  </main>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
  <script type="text/javascript">
    // $(".cep").mask("00000-000");
    // $("#tel").mask("(00) 00000-0000");
    // $("#cpf").mask("000.000.000-00");
    // $("#cnpj").mask("00.000.000/0000-00");
  </script>
  <script src="{{ asset('js/intlTelInput.min.js') }}"></script>
  <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
  <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>

  <script>

    function allowNumeric(e) {
      var code = ('charCode' in e) ? e.charCode : e.keyCode;
      if (!(code > 47 && code < 58)) { // numeric (0-9)
        e.preventDefault();
      }
    }

    document.getElementById('redirectLogin').addEventListener('click', function() {
      window.location.href = "{{ route('page.login.ecomm') }}";
    });

    formCheckout = document.getElementById('formCheckout');
    messagePass = document.getElementById('messagePass').innerText;

    formCheckout.addEventListener('submit', (event) => {
      let url = "{!! route('finalize.register.order') !!}"
      pass1 = document.getElementById('pass1').value;
      pass2 = document.getElementById('pass2').value;

      if (pass1 !== pass2) {
        alert("Passwords must be the same");
        pass1 = '';
        pass2 = '';
        event.preventDefault();
        return;
      }

      formCheckout.action = url;
    })
  </script>

  <script>
    $(document).ready(function() {
      $("#btn-actv1").click(function(event) {
        event.preventDefault();
        $("#cpf_persona").fadeIn();
        $("#legal_person").hide();
        $("#cnpj_legal").hide();
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      $("#btn-actv1").click(function(event) {
        event.preventDefault();
        $("#cpf_persona").fadeIn();
        $("#legal_person").hide();
        $("#cnpj_legal").hide();
      });
    });

    $(document).ready(function() {
      $("#btn-actv2").click(function(event) {
        event.preventDefault();
        $("#legal_person").fadeIn();
        $("#cnpj_legal").fadeIn();
        $("#cpf_persona").hide();
      });
    });

    $(document).ready(function() {
      $(".link-login").click(function(event) {
        event.preventDefault();
        $(".box-form-form").fadeIn();
        $(".enter").fadeIn();
        $(".box-register-form").hide();
        $(".registers").hide();
      });
    });

    $(document).ready(function() {
      $("#new_address").click(function(event) {
        event.preventDefault();
        $("#box_new_address").toggle();
      });
    });

    $(document).ready(function() {
      $(".link-register").click(function(event) {
        event.preventDefault();
        $(".box-register-form").fadeIn();
        $(".registers").fadeIn();
        $(".box-form-form").hide();
        $(".enter").hide();
      });
    });

    $(document).ready(function() {
      $("#select_spipping").click(function(event) {
        $("#form_address_shipping").toggle();
      });
    });
  </script>

  <script>
    'use strict';

    const preencherFormulario = (endereco) => {

      document.getElementById('campo_endereco').value = endereco.logradouro;
      document.getElementById('campo_bairro').value = endereco.bairro;
      document.getElementById('campo_cidade').value = endereco.localidade;
      document.getElementById('campo_estado').value = endereco.uf;
    }

    const pesquisarCep = async () => {

      const cep = document.getElementById('campo_cep').value;
      const url = `http://viacep.com.br/ws/${cep}/json/`;
      const dados = await fetch(url);
      const endereco = await dados.json();

      if (endereco.hasOwnProperty('erro')) {
        return;
      } else {
        preencherFormulario(endereco);
      }
    }

    document.getElementById('campo_cep').addEventListener('focusout', pesquisarCep);
  </script>

  <script src="/js/function-mask.js"></script>
  <script>
    'use strict';

    const campos = document.querySelectorAll('.required');
    // const spans  = document.querySelectorAll('.span_required');

    function setError(index) {
      // campos[index].style.border = '2px solid orange';
      // spans[index].style.display = 'block';
    }

    function removeError(index) {
      // campos[index].style.border = '';
      // spans[index].style.display = 'none';
    }

    function desabledFalseButton() {

      document.getElementById('bt_submit').disabled = false;
      document.getElementById('bt_submit').style.background = "green";
    }

    function desabledTrueButton() {

      document.getElementById('bt_submit').disabled = true;
      document.getElementById('bt_submit').style.background = "black";
    }

    function nomeValidate() {

      if (campos[0].value.length < 3) {
        setError(0);
        desabledTrueButton();
      } else {
        removeError(0);
        desabledFalseButton();
      }
    }

    function sobrenomeValidate() {

      if (campos[1].value.length < 3) {
        setError(1);
        desabledTrueButton();
      } else {
        removeError(1);
        desabledFalseButton();
      }
    }

    function cepValidate() {

      if (campos[2].value.length < 3) {
        setError(2);
        desabledTrueButton();
      } else {
        removeError(2);
        desabledFalseButton();
      }
    }

    jQuery(function($) {
      $("#birth").mask("99/99/9999", {
        placeholder: "dd/mm/aaaa"
      });
      $("#code").mask("999999");
    });
  </script>
@endsection

