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
      padding: 30px;
      background: #ffffff;
      box-shadow: 2px 2px 20px 2px rgba(0, 0, 0, 0.1);
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
      width: 30%;
      height: 40px;
      border-radius: 5px;
      background: #d26075;
      float: right;
      margin-right: 10px;
      color: #ffffff;
      font-weight: bold;
      text-transform: uppercase;
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

    #navbarNav {
      display: flex;
      justify-content: space-between;
      width: 100% !important;
    }

    #navbarNav ul {
      display: flex;
      flex-wrap: wrap;
      flex-direction: row;
      justify-content: space-between;
      width: 100% !important;
      gap: 1rem;
    }

    #navbarNav li {
      width: fit-content !important;
    }

    @media all and (min-width:2px) and (max-width:820px) {}


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
  </style>

  <main id="background-primary">
    <section class="container-ecomm" style="margin-top: 50px;">

      <div class="raw">
        <p class="title-ecomm">Olá {{ $user->name }}!</p>
      </div>

      <div class="raw">
        <div class="box-panel">
          @include('ecomm.layouts.ecomm_navbar')

          <div class="raw">
            <div style="margin-top: 50px;">

              <form action="{{ route('update.register') }}" style="mt-[30px]" method="POST" id="formCheckout">
                @csrf
                <input type="text" name="id" value="{{ $user->id }}" id="" style="display:none">
                <div class="box-general-form">
                  <div class="block-info-form">
                    <div class="header-block-form">
                      <p>Atualizar</p>
                    </div>
                    {{--  --}}
                    <div class="box-form-imputs">
                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Nome *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="name" value="{{ $user->name }}" required type="text">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>
                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Sobrenome *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" value="{{ $user->last_name }}" name="last_name" required
                            type="text">
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
                          <input class="new-inputs required" value="{{ $user->email }}" type="email" name="email"
                            readonly>
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
                          <input class="new-inputs"type="password" maxlength="16" name="password" id="pass1">
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
                          <input class="new-inputs" maxlength="16" name="checkpass" id="pass2" type="password">
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Celular *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input id="cell" onkeypress="allowNumeric(event)" type="cell"
                            class="form-control form-register @error('cell') is-invalid @enderror" placeholder="Cell"
                            name="phone" required autocomplete="cell" tabindex="10" value="{{ $user->phone }}">

                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs required">Apelido *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <input class="new-inputs" name="username" type="text" value="{{ $user->username }}" required>
                        </div>
                        {{-- ALERT --}}
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- LINE --}}
                      @if (isset($userReferral))
                        <div class="line-info-form">
                          <div class="column-form1">
                            <p class="title-inputs">Patrocinador</p>
                          </div>
                          <div class="column-form2">
                            <input class="new-inputs required" type="hidden" name="recommendation_user_id"
                              value="{{ $userReferral->id }}" readonly>
                            <input type="text" class="new-inputs required" name="" id=""
                              value="{{ $userReferral->login }}" readonly>
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                      @endif

                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">Genero *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <select class="new-inputs" name="sex" required>
                            <option value="0" @if ($user->sex == 0) selected @endif>Nenhum</option>
                            <option value="1" @if ($user->sex == 1) selected @endif>Masculino</option>
                            <option value="2" @if ($user->sex == 2) selected @endif>Feminino</option>
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

                    <div>
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
                            <input class="new-inputs-address" name="address" value="{{ $user->address }}"
                              type="text">
                            <input class="new-inputs-number" placeholder="N°" name="number"
                              value="{{ $user->number }}" type="number">
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
                            <input class="new-inputs" value="{{ $user->city }}" name="city" type="text">
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">CEP *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <input class="new-inputs" value="{{ $user->zip }}" name="zip" type="number">
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
                            <input class="new-inputs" value="{{ $user->state }}" name="state" type="text">
                          </div>                          
                          <div class="column-form3">

                          </div>
                        </div> --}}
                        {{-- LINE --}}
                        <div class="line-info-form">
                          {{-- INFO --}}
                          <div class="column-form1">
                            <p class="title-inputs">País *</p>
                          </div>
                          {{-- IMPUT --}}
                          <div class="column-form2">
                            <select class="new-inputs" name="country" required>
                              @foreach ($allCountry as $item)
                                @if ($item->country == $user->country)
                                  <option value="{{ $user->country }}" selected>{{ $user->country }}</option>
                                @else
                                  <option value="{{ $item->country }}">{{ $item->country }}</option>
                                @endif
                              @endforeach
                            </select>
                          </div>
                          {{-- ALERT --}}
                          <div class="column-form3">

                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="header-block-form">
                      <p>Endereço de faturamento</p>
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
                          <input class="new-inputs-address" value="{{ $addressBilling->address ?? null }}"
                            name="address_billing" type="text">
                          <input class="new-inputs-number" value="{{ $addressBilling->number_residence ?? null }}"
                            placeholder="N°" name="number_billing" type="number">
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
                          <input class="new-inputs" name="city_billing" type="text"
                            value="{{ $addressBilling->city ?? null }}">
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
                          <input class="new-inputs" name="zip_billing" type="number"
                            value="{{ $addressBilling->zip ?? null }}">
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
                          <input class="new-inputs" name="state_billing" type="text"
                            value="{{ $addressBilling->state ?? null }}">
                        </div>                        
                        <div class="column-form3">
                        </div>
                      </div> --}}
                      {{-- LINE --}}
                      <div class="line-info-form">
                        {{-- INFO --}}
                        <div class="column-form1">
                          <p class="title-inputs">País *</p>
                        </div>
                        {{-- IMPUT --}}
                        <div class="column-form2">
                          <select class="new-inputs" name="country_billing">
                            @if (isset($addressBilling))
                              @foreach ($allCountry as $item)
                                @if ($item->country == $addressBilling->country)
                                  <option value="{{ $addressBilling->country }}" selected>
                                    {{ $addressBilling->country }}</option>
                                @else
                                  <option value="{{ $item->country }}">{{ $item->country }}</option>
                                @endif
                              @endforeach
                            @else
                              @foreach ($allCountry as $item)
                                <option value="{{ $item->country }}">{{ $item->country }}</option>
                              @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>


                  {{--  --}}
                  <div class="line-info-form" style="margin-top: 40px;">
                    <div style="width: 100%; display: inline-block;">
                      <div class="width: 100%; display: inline-block;">
                        <div çlass="width: 100%; display: flex; justify-content:space-between">

                          <button type="submit" class="button-edit">Editar</button>

                        </div>
                      </div>
                      {{-- end form --}}

              </form>



            </div>

          </div>

          <div class="raw">
            <div style="margin-top: 50px;">

              <form action="{{ route('update.second.address') }}" style="mt-[30px]" method="POST" id="formCheckout">
                @csrf
                <input type="text" name="id" value="{{ $user->id }}" id=""
                  style="display:none">
                <div class="box-general-form">
                  <div class="block-info-form">
                    <div class="header-block-form">
                      <p>Atualizar 2° endereço</p>
                    </div>

                    <div class="box-form-imputs">

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Nome *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="first_name" type="text"
                            value="{{ $AddressSecondary->first_name ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Sobrenome *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="last_name" type="text"
                            value="{{ $AddressSecondary->last_name ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Endereço *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs-address" value="{{ $AddressSecondary->address ?? null }}"
                            name="address" type="text">
                          <input class="new-inputs-number" value="{{ $AddressSecondary->number ?? null }}"
                            placeholder="N°" name="number" type="number">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Bairro *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="neighborhood" type="text"
                            value="{{ $AddressSecondary->neighborhood ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Celular *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="phone" type="text"
                            value="{{ $AddressSecondary->phone ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Complemento</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="complement" type="text"
                            value="{{ $AddressSecondary->complement ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Cidade *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="city" type="text"
                            value="{{ $AddressSecondary->city ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>

                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">Cep *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="zip" type="number"
                            value="{{ $AddressSecondary->zip ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div>
                      {{-- <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">State *</p>
                        </div>
                        <div class="column-form2">
                          <input class="new-inputs" name="state" type="text"
                            value="{{ $AddressSecondary->state ?? null }}">
                        </div>
                        <div class="column-form3">

                        </div>
                      </div> --}}
                      <div class="line-info-form">
                        <div class="column-form1">
                          <p class="title-inputs">País *</p>
                        </div>
                        <div class="column-form2">
                          <select class="new-inputs" name="country">
                            @if (isset($AddressSecondary))
                              @foreach ($allCountry as $item)
                                @if ($item->country == $AddressSecondary->country)
                                  <option value="{{ $AddressSecondary->country }}" selected>
                                    {{ $AddressSecondary->country }}</option>
                                @else
                                  <option value="{{ $item->country }}">{{ $item->country }}</option>
                                @endif
                              @endforeach
                            @else
                              @foreach ($allCountry as $item)
                                <option value="{{ $item->country }}">{{ $item->country }}</option>
                              @endforeach
                            @endif
                          </select>
                        </div>
                      </div>
                    </div>

                  </div>


                  {{--  --}}
                  <div class="line-info-form" style="margin-top: 40px;">
                    <div style="width: 100%; display: inline-block;">
                      <div class="width: 100%; display: inline-block;">
                        <div çlass="width: 100%; display: flex; justify-content:space-between">

                          <button type="submit" class="button-edit">Editar</button>

                        </div>
                      </div>
                      {{-- end form --}}

              </form>



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
    $("#data").mask("00/00/0000");
    // $("#cnpj").mask("00.000.000/0000-00");
  </script>
  <script>
    let formCheckout = document.getElementById('formCheckout');

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
      $(".link-register").click(function(event) {
        event.preventDefault();
        $(".box-register-form").fadeIn();
        $(".registers").fadeIn();
        $(".box-form-form").hide();
        $(".enter").hide();
      });
    });
  </script>

@endsection
