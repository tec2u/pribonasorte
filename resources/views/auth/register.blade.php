@extends('layouts.app')
@section('content')
  <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}" />
  <script src="{{ asset('js/intlTelInput.min.js') }}"></script>
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.0/themes/base/jquery-ui.css" />
  <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
  <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
  <link href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet"
    type="text/css" />
  <style>
    input[type="date"]::-webkit-calendar-picker-indicator {
      cursor: pointer;
      filter: invert(0.8) brightness(50%) sepia(100%) saturate(10000%) hue-rotate(20deg);
    }

    .label_check {
      display: inline-block;
    }

    .check_teste {
      padding: 0;
      margin: 0;
      vertical-align: center;
      position: relative;
      top: 1px;
      overflow: hidden;
    }


    .list-group-item {
      color: #fff !important;
      background-color: #070707 !important;
      border: 1px solid rgba(56, 56, 56, 0.125)
    }

    .form-control.is-invalid {
      border-color: goldenrod;
      padding-right: calc(1.5em + 0.75rem);
      background-repeat: no-repeat;
      background-position: right calc(0.375em + 0.1875rem) center;
      background-size: calc(0.75em em + 0.375rem) calc(0.75em + 0.375rem);
      background-image: none !important;
    }

    body {
      background-image: url(../img/fundo-newhome.jpg) !important;
    }
  </style>

  @php
    $allCountry = Illuminate\Support\Facades\DB::select('SELECT * FROM shipping_prices ORDER BY country ASC');

    if (Session::has('redirect_buy')) {
        Session::forget('redirect_buy');
    }

  @endphp
  {{-- <video autoplay muted loop class="bg_video">
    <source src="/videos/fitnessAuraWay.mp4" type="video/mp4">
  </video> --}}
  {{-- @include('flash::message') --}}
  <div class="limiter py-5">
    <div class="container-login100">
      <div class="wrap-login100" style="width: 800px;">
        <form class="login100-form validate-form" method="POST" action="{{ route('register') }}">
          @csrf
          <span class="login100-form-title p-b-48">
            <img class="imagetest2" src="{{ asset('/img/logo-2-gradient.png') }}" alt="">
          </span>
          @if (isset($id))
            <h4 class="title-login mb-5">{{ __('Registrar com ') . $user->login }}</h4>
          @else
            <h4 class="title-login mb-5">{{ __('Registrar') }}</h4>
          @endif

          @if ($errors->has('error'))
            <div class="alert alert-primary">
              <h4 class="title-login" style="color: #070707">{{ $errors->first('error') }}</h4>
            </div>
          @endif

          <div class="row g-3 px-2" style="display: none;">
            <div class="col-lg-6">
              <input id="individual" type="radio" checked value="1" name="type_account">
              <p> Personal Registration</p>
            </div>

            <div class="col-lg-6">
              <input id="corporate" type="radio" value="2" name="type_account">
              <p> Company Registration</p>
            </div>
          </div>

          <div id="box_corporate" style="display: none; margin-bottom: 40px;" class="row g-3 px-2">
            <div class="col-lg-6">
              <label for="id_corp" class="form-label teste1234">ID Corporate<span style="color: brown">*</span></label>
              <input id="id_corp" type="text"
                class="form-control form-register @error('id_corp') is-invalid @enderror" placeholder="ID Corporate"
                name="id_corporate" value="{{ old('id_corp') }}" autocomplete="id_corp" tabindex="1">
              <span for="id_corp" class="focus-input100"></span>
              @error('id_corp')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="name_corp" class="form-label teste1234">Name Company<span style="color: brown">*</span></label>
              <input id="name_corp" type="text"
                class="form-control form-register @error('name_corp') is-invalid @enderror" placeholder="Name corporate"
                name="corporate_nome" value="{{ old('name_corp') }}" autocomplete="name_corp" tabindex="1">
              <span for="name_corp" class="focus-input100"></span>
              @error('name_corp')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="tax_id" class="form-label teste1234">Tax ID<span style="color: brown">*</span></label>
              <input id="tax_id" type="text"
                class="form-control form-register @error('tax_id') is-invalid @enderror" placeholder="Tax ID"
                name="tax_id" value="{{ old('tax_id') }}" autocomplete="tax_id" tabindex="1">
              <span for="tax_id" class="focus-input100"></span>
              @error('tax_id')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="vat_reg_no" class="form-label teste1234">Vat reg. no.<span style="color: brown">*</span></label>
              <input id="vat_reg_no" type="text"
                class="form-control form-register @error('vat_reg_no') is-invalid @enderror" placeholder="Vat reg. no."
                name="vat_reg_no" value="{{ old('vat_reg_no') }}" autocomplete="vat_reg_no" tabindex="1">
              <span for="vat_reg_no" class="focus-input100"></span>
              @error('vat_reg_no')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>





          </div>

          <div class="row g-3 px-2">

            <div class="col-lg-6">
              <label for="name" class="form-label teste1234">Nome<span style="color: brown">*</span></label>
              <input id="name" type="text"
                class="form-control form-register @error('name') is-invalid @enderror" placeholder="Nome"
                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus tabindex="1">
              <span for="name" class="focus-input100"></span>
              @error('name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="last_name" class="form-label teste1234">Sobrenome<span style="color: brown">*</span></label>
              <input id="last_name" type="text"
                class="form-control form-register @error('last_name') is-invalid @enderror" placeholder="Sobrenome"
                name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" autofocus
                tabindex="2">
              <span for="last_name" class="focus-input100"></span>
              @error('last_name')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
            {{-- <div class="mb-3 mt-3">
<label for="address1" class="form-label teste1234">Address 1 <span style="color: brown">
*</span>:</label>
<input id="address1" type="text"
class="form-control form-register @error('address1') is-invalid @enderror"
placeholder="Address 1" name="address1" value="{{ old('address1') }}" required
autocomplete="address1" tabindex="3">
<span for="address1" class="focus-input100"></span>
@error('address1')
<span class="invalid-feedback " role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
--}}
            {{-- <div class="mb-3 mt-3">
<label for="postcode " class="form-label teste1234">Postcode <span style="color: brown">
*</span>:</label>
<input id="postcode" type="text"
class="form-control form-register @error('postcode') is-invalid @enderror"
placeholder="Postcode" name="postcode" value="{{ old('postcode') }}" required
autocomplete="postcode" tabindex="5">
<span for="postcode" class="focus-input100"></span>
@error('postcode')
<span class="invalid-feedback " role="alert">
<strong>{{ $message }}</strong>
</span>
@enderror
</div> --}}
            <div class="col-lg-6">
              <label class="form-label teste1234">País <span style="color: brown">*</span></label>
              <select class="teste1234 form-register form-control" required name="country" tabindex="7"
                onchange="chooseCountryCellByCountryInput(event)">
                @foreach ($allCountry as $item)
                  <option class="country-{{ $item->country_code }}" value="{{ $item->country }}">
                    {{ $item->country }}
                  </option>
                @endforeach

              </select>
            </div>
            <div class="col-lg-6">
              <label for="city" class="form-label teste1234">Cidade<span style="color: brown">*
                </span></label>
              <input id="city" type="text"
                class="form-control form-register @error('city') is-invalid @enderror" placeholder="Cidade"
                name="city" value="{{ old('city') }}" required autocomplete="city" tabindex="8">
              <span for="city" class="focus-input100"></span>
              @error('city')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="zip" class="form-label teste1234">Cep<span style="color: brown">*
                </span></label>
              <input id="zip" type="number"
                class="form-control form-register @error('zip') is-invalid @enderror" placeholder="Cep" name="zip"
                value="{{ old('zip') }}" required autocomplete="zip" tabindex="8">
              <span for="zip" class="focus-input100"></span>
              @error('zip')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="number_residence" class="form-label teste1234">N° da residencia<span style="color: brown">*
                </span></label>
              <input id="number_residence" type="number"
                class="form-control form-register @error('number_residence') is-invalid @enderror"
                placeholder="N° da residencia" name="number_residence" value="{{ old('number_residence') }}" required
                autocomplete="Number residence" tabindex="8">
              <span for="number_residence" class="focus-input100"></span>
              @error('number_residence')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            {{-- <div class="col-lg-6">
              <label for="state" class="form-label teste1234">State<span style="color: brown">*
                </span></label>
              <input id="state" type="text"
                class="form-control form-register @error('state') is-invalid @enderror" placeholder="state"
                name="state" value="{{ old('state') }}" required autocomplete="state" tabindex="8">
              <span for="state" class="focus-input100"></span>
              @error('state')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div> --}}

            <div class="col-lg-12">
              <label for="address" class="form-label teste1234">Endereço<span style="color: brown">*
                </span></label>
              <input id="address" type="text"
                class="form-control form-register @error('address') is-invalid @enderror" placeholder="Endereço"
                name="address" value="{{ old('address') }}" required autocomplete="address" tabindex="8">
              <span for="address" class="focus-input100"></span>
              @error('address')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            {{-- <div class="col-lg-6">
              <label for="complement" class="form-label teste1234">Complement</label>
              <input id="complement" type="text"
                class="form-control form-register @error('complement') is-invalid @enderror" placeholder="complement"
                name="complement" value="{{ old('complement') }}" autocomplete="complement" tabindex="8">
              <span for="complement" class="focus-input100"></span>
              @error('complement')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div> --}}

            {{-- <div class="col-lg-6">
              <label for="area_residence" class="form-label teste1234">Area<span style="color: brown">*
                </span></label>
              <input id="area_residence" type="text"
                class="form-control form-register @error('area_residence') is-invalid @enderror" placeholder="Area"
                name="area_residence" value="{{ old('area_residence') }}" required autocomplete="area_residence"
                tabindex="8">
              <span for="area_residence" class="focus-input100"></span>
              @error('area_residence')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div> --}}



            <div class="col-lg-6">
              <label for="login" class="form-label teste1234">Apelido<span style="color: brown">*
                </span></label>
              <input id="login" type="text"
                class="form-control form-register login @error('login') is-invalid @enderror" placeholder="Apelido"
                name="login" value="{{ old('login') }}" required autocomplete="login" autofocus tabindex="13"
                onkeypress="allowAlphaNumeric(event)">
              <span for="login" class="focus-input100"></span>
              @error('login')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="cell" class="form-label teste1234">Celular<span style="color: brown">* </span>
                (Somente numeros)</label>
              <input id="cell" onkeypress="allowNumeric(event)" type="cell"
                class="form-control form-register @error('cell') is-invalid @enderror" placeholder="Celular"
                name="cell" value="{{ old('cell') }}" required autocomplete="cell" tabindex="10">
              <span for="cell" class="focus-input100"></span>
              @error('cell')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
              <input type="hidden" name="countryCodeCell" id="countryCodeCell" value="1">
            </div>

            @if (isset($id))
              <div class="wrap-input100 validate-input" style="display: none;">
                <input id="recommendation_user_id" type="text" class="input100" name="recommendation_user_id"
                  value="{{ $id }}" autofocus>
                <span for="recommendation_user_id" class="focus-input100"
                  data-placeholder="Recommendation User Id"></span>
              </div>
            @else
              <div class="col-lg-6">
                <label for="recommendation_user_id" class="form-label teste1234">Patrocinador</label>
                <input id="recommendation_user_id" type="text" class="form-control form-register"
                  name="recommendation_user_id" placeholder="Patrocinador" value="{{ old('recommendation_user_id') }}"
                  autofocus tabindex="18" onkeypress="allowAlphaNumeric(event)">
                <span for="recommendation_user_id" class="focus-input100"></span>
              </div>
            @endif

            <div class="col-lg-6">
              <label for="email" class="form-label teste1234">Email<span style="color: brown">*</span></label>
              <input id="email" type="email"
                class="form-control form-register @error('email') is-invalid @enderror" placeholder="Email"
                name="email" value="{{ old('email') }}" required autocomplete="email" tabindex="14">
              <span for="email" class="focus-input100"></span>
              @error('email')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for="password" class="form-label teste1234">Senha<span style="color: brown">*</span></label>
              <span class="btn-show-pass d-inline-flex ps-2">
                <i class="fa fa-eye" onclick="showPassrWord('password')"></i>
              </span>
              <input id="password" type="password"
                class="form-control form-register @error('password') iconreg is-invalid  @enderror" placeholder="Senha"
                name="password" required autocomplete="new-password" tabindex="15">

              <span for="password" class="focus-input100"></span>
              @error('password')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>

            <div class="col-lg-6">
              <label for=" password-confirm" class="form-label teste1234">Confirmar senha<span
                  style="color: brown">*</span></label>
              <span class="btn-show-pass d-inline-flex ps-2">
                <i class="fa fa-eye" onclick="showPassrWord('password-confirm')"></i>
              </span>
              <input id="password-confirm" type="password" class="form-control form-register"
                placeholder="Confirmar senha" name="password_confirmation" required autocomplete="new-password"
                tabindex="16">
              <span for="password-confirm" class="focus-input100"></span>
            </div>

            <div class="mb-3 mt-3">
              <label for="birthday" class="form-label teste1234">Data de nascimento <span style="color: brown">
                  *</span>:</label>
              <input id="birthday" type="text"
                class="form-control form-register @error('birthday') is-invalid @enderror" name="birthday"
                value="{{ old('birthday') }}" readonly="readonly" required autocomplete="birthday"
                placeholder="dd/mm/yyyy" value="" tabindex="11">
              <span id="lblError" style="color:Red"></span>
              @error('birthday')
                <span class="invalid-feedback " role="alert">
                  <strong>{{ $message }}</strong>
                </span>
              @enderror
            </div>
          </div>
          <div class="p-4">



            <div id="address_billing" class="row g-3 px-2" style="display: none">
              <h5 class="title-login mb-1">Endereço de faturamento</h5>

              <div class="col-lg-6">
                <label class="form-label teste1234">País <span style="color: brown">*</span></label>
                <select class="teste1234 form-register form-control" name="faturing_country" tabindex="7">
                  @foreach ($allCountry as $item)
                    <option value="{{ $item->country }}">{{ $item->country }}</option>
                  @endforeach

                </select>
              </div>
              <div class="col-lg-6">
                <label for="city" class="form-label teste1234">Cidade<span style="color: brown">*
                  </span></label>
                <input id="faturing_city" type="text"
                  class="form-control form-register @error('city') is-invalid @enderror" placeholder="Cidade"
                  name="faturing_city" value="{{ old('city') }}" autocomplete="city" tabindex="8">
                <span for="city" class="focus-input100"></span>
                @error('city')
                  <span class="invalid-feedback " role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="col-lg-6">
                <label for="zip" class="form-label teste1234">Cep<span style="color: brown">*
                  </span></label>
                <input id="faturing_zip" type="number"
                  class="form-control form-register @error('zip') is-invalid @enderror" placeholder="Cep"
                  name="faturing_zip" value="{{ old('zip') }}" autocomplete="zip" tabindex="8">
                <span for="zip" class="focus-input100"></span>
                @error('zip')
                  <span class="invalid-feedback " role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>


              <div class="col-lg-6">
                <label for="faturing_number_residence" class="form-label teste1234">N° da residencia<span
                    style="color: brown">*
                  </span></label>
                <input id="faturing_number_residence" type="number"
                  class="form-control form-register @error('faturing_number_residence') is-invalid @enderror"
                  placeholder="N° da residencia" name="faturing_number_residence"
                  value="{{ old('faturing_number_residence') }}" autocomplete="Number residence" tabindex="8">
                <span for="faturing_number_residence" class="focus-input100"></span>
                @error('faturing_number_residence')
                  <span class="invalid-feedback " role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="col-lg-12">
                <label for="address" class="form-label teste1234">Endereço<span style="color: brown">*
                  </span></label>
                <input id="faturing_address" type="text"
                  class="form-control form-register @error('address') is-invalid @enderror" placeholder="Endereço"
                  name="faturing_address" value="{{ old('address') }}" autocomplete="address" tabindex="8">
                <span for="address" class="focus-input100"></span>
                @error('address')
                  <span class="invalid-feedback " role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
          </div>



          <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
            <input class="form-check-input" name="addressBillingSameDelivery" type="checkbox"
              id="flexCheckAddressBilling" checked>
            <label class="form-check-label" for="flexCheckDefault" style="color: #fff;font-size:.8rem;">
              O endereço de faturamento é o mesmo da entrega
            </label>
          </div>

          <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
            <label class="form-check-label" for="flexCheckDefault" style="color: #fff;font-size:.8rem;">
              Eu concordo com os
              <a href="{{ route('general_terms_conditions') }}" style="color: #fff;">
                termos</a>
            </label>
          </div>

          <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
              <div class="login100-form-bgbtn"></div>
              <button type="submit" class="login100-form-btn btn btn-primary rounded-pill">
                Registrar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.js"
    integrity="sha512-+k1pnlgt4F1H8L7t3z95o3/KO+o78INEcXTbnoJQ/F2VqDVhWoaiVml/OEHv9HsVgxUaVW+IbiZPUJQfF/YxZw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script> --}}

  <script src="{{ asset('js/intlTelInput.min.js') }}"></script>
  <script src="http://code.jquery.com/jquery-1.8.2.js"></script>
  <script src="http://code.jquery.com/ui/1.9.0/jquery-ui.js"></script>


  <script>
    document.getElementsByClassName('login')[0].addEventListener("change", function() {
      this.value = this.value.toLowerCase();
    });
  </script>
  <script>
    function allowAlphaNumeric(e) {
      var code = ('charCode' in e) ? e.charCode : e.keyCode;
      if (!(code > 47 && code < 58) && // numeric (0-9)
        !(code > 64 && code < 91) && // upper alpha (A-Z)
        !(code > 96 && code < 123)) { // lower alpha (a-z)
        e.preventDefault();
      }
    }
  </script>
  <script>
    $(function() {
      $("#birthday").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        yearRange: '1900:+0',
        onSelect: function(dateString, txtDate) {
          ValidateDOB(dateString);
        }
      });
    });

    function ValidateDOB(dateString) {
      var lblError = $("#lblError");
      var parts = dateString.split("/");
      var dtDOB = new Date(parts[1] + "/" + parts[0] + "/" + parts[2]);
      var dtCurrent = new Date();
      lblError.html("Eligibility 18 years ONLY.")
      if (dtCurrent.getFullYear() - dtDOB.getFullYear() < 18) {
        return false;
      }
      if (dtCurrent.getFullYear() - dtDOB.getFullYear() == 18) {
        //CD: 11/06/2018 and DB: 15/07/2000. Will turned 18 on 15/07/2018.
        if (dtCurrent.getMonth() < dtDOB.getMonth()) {
          return false;
        }
        if (dtCurrent.getMonth() == dtDOB.getMonth()) {
          //CD: 11/06/2018 and DB: 15/06/2000. Will turned 18 on 15/06/2018.
          if (dtCurrent.getDate() < dtDOB.getDate()) {
            return false;
          }
        }
      }
      lblError.html("");
      return true;
    }

    $(window).load(function() {
      $('#flash-overlay-modal').modal('show');
    });

    $(document).ready(function() {
      function checkBillingAddressVisibility() {
        if ($('#address_billing').is(':visible')) {
          $('#address_billing input').attr('required', true);
        } else {
          $('#address_billing input').removeAttr('required');
        }
      }

      checkBillingAddressVisibility();

      $('#flexCheckAddressBilling').bind('change', function() {
        $('#address_billing').toggle();
        checkBillingAddressVisibility();
      });

      $(window).bind('resize', function() {
        checkBillingAddressVisibility();
      });
    });



    function chooseCountryCellByCountryInput(e) {
      const selectedOption = event.target.selectedOptions[0];
      const selectedClass = selectedOption.className;
      const cleanedClass = selectedClass.replace("country-", "");
      // console.log("Cleaned class:", cleanedClass);
      codeCell.setCountry(cleanedClass);
    }

    function allowNumeric(e) {
      var code = ('charCode' in e) ? e.charCode : e.keyCode;
      if (!(code > 47 && code < 58)) { // numeric (0-9)
        e.preventDefault();
      }
    }

    var inputCell = document.querySelector("#cell");
    let codeCell = window.intlTelInput(inputCell, {
      separateDialCode: true
    });
    inputCell.addEventListener("countrychange", function() {
      // console.log(codeCell.getSelectedCountryData());
      document.querySelector("#countryCodeCell").value = codeCell.getSelectedCountryData().dialCode;
    });


    function showPassrWord(id) {
      let senhaInput = document.getElementById(id)
      if (senhaInput.type === "password") {
        senhaInput.type = "text";
      } else {
        senhaInput.type = "password";
      }
    }

    $(document).ready(function() {
      $("#individual").click(function() {
        if ($("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeOut();
        }
      });
    });

    $(document).ready(function() {
      $("#corporate").click(function() {
        if (!$("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeIn();
        }
      });
    });

    $(document).ready(function() {
      $("#individual").click(function() {
        if ($("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeOut(function() {
            $(this).find('input').prop('required', false);
          });
        }
      });
    });

    $(document).ready(function() {
      $("#corporate").click(function() {
        if (!$("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeIn(function() {
            $(this).find('input').prop('required', true);
          });
        }
      });
    });
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
  </script>
@endsection
