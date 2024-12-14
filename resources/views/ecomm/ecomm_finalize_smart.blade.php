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
      text-align: center;
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
      background: #f0f0f0;
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
      background: #27032f;
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
      background: #d26075;
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
      color: #27032f;
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

    p.link-loginn {
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

    .line_row {
      width: 100%;
      display: inline-block;
      margin-bottom: 30px;
    }

    ul.line_bts_address {
      /* margin-left: 30px; */
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 2rem;
      margin: 0 !important;
      padding: 0 !important;
    }

    ul.line_bts_address li {
      display: block;
      /* margin-right: 10px; */
      list-style: none;
      width: fit-content;
    }

    .btn_address {
      padding: .5rem 2rem !important;
      margin: 0 !important;
      height: 40px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
    }

    p.text_my_address {
      font-size: 18px;
      color: #d26075;
      margin-top: 30px;
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

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
    }

    /* CSS for the modal box */
    .modal-box {
      position: relative;
      margin: 0 auto;
      height: 720px;
      display: none;
    }

    #close-modal-button {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 2;
    }

    .ppl-parcelshop-map {
      height: 100%;
      max-height: 640px;
    }
  </style>

  {{-- <main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%; height: 50vh; background: #d26075;">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <!-- <center><p class="text-title">Vitamin and Minerals</p></center> -->
          </div>
        </div>
      </div>
    </section>
  </main> --}}

  <main id="background-primary" style="padding-top: 7rem;">
    <section class="container-ecomm">

      <div class="raw">
        <div class="band-title">
          <p class="title-ecomm">Finalize Order</p>
        </div>
      </div>

      {{-- <div class="col-12 box-register" id="box_pickup" style="display: blick; width:100%; height: ;">
        <div id="ppl-parcelshop-map"></div>
      </div> --}}
      {{-- <button id="modal-button">Open Modal</button> --}}

      <!-- Modal overlay -->
      <div class="modal-overlay" id="modal-overlay"></div>

      <!-- Modal box -->
      <div class="modal-box" style="background-color: #f0f0f0;">
        <div id="ppl-parcelshop-map"></div>
        <h5 id="name_ppl_selected" style="display: none; background-color: #f0f0f0; font-size:2rem; color:green;">Selected
        </h5>
        <button id="close-modal-button" class="btn_address">Close Modal</button>
      </div>



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

      <div class="raw">
        <div class="box-register">
          @if (isset($user))
            <div class="line-title-registration registers">
              @if (!isset($user->address2) and empty($user->address2))
                <div class="band-registration">
                  <p class="subtitle_form">
                    Your package will be shipped to this address: {{ $user->address }}, {{ $user->neighborhood }} -
                    {{ $user->number }} {{ $user->zip }}</p>. If you want to change destination you can choose below

                </div>
              @endif
              <div class="band-registration">
                <p class="link-loginn">
                  <a href="{{ route('orders.settings.ecomm') }}" style="color: inherit">
                    <button class="btn_address">
                      Change delivery point
                    </button>
                  </a>
                </p>
              </div>
            </div>

            <div class="line_row">
              {{-- <center> --}}
              <ul class="line_bts_address">
                <li><button id="another_address" onclick="alterarValor('home1')" class="btn_address">My Address</button>
                </li>
                <li><button id="my_address" onclick="alterarValor('home2')" class="btn_address">Another Address</button>
                </li>
                @if (isset($priceShippingPickup) && $priceShippingPickup > 0)
                  <li onclick="alterarValor('pickup')"><button id="pickup" class="btn_address">Pickup</button></li>
                @endif
              </ul>
              {{-- </center> --}}
            </div>

            <div id="box_another_address_pickup" style="display: none">
              <center>
                <p id="text_my_address_pickup" class="text_my_address"></p>
              </center>
            </div>

            {{-- ADDRESS SHIPPING --}}
            <div id="box_my_address" style="display: none;">
              {{-- <form action="{{ route('registered.address.secondary') }}" method="POST" id="form_new_address"> --}}
              @csrf
              <div class="row-form">
                <div class="small-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="first name" id=""
                    type="text" name="first_name">
                </div>
                <div class="small-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="last name" id=""
                    type="text" name="last_name">
                </div>
                <div class="small-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="Phone +000 00000000" id="tel"
                    type="number" name="phone">
                </div>
                <div class="min-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="ZIP code" oninput="cepValidate()"
                    aria-label="CEP" id="campo_cep" type="number" name="zip">
                </div>
                <div class="lowm-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="Address" id="campo_endereco"
                    type="text" name="address">
                </div>
                <div class="min-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="N° residence" type="number"
                    name="number">
                </div>
              </div>



              <div class="row-form">
                <div class="small-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="Complement" type="text"
                    name="complement">
                </div>
                <div class="min-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="Area" id="campo_bairro"
                    type="text" name="neighborhood">
                </div>
                <div class="small-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="City" id="campo_cidade"
                    type="text" name="city">
                </div>
                {{-- <div class="mic-form">
                  <input class="form-input-finalize" form="formCheckout" placeholder="State" id="campo_estado"
                    type="text" name="state">
                </div> --}}
                <div class="small-form">
                  <select class="form-input-finalize" form="formCheckout" name="country" id="selectCountry"
                    onchange="atualizaPreco()">
                    <option value="" selected></option>
                    @foreach ($allCountry as $item)
                      <option value="{{ $item->country }}">{{ $item->country }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              {{-- </form> --}}
            </div>

            <div id="box_another_address">
              <center>
                <p class="text_my_address">Your order will be sent to the address registered above!</p>
              </center>
            </div>
          @else
            <div class="line-title-registration registers">
              <div class="band-registration">
                <p class="subtitle_form">Registration</p>
              </div>
              <div class="band-registration">
                <p class="link-login">I have an account</p>
              </div>
            </div>

            <div class="line-title-registration enter" style="display: none;">
              <div class="band-registration">
                <p class="subtitle_form">Enter</p>
              </div>
              <div class="band-registration">
              </div>
            </div>

            <div class="box-form-form">
              <div class="box-form-log">
                <form action="{{ route('log.user.ecomm') }}" method="POST" style="margin-bottom: 30px;">
                  @csrf
                  <input class="form-input-finalize" placeholder="E-mail" type="text" name="email" required>
                  {{--  --}}
                  <input class="form-input-finalize" placeholder="Senha" type="password" maxlength="16"
                    name="password" required>
                  {{--  --}}
                  <button type="submit" class="button-log-form">Login</button>
                </form>
              </div>
              <div class="box-form-log-info">
                {{-- <center> --}}
                <p class="title-block-log">Create an account</p>
                <p>If you don't have an account yet, register now</p>
                <button class="button-return-register link-register">New account</button>
                {{-- </center> --}}
              </div>
            </div>
          @endif
          <div class="box-register-form">
            <form action="{{ route('finalize.register.order.comgate') }}" style="mt-[30px];" method="POST"
              id="formCheckout">
              @csrf
              @if (isset($user))
                <input type="text" name="id_user_login" value="{{ $user->id }}" style="display:none">
              @endif
              @if (!isset($user))

                {{-- NOVO FORMULÁRIO --}}
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

                @if (!$allowedRegister)
                  <div class="alert alert-danger">
                    Unfortunately, we do not ship to your country yet.
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

                <div class="box-general-form">
                  <div class="block-info-form">
                    <div class="header-block-form">
                      <p>Registration</p>
                    </div>
                    {{--  --}}
                    <div class="box-form-imputs">
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Login email *</p>
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
                          <p class="title-inputs required">Password *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs"type="password" maxlength="16" name="password" id="pass1"
                            required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Confirm password *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" maxlength="16" name="checkpass" id="pass2" type="text"
                            required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Phone *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input id="cell" onkeypress="allowNumeric(event)" type="cell"
                            class="form-control form-register @error('cell') is-invalid @enderror" placeholder="Cell"
                            name="phone" required autocomplete="cell" tabindex="10">
                          <input type="hidden" name="countryCodeCell" id="countryCodeCell" value="1">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Username *</p>
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
                        <div class="column-form1">
                          <p class="title-inputs">Referral</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          @if (isset($userBack))
                            <input class="new-inputs required" type="text" name="recommendation_user_id"
                              value="{{ $userBack }}" style="background-color: #5918683a; font-weight: bold;"
                              required>
                          @else
                            <input class="new-inputs required" type="text" name="recommendation_user_id" required>
                          @endif
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
                      <p>Billing Information</p>
                    </div>
                    {{--  --}}
                    <div class="box-form-imputs">
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">First name *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="name" type="text">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Last name *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="last_name" type="text">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
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
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Male/Famale *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <select class="new-inputs" name="sex" required>
                            <option value="1">Male</option>
                            <option value="2">Famale</option>
                          </select>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Address *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs-address" name="address" type="text">
                          <input class="new-inputs-number" placeholder="N°" name="number" type="number">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>

                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">City *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="city" type="text">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Postcode *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="zip" type="text">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">State *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="state" type="text">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Country *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <select class="new-inputs" name="country" required>
                            @foreach ($allCountry as $item)
                              <option value="{{ $item->country }}">{{ $item->country }}</option>
                            @endforeach
                          </select>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                    </div>

                    <div style="display: none; margin-top: 20px;" id="form_address_shipping">
                      <div class="header-block-form">
                        <p>Address shipping</p>
                      </div>
                      {{--  --}}
                      <div class="box-form-imputs">
                        {{-- LINE --}}
                        <div class="line-info-form" style="display: none;">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">First name *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" name="first_name" type="text">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form" style="display: none;">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">Last name *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" name="last_name" type="text">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">Address *</p>
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
                            <p class="title-inputs">City *</p>
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
                            <p class="title-inputs">Postcode *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" name="zip2" type="text">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">State *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" name="state2" type="text">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">Country *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <select class="new-inputs" name="country2" required>
                              @foreach ($allCountry as $item)
                                <option value="{{ $item->country }}">{{ $item->country }}</option>
                              @endforeach
                            </select>
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                      <input class="form-check-input" type="checkbox" checked id="select_spipping"
                        name="address_shipping">
                      <label class="form-check-label" for="select_spipping" style="color: #212121; font-size: 15px;">
                        this will be my delivery address
                      </label>
                    </div>

                    <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                      <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
                        I Agree with
                        <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                          Terms and Agreement</a>
                      </label>
                    </div>
                  </div>
                  {{--  --}}
                  <div class="line-info-form" style="margin-top: 40px;">
                    <div style="width: 70%; display: inline-block;">
                      <div class="width: 100%; display: inline-block;">
                        <div çlass="width: 100%; display: flex; justify-content:space-between">
                          @if ($allowedRegister)
                            <button type="submit" class="button-edit">Register Now</button>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                {{-- out form --}}

                <div style="display: none;">
                  <div class="row-form">
                    <div class="mid-form">
                      <input class="form-input-finalize required" placeholder="Name" aria-label="Name"
                        oninput="nomeValidate()" type="text" name="name" required>
                    </div>
                    <div class="mid-form">
                      <input class="form-input-finalize required" placeholder="E-mail" aria-label="Sobrenome"
                        oninput="sobrenomeValidate()" type="text" name="email" required>
                    </div>
                  </div>

                  <div class="row-form">
                    <div class="mid-form" style="width: 30%">
                      <input class="form-input-finalize required" placeholder="Alter Password" type="password"
                        maxlength="16" name="password" id="pass1" required>
                    </div>
                    <div class="mid-form" style="width: 30%">
                      <input class="form-input-finalize required" type="password" maxlength="16"
                        placeholder="Confirm your password" name="checkpass" id="pass2" required>
                    </div>
                    <div class="mid-form" style="width: 34%">
                      <input class="form-input-finalize required" placeholder="Recommendation Username" type="text"
                        name="recommendation_user_id" required>
                    </div>
                  </div>

                  <div class="row-form" style="display: none;">
                    <div class="mid-form">
                      <input class="form-input-finalize required" placeholder="Alter Password" type="password"
                        maxlength="16" name="password" id="pass1" required>
                    </div>
                    <div class="mid-form">
                      <input class="form-input-finalize required" type="password" maxlength="16"
                        placeholder="Confirm your password" name="checkpass" id="pass2" required>
                    </div>
                  </div>

                  <div class="row-form">
                    <div class="mid-form">
                      <button type="button" id="btn-actv1" class="button-type-user">Physical Person</button>
                    </div>
                    <div class="mid-form">
                      <button type="button" id="btn-actv2" class="button-type-user">Legal Person</button>
                    </div>
                  </div>

                  <div class="row-form" style="display: none;" id="legal_person">
                    <div class="mid-form">
                      <input class="form-input-finalize" placeholder="Corporate Reason" type="text"
                        name="corporate_reason">
                    </div>
                    <div class="mid-form">
                      <input class="form-input-finalize" placeholder="Fantasy Name" type="text"
                        name="fantasy_name">
                    </div>
                  </div>

                  <div class="row-form">
                    <div class="band-registration">

                      <p class="subtitle_form">{{ $user->address }}, {{ $user->neighborhood }} - {{ $user->number }}
                        <br />
                        {{ $user->zip }}
                      </p>
                    </div>
                  </div>

                  <div class="row-form">

                    <div class="low-form" id="cpf_persona">
                      <input class="form-input-finalize required" placeholder="identity card" id="cpf"
                        type="text" name="identity_card">
                    </div>

                    <div class="low-form" style="display: none;" id="cnpj_legal">
                      <input class="form-input-finalize" placeholder="Id corporate" id="cnpj" type="text"
                        name="id_corporate">
                    </div>

                    {{-- <div class="low-form">
                            <input class="form-input-finalize" placeholder="Birth" id="data" type="date"
                            name="birth" required>
                        </div> --}}
                    <div class="low-form">
                      <select class="form-input-finalize" name="sex" required>
                        <option value="1">Masculine</option>
                        <option value="2">Feminine</option>
                      </select>
                    </div>
                  </div>
                  <div class="row-form">
                    <div class="small-form">
                      <input class="form-input-finalize" placeholder="Phone" id="tel" type="text"
                        name="phone" required>
                    </div>
                    <div class="min-form">
                      <input class="form-input-finalize cep" placeholder="ZIP code" oninput="cepValidate()"
                        aria-label="CEP" id="campo_cep" type="text" name="zip" required>
                    </div>
                    <div class="lowm-form">
                      <input class="form-input-finalize" placeholder="Address" id="campo_endereco" type="text"
                        name="address" required>
                    </div>
                    <div class="min-form">
                      <input class="form-input-finalize" placeholder="N°" type="text" name="number" required>
                    </div>
                  </div>
                  <div class="row-form">
                    {{-- <div class="small-form">
                            <input class="form-input-finalize" placeholder="Complement" type="text" name="complement">
                        </div> --}}
                    {{-- <div class="min-form">
                            <input class="form-input-finalize" placeholder="Neighborhood" id="campo_bairro" type="text"
                            name="neighborhood" required>
                        </div> --}}
                    <div class="small-form">
                      <input class="form-input-finalize" placeholder="City" id="campo_cidade" type="text"
                        name="city" required>
                    </div>
                    <div class="mic-form">
                      <input class="form-input-finalize" placeholder="UF" id="campo_estado" type="text"
                        name="state" required>
                    </div>
                    <div class="small-form">
                      <select class="form-input-finalize" name=country required>
                        @foreach ($allCountry as $item)
                          <option value="{{ $item->country }}">{{ $item->country }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              @endif
              <!-- CONTINUE FORM -->
          </div>

          <div style="width: 100%; display: inline-block;">
            <p class="subtitle_form" style="margin-top: 30px;">Payment methods</p>
            <div class="">
              {{-- <ul class="brands">
                <li><img style="width: 50px;" src="/images/mastercard-logo.png"></li>
                <li><img style="width: 50px;" src="/images/Diners-Club-Logo.png"></li>
                <li><img style="width: 50px;" src="/images/American-Express-logo.png"></li>
                <li><img style="width: 50px;" src="/images/visa-logo.png"></li>
              </ul> --}}
              {{-- <ul class="brands">
                @foreach ($metodos as $item)
                  <li><img style="width: 100px;" src="{{ $item->logo }}"></li>
                @endforeach
              </ul> --}}
              <ul style="margin-top: 5px;" class="brands">
                <li><img style="width: 90px;" src="/img/comgatepay.png"></li>
                <li><img style="width: 50px;" src="/img/applepay.png"></li>
                <li><img style="width: 50px;" src="/img/googlepay.png"></li>
                <li><img style="width: 50px;" src="/img/mastercardpay.png"></li>
                <li><img style="width: 50px;" src="/img/visapay.png"></li>
              </ul>
            </div>
          </div>
          <a href="{{ route('ecomm') }}" style="float: right; color: white" class="btn_address">
            Back to products
          </a>
        </div>

        <div class="box-resume">
          <p class="subtitle_form" style="text-align: center">Your shoppings</p>
          <div class="box-products">
            <p class="name-product-cart">{{ $count_order }} products in the bag</p>
            @if (isset($list_product) and !empty($list_product))
              @foreach ($list_product as $pro)
                @php
                  $products_list = Illuminate\Support\Facades\DB::select("SELECT * FROM products WHERE id = '$pro'");
                @endphp

                <img style="width: 50px; margin-right: 5px; float: left;"
                  src="/img/products/{{ $products_list[0]->img_1 }}">
              @endforeach
            @endif
            <hr class="line-card">
          </div>

          <div class="box-total">
            <div class="raw">
              <div class="camp-text-total">
                <p class="text-total">QV:</p>
              </div>

              <div class="camp-value-total">
                <p class="text-value" id="pq_shipping">{{ number_format($qv, 2, ',', '.') }}</p>
                <input class="text-value" name="qv" value="" id="qv" style="display: none" />
              </div>
            </div>

            <div class="raw">
              <div class="camp-text-total">
                <p class="text-total">Products:</p>
              </div>

              <div class="camp-value-total">
                <p class="text-value">{{ $count_order }}</p>
              </div>
            </div>
            <div class="raw">
              <div class="camp-text-total">
                <p class="text-total">Shipping:</p>
              </div>

              <div class="camp-value-total">
                <p class="text-value" id="p_shipping">€{{ $format_shipping }}</p>
                <input class="text-value" name="total_shipping" value="{{ $format_shipping }}" id="total_shipping"
                  style="display: none" />
              </div>
            </div>

            <div class="raw">
              <div class="camp-text-total">
                <p class="text-total">VAT:</p>
              </div>

              <div class="camp-value-total">
                <p class="text-value">€<span id="span_vat">{{ $total_VAT }}</span></p>
                <input class="text-value" id="input_vat" name="total_vat" value="{{ $total_VAT }}"
                  style="display: none" />
              </div>
            </div>
            <div class="raw">
              <div class="camp-text-total">
                <p class="text-total">Total price:</p>
              </div>

              <div class="camp-value-total">
                <p class="text-value" id="value_order">€{{ $format_price }}</p>
                <input class="text-value" id="request_price" name="price" value="{{ $format_price }}"
                  style="display: none" />
              </div>
            </div>

            <input type="text" name="method_shipping" value="" style="display: none;"
              id="radio_method_payment">

            <div style="display: none">
              <input type="text" name="id_ppl" id="id_ppl" style="display: none">
              <input type="text" name="accessPointType" id="accessPointType" style="display: none">
              <input type="text" name="code" id="code" style="display: none">
              <input type="text" name="dhlPsId" id="dhlPsId" style="display: none">
              <input type="text" name="depot" id="depot" style="display: none">
              <input type="text" name="depotName" id="depotName" style="display: none">
              <input type="text" name="name_ppl" id="name_ppl" style="display: none">
              <input type="text" name="street_ppl" id="street_ppl" style="display: none">
              <input type="text" name="city_ppl" id="city_ppl" style="display: none">
              <input type="text" name="zipCode_ppl" id="zipCode_ppl" style="display: none">
              <input type="text" name="country_ppl" id="country_ppl" style="display: none">
              <input type="text" name="parcelshopName" id="parcelshopName" style="display: none">
            </div>

            {{-- <div class="raw" style="display: flex; justify-content: space-between; font-size:.8rem">
              <p class="text-total">Smartshipping:</p>
              <div class="form-check">
                <input class="form-check-input" type="radio" value="1" name="smartshipping"
                  id="flexRadioDefault1" required>
                <label class="form-check-label" for="flexRadioDefault1">
                  Yes
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" value="0" name="smartshipping"
                  id="flexRadioDefault2" required>
                <label class="form-check-label" for="flexRadioDefault2">
                  No
                </label>
              </div>
            </div> --}}
          </div>
          <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
            <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
              I Agree with
              <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                Terms and Agreement</a>
            </label>
          </div>
          {{-- @if (isset($user)) --}}
          {{-- <span>Choose method</span>
          <select name="methodPayment" id="selectMethod" onchange="changeMetodo(event)" style="font-size: 1rem"
            class="form-control form-control-lg @error('payment') is-invalid @enderror">
            <option value="">Choose a method</option>
            @foreach ($metodos as $item)
              <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
          </select> --}}
          @if ($qv >= 50)
            <button class="btn_address" style="margin: 0;width:100%;opacity: 1" onclick="submitSmart()"
              id="bt_submit_smart">Smartshipping</button>
          @endif
          {{-- <br>
          <button class="btn-finalize-pay" onclick="submitMethod()" disabled style="opacity: .5"
            id="bt_submit">Pay</button>
          <br>
          <span>Pay with crypto</span>
          <select name="payment" id="selectCrypto" style="font-size: 1rem"
            class="form-control form-control-lg @error('payment') is-invalid @enderror">
            <option value=""> Choose a coin</option>
            <option value="BTC">Buy Now (BTC) </option>
          </select>
          <button class="btn-finalize-pay" id="bt_submit_crypt" disabled onclick="submitCrypto()">Pay with
            crypto</button> --}}
          <!-- FINAL FORM -->
          {{-- @else
            <button class="btn-finalize-pay" style="opacity: .5" disabled>Please login</button>
          @endif --}}
          </form>
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
    // $("#data").mask("00/00/0000");
    // $("#cnpj").mask("00.000.000/0000-00");
  </script>
  <script>
    // Get the modal elements
    var modalOverlay = document.querySelector(".modal-overlay");
    var modalBox = document.querySelector(".modal-box");
    var closeButton = document.querySelector("#close-modal-button");

    // Get the button that opens the modal
    var modalButton = document.querySelector("#pickup");

    // When the user clicks the button, show the modal
    modalButton.addEventListener("click", function() {
      modalOverlay.style.display = "block";
      modalBox.style.display = "block";

      // Create a link element to load the main.css file
      var link = document.createElement("link");
      link.rel = "stylesheet";
      link.href = "https://www.ppl.cz/sources/map/main.css";

      // Create a script element to load the main.js file
      var script = document.createElement("script");
      script.src = "https://www.ppl.cz/sources/map/main.js";

      // Add the script+href link to the document head
      document.head.appendChild(link);
      document.head.appendChild(script);
    });

    // When the user clicks the close button, hide the modal
    modalOverlay.addEventListener("click", function() {
      modalOverlay.style.display = "none";
      modalBox.style.display = "none";
    });

    closeButton.addEventListener("click", function() {
      modalOverlay.style.display = "none";
      modalBox.style.display = "none";
    });
  </script>
  <script>
    function calcularVAT(country_code) {
      let maiorestaxas = @json($todosVats);
      let tax1add = 0;

      if (country_code === 'AT' || country_code === 'Austria') {
        tax1add = maiorestaxas.Austria;
      } else if (country_code === 'BE' || country_code === 'Belgium') {
        tax1add = maiorestaxas.Belgium;
      } else if (country_code === 'BG' || country_code === 'Bulgaria') {
        tax1add = maiorestaxas.Bulgaria;
      } else if (country_code === 'CY' || country_code === 'Cyprus') {
        tax1add = maiorestaxas.Cyprus;
      } else if (country_code === 'CZ' || country_code === 'Czech Republic') {
        tax1add = maiorestaxas["Czech Republic"];
      } else if (country_code === 'DE' || country_code === 'Germany') {
        tax1add = maiorestaxas.Germany
      } else if (country_code === 'DK' || country_code === 'Denmark') {
        tax1add = maiorestaxas.Denmark
      } else if (country_code === 'EE' || country_code === 'Estonia') {
        tax1add = maiorestaxas.Estonia
      } else if (country_code === 'EL' || country_code === 'Greece') {
        tax1add = maiorestaxas.Greece
      } else if (country_code === 'ES' || country_code === 'Spain') {
        tax1add = maiorestaxas.Spain
      } else if (country_code === 'FI' || country_code === 'Finland') {
        tax1add = maiorestaxas.Finland
      } else if (country_code === 'FR' || country_code === 'France') {
        tax1add = maiorestaxas.France
      } else if (country_code === 'HR' || country_code === 'Croatia') {
        tax1add = maiorestaxas.Croatia
      } else if (country_code === 'HU' || country_code === 'Hungary') {
        tax1add = maiorestaxas.Hungary
      } else if (country_code === 'IE' || country_code === 'Ireland') {
        tax1add = maiorestaxas.Ireland
      } else if (country_code === 'IT' || country_code === 'Italy') {
        tax1add = maiorestaxas.Italy
      } else if (country_code === 'LT' || country_code === 'Lithuania') {
        tax1add = maiorestaxas.Lithuania
      } else if (country_code === 'LU' || country_code === 'Luxembourg') {
        tax1add = maiorestaxas.Luxembourg
      } else if (country_code === 'LV' || country_code === 'Latvia') {
        tax1add = maiorestaxas.Latvia
      } else if (country_code === 'MT' || country_code === 'Malta') {
        tax1add = maiorestaxas.Malta
      } else if (country_code === 'NL' || country_code === 'Netherlands') {
        tax1add = maiorestaxas.Netherlands
      } else if (country_code === 'PL' || country_code === 'Poland') {
        tax1add = maiorestaxas.Poland
      } else if (country_code === 'PT' || country_code === 'Portugal') {
        tax1add = maiorestaxas.Portugal
      } else if (country_code === 'RO' || country_code === 'Romania') {
        tax1add = maiorestaxas.Romania
      } else if (country_code === 'SE' || country_code === 'Sweden') {
        tax1add = maiorestaxas.Sweden
      } else if (country_code === 'SI' || country_code === 'Slovenia') {
        tax1add = maiorestaxas.Slovenia
      } else if (country_code === 'SK' || country_code === 'Slovakia') {
        tax1add = maiorestaxas.Slovakia
      } else if (country_code === 'XI' || country_code === 'Unknown Country') {
        tax1add = 0
      } else {
        tax1add = 0; // Valor padrão se o país não for encontrado
      }

      return tax1add;
    }

    function precoPickup(country_code) {
      let maiorestaxas = @json($todosFretePickup);

      let shipping = 0;
      let totalTaxAdd = 0;
      let tax1add = 0;
      let taxadd = 0;

      if (country_code === 'AT' || country_code === 'Austria') {
        tax1add = maiorestaxas.AT;
      } else if (country_code === 'BE' || country_code === 'Belgium') {
        tax1add = maiorestaxas.BE;
      } else if (country_code === 'BG' || country_code === 'Bulgaria') {
        tax1add = maiorestaxas.BG;
      } else if (country_code === 'CY' || country_code === 'Cyprus') {
        tax1add = maiorestaxas.CY;
      } else if (country_code === 'CZ' || country_code === 'Czech Republic') {
        tax1add = maiorestaxas.CZ;
      } else if (country_code === 'DE' || country_code === 'Germany') {
        tax1add = maiorestaxas.DE
      } else if (country_code === 'DK' || country_code === 'Denmark') {
        tax1add = maiorestaxas.DK
      } else if (country_code === 'EE' || country_code === 'Estonia') {
        tax1add = maiorestaxas.EE
      } else if (country_code === 'EL' || country_code === 'Greece') {
        tax1add = maiorestaxas.EL
      } else if (country_code === 'ES' || country_code === 'Spain') {
        tax1add = maiorestaxas.ES
      } else if (country_code === 'FI' || country_code === 'Finland') {
        tax1add = maiorestaxas.FI
      } else if (country_code === 'FR' || country_code === 'France') {
        tax1add = maiorestaxas.FR
      } else if (country_code === 'HR' || country_code === 'Croatia') {
        tax1add = maiorestaxas.HR
      } else if (country_code === 'HU' || country_code === 'Hungary') {
        tax1add = maiorestaxas.HU
      } else if (country_code === 'IE' || country_code === 'Ireland') {
        tax1add = maiorestaxas.IE
      } else if (country_code === 'IT' || country_code === 'Italy') {
        tax1add = maiorestaxas.IT
      } else if (country_code === 'LT' || country_code === 'Lithuania') {
        tax1add = maiorestaxas.LT
      } else if (country_code === 'LU' || country_code === 'Luxembourg') {
        tax1add = maiorestaxas.LU
      } else if (country_code === 'LV' || country_code === 'Latvia') {
        tax1add = maiorestaxas.LV
      } else if (country_code === 'MT' || country_code === 'Malta') {
        tax1add = maiorestaxas.MT
      } else if (country_code === 'NL' || country_code === 'Netherlands') {
        tax1add = maiorestaxas.NL
      } else if (country_code === 'PL' || country_code === 'Poland') {
        tax1add = maiorestaxas.PL
      } else if (country_code === 'PT' || country_code === 'Portugal') {
        tax1add = maiorestaxas.PT
      } else if (country_code === 'RO' || country_code === 'Romania') {
        tax1add = maiorestaxas.RO
      } else if (country_code === 'SE' || country_code === 'Sweden') {
        tax1add = maiorestaxas.SE
      } else if (country_code === 'SI' || country_code === 'Slovenia') {
        tax1add = maiorestaxas.SI
      } else if (country_code === 'SK' || country_code === 'Slovakia') {
        tax1add = maiorestaxas.SK
      } else if (country_code === 'XI' || country_code === 'Unknown Country') {
        tax1add = 0.2
      } else {
        tax1add = 0; // Valor padrão se o país não for encontrado
      }

      // tax1add = parseFloat(tax1add);

      console.log(tax1add);

      return tax1add;
    }

    function addNewTax(country_code) {

      let maiorestaxas = @json($todosFreteCasa);

      // console.log(maiorestaxas["Czech Republic"]);
      let shipping = 0;
      let totalTaxAdd = 0;
      let tax1add = 0;
      let taxadd = 0;

      if (country_code === 'AT' || country_code === 'Austria') {
        tax1add = maiorestaxas.Austria;
      } else if (country_code === 'BE' || country_code === 'Belgium') {
        tax1add = maiorestaxas.Belgium;
      } else if (country_code === 'BG' || country_code === 'Bulgaria') {
        tax1add = maiorestaxas.Bulgaria;
      } else if (country_code === 'CY' || country_code === 'Cyprus') {
        tax1add = maiorestaxas.Cyprus;
      } else if (country_code === 'CZ' || country_code === 'Czech Republic') {
        tax1add = maiorestaxas["Czech Republic"];
      } else if (country_code === 'DE' || country_code === 'Germany') {
        tax1add = maiorestaxas.Germany
      } else if (country_code === 'DK' || country_code === 'Denmark') {
        tax1add = maiorestaxas.Denmark
      } else if (country_code === 'EE' || country_code === 'Estonia') {
        tax1add = maiorestaxas.Estonia
      } else if (country_code === 'EL' || country_code === 'Greece') {
        tax1add = maiorestaxas.Greece
      } else if (country_code === 'ES' || country_code === 'Spain') {
        tax1add = maiorestaxas.Spain
      } else if (country_code === 'FI' || country_code === 'Finland') {
        tax1add = maiorestaxas.Finland
      } else if (country_code === 'FR' || country_code === 'France') {
        tax1add = maiorestaxas.France
      } else if (country_code === 'HR' || country_code === 'Croatia') {
        tax1add = maiorestaxas.Croatia
      } else if (country_code === 'HU' || country_code === 'Hungary') {
        tax1add = maiorestaxas.Hungary
      } else if (country_code === 'IE' || country_code === 'Ireland') {
        tax1add = maiorestaxas.Ireland
      } else if (country_code === 'IT' || country_code === 'Italy') {
        tax1add = maiorestaxas.Italy
      } else if (country_code === 'LT' || country_code === 'Lithuania') {
        tax1add = maiorestaxas.Lithuania
      } else if (country_code === 'LU' || country_code === 'Luxembourg') {
        tax1add = maiorestaxas.Luxembourg
      } else if (country_code === 'LV' || country_code === 'Latvia') {
        tax1add = maiorestaxas.Latvia
      } else if (country_code === 'MT' || country_code === 'Malta') {
        tax1add = maiorestaxas.Malta
      } else if (country_code === 'NL' || country_code === 'Netherlands') {
        tax1add = maiorestaxas.Netherlands
      } else if (country_code === 'PL' || country_code === 'Poland') {
        tax1add = maiorestaxas.Poland
      } else if (country_code === 'PT' || country_code === 'Portugal') {
        tax1add = maiorestaxas.Portugal
      } else if (country_code === 'RO' || country_code === 'Romania') {
        tax1add = maiorestaxas.Romania
      } else if (country_code === 'SE' || country_code === 'Sweden') {
        tax1add = maiorestaxas.Sweden
      } else if (country_code === 'SI' || country_code === 'Slovenia') {
        tax1add = maiorestaxas.Slovenia
      } else if (country_code === 'SK' || country_code === 'Slovakia') {
        tax1add = maiorestaxas.Slovakia
      } else if (country_code === 'XI' || country_code === 'Unknown Country') {
        tax1add = 0.2
      } else {
        tax1add = 0; // Valor padrão se o país não for encontrado
      }

      // tax1add = parseFloat(tax1add);

      console.log(tax1add);

      return tax1add;

    }

    var link = document.createElement("link");
    link.rel = "stylesheet";
    link.href = "https://www.ppl.cz/sources/map/main.css";

    // Create a script element to load the main.js file
    var script = document.createElement("script");
    script.src = "https://www.ppl.cz/sources/map/main.js";

    // Add the script+href link to the document head
    document.head.appendChild(link);
    document.head.appendChild(script);
    document.addEventListener(
      "ppl-parcelshop-map",
      (event) => {
        // attribute
        document.getElementById('name_ppl_selected').style.display = 'block';
        document.getElementById('name_ppl_selected').innerHTML = `Selected ${event.detail.name}`;

        document.getElementById('box_another_address_pickup').style.display = 'block';
        document.getElementById('text_my_address_pickup').innerHTML = `Selected pickup: ${event.detail.name}`;


        document.getElementById('id_ppl').value = event.detail.id;
        document.getElementById('accessPointType').value = event.detail.accessPointType;
        document.getElementById('code').value = event.detail.code;
        document.getElementById('dhlPsId').value = event.detail.dhlPsId;
        document.getElementById('depot').value = event.detail.depot;
        document.getElementById('depotName').value = event.detail.depotName;
        document.getElementById('name_ppl').value = event.detail.name;
        document.getElementById('street_ppl').value = event.detail.street;
        document.getElementById('city_ppl').value = event.detail.city;
        document.getElementById('zipCode_ppl').value = event.detail.zipCode;
        document.getElementById('country_ppl').value = event.detail.country;
        document.getElementById('parcelshopName').value = event.detail.parcelshopName;

        modalBox.style.display = 'none';
        modalOverlay.style.display = 'none';

        atualizaPrecoPickup(event.detail.country);
      }
    );

    let selectCrypto = document.getElementById("selectCrypto");
    let bt_submit_crypt = document.getElementById("bt_submit_crypt");
    let formCheckout = document.getElementById('formCheckout');
    let selectMethod = document.getElementById("selectMethod");
    let bt_submit = document.getElementById("bt_submit");

    formCheckout.addEventListener('submit', (event) => {
      pass1 = document.getElementById('pass1').value;
      pass2 = document.getElementById('pass2').value;

      if (pass1 !== pass2) {
        alert("Passwords must be the same");
        pass1 = '';
        pass2 = '';
        event.preventDefault();
        return;
      }
    })

    bt_submit_crypt.style.opacity = .5;

    selectCrypto.addEventListener("change", () => {
      if (selectCrypto.value !== '') {
        bt_submit_crypt.disabled = false;
        bt_submit_crypt.style.opacity = 1;
      } else {
        bt_submit_crypt.style.opacity = .5;
        bt_submit_crypt.disabled = true;
      }
    })

    function submitCrypto() {
      if (document.getElementById("radio_method_payment" == 'home2')) {
        document.getElementById("form_new_address").submit();
      }

      let url = "{!! route('finalize.register.order.crypto') !!}"
      document.getElementById('formCheckout').action = url;
    }

    function changeMetodo({
      target
    }) {
      if (target.value !== '') {
        bt_submit.disabled = false;
        bt_submit.style.opacity = 1;

        bt_submit_smart.disabled = false;
        bt_submit_smart.style.opacity = 1;
      } else {
        bt_submit.style.opacity = .5;
        bt_submit.disabled = true;

        bt_submit_smart.style.opacity = .5;
        bt_submit_smart.disabled = true;
      }
    }

    function submitSmart() {
      if (document.getElementById("radio_method_payment" == 'home2')) {
        document.getElementById("form_new_address").submit();
      }
      let url = "{!! route('finalize.register.order.pay.smart') !!}"
      let form = document.getElementById('formCheckout');
      form.action = url;
      console.log(form);


    }

    function submitMethod() {

      if (document.getElementById("radio_method_payment" == 'home2')) {
        document.getElementById("form_new_address").submit();
      }

      return
      if (selectMethod.value == '' || selectMethod.value == null) {
        bt_submit.style.opacity = .5;
        bt_submit.disabled = true;
      } else {
        let url = "{!! route('finalize.register.order.comgate') !!}"
        document.getElementById('formCheckout').action = url;
      }
    }

    function alterarValor(valor) {

      if (valor == 'home1') {
        document.getElementById('radio_method_payment').value = 'home1';
        frete = {{ $priceShippingHome + $subtotal }}
        document.getElementById('p_shipping').innerHTML = "€" + {{ $priceShippingHome }};
        document.getElementById('total_shipping').value = {{ $priceShippingHome }};
        document.getElementById('text_my_address_pickup').style.display = 'none';
        document.getElementById('input_vat').value = "<?php echo $total_VAT; ?>"
        document.getElementById('span_vat').innerHTML = "<?php echo $total_VAT; ?>"

      } else if (valor == 'home2') {

        document.getElementById('radio_method_payment').value = 'home2';
        document.getElementById('request_price').value = {{ $subtotal }};
        document.getElementById('value_order').innerHTML = "€" + {{ $subtotal }};
        document.getElementById('text_my_address_pickup').style.display = 'none';
        document.getElementById('p_shipping').innerHTML = "€" + {{ 0 }};
        return;
      } else if (valor == 'pickup') {
        document.getElementById('radio_method_payment').value = 'pickup';
        document.getElementById('p_shipping').innerHTML = "€" + {{ 0 }};
        document.getElementById('total_shipping').value = {{ 0 }};
        frete = {{ 0 + $subtotal }}
      }

      const numeroFormatado = frete.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = "€" + numeroFormatado;
    }

    function atualizaPrecoPickup(pais) {
      select = pais.toLowerCase();
      paises = @json($allPickup);
      let frete = 0;

      paises.forEach(element => {
        if (element.country_code.toLowerCase() == select) {
          newtax_add = precoPickup(pais)
          new_vat = calcularVAT(pais);
          frete = parseFloat(newtax_add) + parseFloat(<?php echo $withoutVAT; ?>);

          document.getElementById('input_vat').value = parseFloat(new_vat)
          document.getElementById('total_shipping').value = parseFloat(newtax_add)

          // newtax_add = newtax_add.toLocaleString('pt-BR', {
          //   minimumFractionDigits: 2,
          //   maximumFractionDigits: 2,
          //   useGrouping: true,
          // });


          document.getElementById('span_vat').innerHTML = parseFloat(new_vat).toFixed(2);
          document.getElementById('p_shipping').innerHTML = "€" + parseFloat(newtax_add);

        }
      });

      let tt = frete + new_vat;

      const numeroFormatado = tt.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = "€" + numeroFormatado;
    }


    function atualizaPreco() {
      select = document.getElementById('selectCountry').value;
      paises = @json($allCountry);
      let frete = 0;

      paises.forEach(element => {
        if (element.country == select) {
          newtax_add = addNewTax(select)
          frete = parseFloat(newtax_add) + parseFloat(<?php echo $withoutVAT; ?>);
          new_vat = calcularVAT(select);

          document.getElementById('total_shipping').value = parseFloat(newtax_add)
          document.getElementById('input_vat').value = parseFloat(new_vat)

          // newtax_add = newtax_add.toLocaleString('pt-BR', {
          //   minimumFractionDigits: 2,
          //   maximumFractionDigits: 2,
          //   useGrouping: true,
          // });

          document.getElementById('span_vat').innerHTML = parseFloat(new_vat).toFixed(2);
          document.getElementById('p_shipping').innerHTML = "€" + parseFloat(newtax_add);

        }
      });

      let tt = frete + new_vat;

      const numeroFormatado = tt.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = "€" + numeroFormatado;
    }
  </script>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>
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
      $(".link-register").click(function(event) {
        event.preventDefault();
        $(".box-register-form").fadeIn();
        $(".registers").fadeIn();
        $(".box-form-form").hide();
        $(".enter").hide();
      });
    });

    $(document).ready(function() {
      $("#pickup").click(function(event) {
        event.preventDefault();
        // $("#radio_method_payment").val('pickup');
        $("#box_pickup").fadeIn();
        $("#box_another_address").hide();
        $("#box_my_address").hide();
      });
    });

    $(document).ready(function() {
      $("#my_address").click(function(event) {
        event.preventDefault();
        // $("#radio_method_payment").val('home1');
        $("#box_my_address").fadeIn();
        $("#box_another_address").hide();
        $("#box_pickup").hide();
      });
    });

    $(document).ready(function() {
      $("#another_address").click(function(event) {
        event.preventDefault();
        // $("#radio_method_payment").val('home2');
        $("#box_another_address").fadeIn();
        $("#box_my_address").hide();
        $("#box_pickup").hide();
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
        // document.getElementById('campo_endereco').value = 'CEP inválido!'
      } else {
        preencherFormulario(endereco);
      }
    }

    document.getElementById('campo_cep').addEventListener('focusout', pesquisarCep);
  </script>

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
        // desabledTrueButton();
      } else {
        removeError(0);
        desabledFalseButton();
      }
    }

    function sobrenomeValidate() {

      if (campos[1].value.length < 3) {
        setError(1);
        // desabledTrueButton();
      } else {
        removeError(1);
        desabledFalseButton();
      }
    }

    function cepValidate() {

      if (campos[2].value.length < 3) {
        setError(2);
        // desabledTrueButton();
      } else {
        removeError(2);
        desabledFalseButton();
      }
    }
  </script>

@endsection
