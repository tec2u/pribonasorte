@extends('layouts.header')
@section('content')
  <style>
    .card-product-list {
      background: #ffffff;
      padding: 30px;
      border-radius: 5px;
      box-shadow: 2px 2px 20px #cdcdcd;
    }

    .new-title {
      font-size: 35px;
      margin-left: -20px;
      font-weight: bold;
      float: left;
      color: #000000;
    }

    .head-table-list {
      width: 100%;
      display: inline-block;
      background: #470c52;
      padding: 10px 10px;
      border-radius: 5px 5px 0px 0px;
    }

    .card-title-new {
      font-size: 17px;
      font-weight: bold;
      color: #000000;
    }

    #button-buy {
      width: 100%;
      margin-top: 20px;
      height: 40px;
      font-size: 14px;
    }

    .price-value {
      font-size: 16px;
      font-weight: bold;
    }

    .bloco-footer {
      margin-left: -10px;
    }

    button.btn-amount {
      width: 20px;
      background: #470c52;
      color: #ffffff;
      border: 0px;
    }

    .endereco2 {
      display: flex;
      gap: 1rem;
      flex-wrap: wrap;
      padding: 1rem;
    }

    .endereco2 input,
    .endereco2 select {
      border-radius: 5px;
      padding: .2rem .5rem;
      border: #C0C0C0 solid 1px;
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      z-index: 2;
    }

    /* CSS for the modal box */
    .modal-box {
      position: relative;
      margin: 0 auto;
      height: 720px;
      display: none;
      z-index: 2;
    }

    #close-modal-button {
      position: absolute;
      top: 10px;
      right: 10px;
      z-index: 3;
    }

    .ppl-parcelshop-map {
      height: 100%;
      max-height: 640px;
      display: relative;
      z-index: 2;
    }

    #name_ppl_selected {
      display: relative;
      z-index: 2;
    }
  </style>

  <main id="main" class="main">.
    @php
      $user_id = Auth::user()->id;
      // $availableComission = Illuminate\Support\Facades\DB::select("select sum(price) from banco where user_id=$user_id");
      // $availableComission = isset($availableComission[0]->{'sum(price)'}) ? $availableComission[0]->{'sum(price)'} : 0;

      use App\Models\User;
      use Illuminate\Support\Facades\Auth;
      use Illuminate\Support\Carbon;
      use Illuminate\Support\Facades\DB;

      $currentDate = Carbon::now();
      $dayThreshold = 16;
      $cardAvailable = false;
      $subMonth = $currentDate->subMonth()->month;
      $currentYear = $currentDate->year;
      $currentMonth = $currentDate->month;

      if ($subMonth >= 2) {
          $subMonth = $subMonth - 1;
          $currentYear = $currentYear;
      } else {
          $subMonth = 12;
          $currentYear = $currentYear - 1;
      }

      if ($subMonth < 9) {
          $subMonth = '0' . $subMonth;
      } else {
          $subMonth = $subMonth;
      }

      $farst_day = date('t', mktime(0, 0, 0, $subMonth, '01', $currentYear));

      $dateComplete = $currentYear . '-' . $subMonth . '-' . $farst_day . ' 23:59:59';
      $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

      if ($currentDate->day >= $dayThreshold) {
          $cardAvailable = true;

          $availableComission = DB::table('banco')
              ->where('user_id', User::find(Auth::id())->id)
              ->where('price', '>', 0)
              ->where('created_at', '<=', $dateComplete2)
              ->sum('price');
      } else {
          $availableComission = DB::table('banco')
              ->where('user_id', User::find(Auth::id())->id)
              ->where('price', '>', 0)
              ->whereMonth('created_at', '<=', $dateComplete)
              ->sum('price');
      }

      $retiradasTotais = DB::table('banco')
          ->where('user_id', User::find(Auth::id())->id)
          ->where('price', '<', 0) // Considera apenas valores negativos
          ->sum('price');

      $retiradasTotais = -$retiradasTotais;

      if ($retiradasTotais >= $availableComission) {
          $availableComission = 0;
      } else {
          $availableComission = $availableComission - $retiradasTotais;
      }

      $userCorporate = User::where('id', User::find(Auth::id())->id)
          ->whereNotNull('id_corporate')
          ->whereNotNull('corporate_nome')
          ->first();

      $userCorporate = isset($userCorporate);

    @endphp
    <!-- Modal box -->

    {{-- @include('flash::message') --}}
    <section id="produto" class="content">

      <div class="fade">
        <div class="container-fluid">
          <div class="row justify-content-evenly" style="margin-bottom: 10px;">
            <p class="new-title">Cart Shop</p>
          </div>
          <div class="row justify-content-evenly card-product-list" style="margin-bottom: 20px;">
            <div class="head-table-list">
              <table style="width: 100%;">
                <thead>
                  <tr>
                    <th width="50%" style="color: #ffffff; font-size: 13px;">Product</th>
                    <th width="15%" style="color: #ffffff; font-size: 13px;">Price</th>
                    <th width="15%" style="color: #ffffff; font-size: 13px;">Quant</th>
                    <th width="15%" style="color: #ffffff; font-size: 13px;">Total</th>
                    <th width="5%" style="color: #ffffff; font-size: 13px;"></th>
                  </tr>
                </thead>
              </table>
            </div>
            @if ($count_itens > 0)
              @foreach ($user_cart as $item)
                @php
                  $new_price = number_format($item->price, 2, ',', '.');
                  $new_total = number_format($item->total, 2, ',', '.');
                  $new_sub_total = number_format($subtotal, 2, ',', '.');

                @endphp
                <table class="border-radius: 0px 0px 5px 5px;">
                  <thead>
                    <tr></tr>
                    <th style="border: 1px solid silver; padding: 10px;" width="5%">
                      <a href=""><img style="width: 40px;" src="/img/products/{{ $item->img }}"
                          alt="img_products"></a>
                    </th>
                    <th style="border: 1px solid silver; padding: 10px;" width="45%">{{ $item->name }}</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="15%">€{{ $new_price }}</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="15%">
                      <ul style="margin-top: 10px;">
                        <li style="display: inline-block;"><input class="text-center" style="width: 50px;" disabled
                            type="text" value="{{ $item->amount }}"></li>
                      </ul>
                    </th>
                    <th style="border: 1px solid silver; padding: 10px;" width="15%">€{{ $new_total }}</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="5%">
                      {{-- <a id="limpa_carrinho" href="{{ route('packages.delete_cart_buy', ['id' => $item->id]) }}"><i
                          style="font-size: 20px;" class="bi bi-trash3-fill"></i></a> --}}
                    </th>
                    </tr>
                  </thead>
                </table>
              @endforeach
            @else
              <table>
                <thead>
                  <tr></tr>
                  <th style="border: 1px solid silver; padding: 10px;" width="100%">
                    <p class="text-center mt-2">no items in cart!</p>
                  </th>
                  </tr>
                </thead>
              </table>
            @endif

            <div class="head-table-list">
              <table style="width: 100%;">
                <thead>
                  <tr>
                    <th width="50%" style="color: #ffffff; font-size: 13px;">Package</th>
                    <th width="15%" style="color: #ffffff; font-size: 13px;">Price</th>
                    <th width="15%" style="color: #ffffff; font-size: 13px;">Quant</th>
                    <th width="15%" style="color: #ffffff; font-size: 13px;">Total</th>
                    <th width="5%" style="color: #ffffff; font-size: 13px;"></th>
                  </tr>
                </thead>
              </table>
            </div>
            @if ($count_itens > 0)
              {{-- @foreach ($package_choosed as $item) --}}
              @php
                $new_price_package = number_format($package_choosed->price, 2, ',', '.');
                $new_total_package = number_format($package_choosed->price, 2, ',', '.');
              @endphp
              <table class="border-radius: 0px 0px 5px 5px;">
                <thead>
                  <tr></tr>
                  <th style="border: 1px solid silver; padding: 10px;" width="5%">
                    <a href=""><img style="width: 40px;" src="/img/packages/{{ $package_choosed->img }}"
                        alt="img_products"></a>
                  </th>
                  <th style="border: 1px solid silver; padding: 10px;" width="45%">{{ $package_choosed->name }}</th>
                  <th style="border: 1px solid silver; padding: 10px;" width="15%">€{{ $new_price_package }}</th>
                  <th style="border: 1px solid silver; padding: 10px;" width="15%">
                    <ul style="margin-top: 10px;">
                      <li style="display: inline-block;"><input class="text-center" style="width: 50px;" disabled
                          type="text" value="1"></li>
                    </ul>
                  </th>
                  <th style="border: 1px solid silver; padding: 10px;" width="15%">€{{ $new_total_package }}</th>
                  <th style="border: 1px solid silver; padding: 10px;" width="5%">
                    {{-- <a id="limpa_carrinho" href="{{ route('packages.delete_cart_buy', ['id' => $item->id]) }}"><i
                          style="font-size: 20px;" class="bi bi-trash3-fill"></i></a> --}}
                  </th>
                  </tr>
                </thead>
              </table>
              {{-- @endforeach --}}
            @else
              <table>
                <thead>
                  <tr></tr>
                  <th style="border: 1px solid silver; padding: 10px;" width="100%">
                    <p class="text-center mt-2">no items in cart!</p>
                  </th>
                  </tr>
                </thead>
              </table>
            @endif

            @if ($count_itens > 0)
              <table style="margin-top: 10px;">
                <thead>
                  {{-- <tr>
                    <th style="border: 1px solid silver; padding: 10px;" width="75%">Frete</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="25%">€{{ $priceShippingHome }}</th>
                  </tr> --}}
                </thead>
              </table>
              <table style="margin-top: 10px;">
                <thead>
                  <tr>
                    <th style="border: 1px solid silver; padding: 10px;" width="75%">Subtotal</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="25%">
                      €{{ $withoutVAT }}</th>
                  </tr>
                  <tr>
                    <th style="border: 1px solid silver; padding: 10px;" width="75%">QV</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="25%">
                      {{ number_format($qv, 2, ',', '.') }}</th>
                  </tr>
                  <tr>
                    <th style="border: 1px solid silver; padding: 10px;" width="75%">CV</th>
                    <th style="border: 1px solid silver; padding: 10px;" width="25%">
                      {{ number_format($cv, 2, ',', '.') }}</th>
                  </tr>
                </thead>
              </table>
            @endif
            <br>


          </div>
          {{--  --}}

          @if ($count_itens > 0)
            <form action="{{ route('packages.newProcessPackage5') }}" method="POST" onsubmit="submitCheckout()"
              id="form-checkout-backoffice">
              @csrf
              <div style="margin-top: 20px;" class="row justify-content-evenly">

                {{-- <div style="padding: 0px 0px 0px 5px;display: none;" id="formHome2" class="col-12">
                  <div class="card shadow p-md-5" style="width: 100%; height: fit-content;">
                    <h2>New Adress</h2>

                    <div class="row-form endereco2">

                      <div style="width: 70%; margin-bottom: 20px; inline-block;">
                        <div style="width: 48%; margin-right: 1%; float: left; display: inline-block;">
                          <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                            placeholder="First name" type="text" name="name">
                        </div>
                        <div style="width: 48%; margin-left: 1%; float: left; display: inline-block;">
                          <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                            placeholder="Last name" type="text" name="last_name">
                        </div>
                      </div>


                      <div class="small-form">
                        <input class="form-input-finalize" placeholder="Phone +000 00000000" id="tel"
                          type="text" name="phone">
                      </div>
                      <div class="min-form">
                        <input class="form-input-finalize cep" placeholder="ZIP code" aria-label="CEP" id="campo_cep"
                          type="text" name="zip">
                      </div>
                      <div class="lowm-form">
                        <input class="form-input-finalize" placeholder="Address" id="campo_endereco" type="text"
                          name="address">
                      </div>
                      <div class="min-form">
                        <input class="form-input-finalize" placeholder="Number of residence" type="number"
                          name="number" id="n_residence">
                      </div>
                    </div>

                    <div class="row-form endereco2">
                    
                      <div class="small-form">
                        <input class="form-input-finalize" placeholder="City" id="campo_cidade" type="text"
                          name="city">
                      </div>
                      <div class="mic-form">
                        <input class="form-input-finalize" placeholder="State" id="campo_estado" type="text"
                          name="state">
                      </div>
                      <br>
                      <div class="small-form">
                        <select class="form-input-finalize" name="country" id="selectCountry"
                          onchange="atualizaPreco()">
                          <option value="">Choose</option>
                          @foreach ($countryes as $item)
                            <option value="{{ $item->country }}">
                              {{ $item->country }}
                            </option>
                          @endforeach
                        </select>
                      </div>
                      <div class="small-form">
                        <a class="btn btn-success btn-sm" id="calcularfrete2" onclick="atualizaPreco()">
                          Calculate</a>
                      </div>
                      <span id="lognPrice"></span>
                    </div>

                  </div>
                </div> --}}

                <!-- Modal overlay -->

                <div class="modal-overlay" id="modal-overlay"></div>
                <div class="modal-box" style="background-color: #f0f0f0;">
                  <div id="ppl-parcelshop-map"></div>
                  <h5 id="name_ppl_selected"
                    style="display: none; background-color: #f0f0f0; font-size:2rem; color:green;">Selected
                  </h5>
                  <button id="close-modal-button" class="btn btn-primary btn_address">Close Modal</button>
                </div>


                <div style="padding: 0px 0px 0px 5px;display: none;" id="wpickup" class="col-12">
                  {{-- <div class="card shadow p-md-5" style="width: 100%; height: fit-content;"> --}}
                  {{-- <div id="ppl-parcelshop-map"></div> --}}

                  {{-- <div id="ppl-parcelshop-map" data-lat="50" data-lng="15" datamode="default" data-language='en'>
                    </div> --}}


                  {{-- <h5 id="name_ppl_selected" style="display: none">Selected</h5> --}}

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
                  {{-- </div> --}}
                </div>
                <div style="display: flex;flex-direction: row;">
                  <div style="padding: 0rem;" class="col-sm-12 col-md-6">
                    <div class="card shadow p-md-8" id="formHome2"
                      style="width: 100%; padding:1rem;height: auto; display: flex; flex-wrap: wrap; display: none">

                      <p class="card-title-new text-start">Address:</p>

                      <div class="row-form endereco2">

                        <div class="col-sm-12">
                          <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                            placeholder="First name" type="text" name="first_name"
                            class="form-control form-register">
                        </div>

                        <div class="col-sm-12">
                          <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                            class="form-control form-register" placeholder="Last name" type="text"
                            name="last_name">
                        </div>



                        <div class="col-sm-12">
                          {{-- <input class="form-input-finalize" placeholder="Phone +000 00000000" id="tel" type="text"
                            name="phone"> --}}

                          <input id="cell" onkeypress="allowNumeric(event)" type="cell"
                            class="form-control form-register" placeholder="Cell" name="phone"
                            value="{{ old('phone') }}" autocomplete="cell" tabindex="10">
                          <span for="cell" class="focus-input50"></span>
                          <input type="hidden" name="countryCodeCell" id="countryCodeCell" value="1">

                        </div>

                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register" placeholder="City"
                            id="campo_cidade" type="text" name="city">
                        </div>

                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register" placeholder="Address"
                            id="campo_endereco" type="text" name="address">
                        </div>
                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register" placeholder="N° of residence"
                            type="number" name="number" id="n_residence">
                        </div>

                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register cep" placeholder="ZIP code"
                            aria-label="CEP" id="campo_cep" type="number" name="zip">
                        </div>

                        <div class="col-sm-12">
                          <select class="form-input-finalize form-control form-register" name="country"
                            id="selectCountry" onchange="atualizaPreco()">
                            <option value="">Choose</option>
                            @foreach ($countryes as $item)
                              <option class="country-{{ $item->country_code }}" value="{{ $item->country }}">
                                {{ $item->country }}
                              </option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-sm-12">
                          <a class="btn btn-primary btn-sm" id="calcularfrete2" onclick="atualizaPreco()">
                            Confirm</a>
                        </div>
                        <span id="lognPrice" style="display: none"></span>
                      </div>

                    </div>

                    <div class="card shadow p-md-8" id="formHome1"
                      style="width: 100%; padding:1rem;height: auto; display: flex; flex-wrap: wrap;">

                      <p class="card-title-new text-start">Your Address:</p>

                      <div class="row-form endereco2">

                        <div class="col-sm-12">
                          <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                            placeholder="First name" type="text" readonly value="{{ $user->name }}"
                            class="form-control form-register">
                        </div>

                        <div class="col-sm-12">
                          <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                            class="form-control form-register" placeholder="Last name" type="text"
                            value="{{ $user->last_name }}" readonly>
                        </div>

                        <div class="col-sm-12">
                          <input id="cell" onkeypress="allowNumeric(event)" type="cell"
                            class="form-control form-register" placeholder="Cell" value="{{ $user->cell }}"
                            autocomplete="cell" tabindex="10" readonly>
                        </div>

                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register" placeholder="City"
                            id="campo_cidade" type="text" value="{{ $user->city }}" readonly>
                        </div>

                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register" placeholder="Address"
                            id="campo_endereco" type="text" value="{{ $user->address1 }}" readonly>
                        </div>
                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register" placeholder="N° of residence"
                            type="number" id="n_residence" value="{{ $user->number_residence }}" readonly>
                        </div>

                        <div class="col-sm-12">
                          <input class="form-input-finalize form-control form-register cep" placeholder="ZIP code"
                            aria-label="CEP" id="campo_cep" type="number" value="{{ $user->postcode }}" readonly>
                        </div>

                        <div class="col-sm-12">
                          <select class="form-input-finalize form-control form-register">
                            <option>
                              {{ $user->country }}
                            </option>
                          </select>
                        </div>
                        <div class="col-sm-12">
                          <a class="btn btn-primary btn-sm" href="{{ route('users.index') }}">
                            Edit</a>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div style="" class="col-sm-12 col-md-6">
                    @if (session('error'))
                      <div class="alert alert-danger">
                        {{ session('error') }}
                      </div>
                    @endif
                    <div class="card shadow p-md-2" style="width: 100%; height: fit-content;">
                      <p class="card-title-new text-start">Choose Shipping</p>
                      {{-- <p class="text-start">€{{ $priceShippingHome }}</p> --}}
                      <div class="form-check" style="font-size: 1rem;">
                        <input class="form-check-input" type="radio" value="home1" name="method_shipping"
                          id="flexRadioDefault1" onclick="alterarValor('home')" required>
                        <label class="form-check-label" for="flexRadioDefault1">
                          Deliver At Your Address
                        </label>
                      </div>

                      {{-- @if (isset($priceShippingPickup)) --}}
                      <div class="form-check" style="font-size: 1rem;" id="btn-wpickup">
                        <input class="form-check-input" type="radio" value="pickup" name="method_shipping"
                          id="flexRadioDefault2" onclick="alterarValor('pickup')" required>
                        <label class="form-check-label" for="flexRadioDefault2">
                          Pick Up <span style="display: none" id="logpickup"></span>
                        </label>
                      </div>
                      {{-- @endif --}}

                      <div class="form-check" style="font-size: 1rem;" id="id-novo-endereco">
                        <input class="form-check-input" type="radio" value="home2" name="method_shipping"
                          id="flexRadioDefault3" onclick="alterarValor('home2')" required>
                        <label class="form-check-label" for="flexRadioDefault3">
                          Other address
                        </label>
                      </div>
                      <br>
                      <p id="address1" style="display: none; margin-top: 20px;">
                        Address Shipping:
                        {{ Auth::user()->address1 }}
                      </p>

                      <table style="margin-top: 10px;">
                        <thead>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">My E-Wallet Balance</th>
                            <th style="border: 1px solid silver; padding: 10px;" width="25%">
                              €{{ number_format($availableComission, 2, ',', '.') }}</th>
                          </tr>
                        </thead>
                      </table>

                      {{-- @if ($availableComission > 0)
                      <div class="form-check" style="font-size: .8rem; display:flex; align-items: center">
                        <input class="form-check-input" type="radio" value="home1" name="method_shipping"
                          id="flexRadioDefault1comission" onclick="" required>
                        <label class="form-check-label" for="flexRadioDefault1comission">
                          Use my balance
                        </label>
                      </div>
                      @endif --}}

                      <table style="margin-top: 10px;">
                        <thead>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">Subtotal</th>
                            <th style="border: 1px solid silver; padding: 10px;" width="25%">€{{ $withoutVAT }}
                            </th>
                          </tr>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">VAT</th>
                            <th style="border: 1px solid silver; padding: 10px;" width="25%">€<span
                                id="p_vat">{{ $total_VAT }}</span>
                            </th>
                          </tr>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">Shipping</th>
                            <th style="border: 1px solid silver; padding: 10px;" width="25%">€<span
                                id="shipping_table">{{ number_format($priceShippingHome, 2, '.', ',') }}</span>
                            </th>
                          </tr>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">QV</th>
                            <th style="border: 1px solid silver; padding: 10px;" width="25%">
                              {{ number_format($qv, 2, ',', '.') }}</th>
                          </tr>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">CV</th>
                            <th style="border: 1px solid silver; padding: 10px;" width="25%">
                              {{ number_format($cv, 2, ',', '.') }}</th>
                          </tr>
                          <tr>
                            <th style="border: 1px solid silver; padding: 10px;" width="75%">Total</th>
                            <th style="border: 1px solid silver; padding: 10px;" id="value_order" width="25%">
                              {{ $new_sub_total }}
                            </th>
                          </tr>
                        </thead>
                      </table>


                      <p>Pay with:</p>
                      <select name="methodPayment" id="selectMethod" onchange="changeMetodo(event)"
                        style="font-size: 1rem"
                        class="form-control form-control-lg @error('payment') is-invalid @enderror" required>
                        <option value="">Choose a method</option>
                        @foreach ($metodos as $item)
                          <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                        <option value="BTC">BTC</option>
                        <option value="ETH">ETH</option>

                        {{-- @if ($availableComission > 0) --}}
                        <option style="display: none" value="comission" id="paycomission">E-wallet</option>
                        {{-- @endif --}}
                        {{-- <option value="USDT">Buy Now (USDT ERC20) </option> --}}
                      </select>

                      <input type="text" style="display: none" id="request_price" name="price"
                        value="{{ $new_sub_total }}">
                      <input type="text" style="display: none" id="input_vat" name="total_vat"
                        value="{{ $total_VAT }}">

                      <input type="text" style="display: none" id="total_shipping" name="total_shipping"
                        value="{{ $priceShippingHome }}">

                      <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                        <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                        <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
                          I Agree with
                          <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                            Terms and Agreement</a>
                        </label>
                      </div>
                      {{-- @if ($qv >= 100) --}}
                      </br>Click here to place a smartship from this order. Smartship is a convenient, automatic monthly
                      shipment of your favorite Lifeprosper Products.</br>

                      {{-- @if (isset($userCorporate) && $userCorporate)
                        <button @if (isset($btnSmart) && $btnSmart == 'smart') disabled @endif class="btn btn-primary btn-lg"
                          style="display: none;" id="button-buy">Place purchase
                          order</button>

                        <button class="btn btn-primary btn-sm" style="margin-top: 10px; display: none;"
                          id="button-buy-smartshipping" disabled>Place Smartshipping (card only)</button>
                      @else --}}
                      <button @if (!isset($btnSmart) && $btnSmart != 'smart') disabled @endif class="btn btn-primary btn-sm"
                        style="margin-top: 10px; display: none;" id="button-buy-smartshipping">Place Smartshipping
                        (card only)</button>

                      {{--  @endif --}}
                      <button @if (isset($btnSmart) && $btnSmart == 'smart') disabled @endif class="btn btn-primary btn-lg"
                        style="display: none;" id="button-buy">Buy as Regular
                        Order</button>
                      {{-- @endif --}}


                    </div>
                  </div>
                </div>
              </div>
            </form>
          @endif
        </div>

        <div class="container-fluid bloco-footer">
          <div class="row">
            <div class="col-4">
              <a href="{{ route('packages.newProcessPackage3', $package_choosed->id) }}"><button
                  class="btn btn-primary btn-lg" style="width: 100%; font-size: 13px;" type="button">Keep
                  shopping</button></a>
            </div>
            {{-- @if ($count_itens > 0)
              <div class="col-4">
                <a href="{{ route('packages.clear_cart_buy') }}"><button class="btn btn-primary btn-lg"
                    style="width: 100%; font-size: 13px;" type="button">Clear cart</button></a>
              </div>
            @endif --}}
          </div>
        </div>
      </div>
    </section>

  </main>

  <script src="{{ asset('js/intlTelInput.min.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}" />

  <script>
    $(document).ready(function() {
      $('#selectCountry').on('change', function(e) {
        atualizaPreco();
        chooseCountryCellByCountryInput(e);
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
      // var code = ('charCode' in e) ? e.charCode : e.keyCode;
      // if (!(code > 47 && code < 58)) { // numeric (0-9)
      //   e.preventDefault();
      // }
    }

    var inputCell = document.querySelector("#cell");
    let codeCell = window.intlTelInput(inputCell, {
      separateDialCode: true
    });
    inputCell.addEventListener("countrychange", function() {
      // console.log(codeCell.getSelectedCountryData());
      document.querySelector("#countryCodeCell").value = codeCell.getSelectedCountryData().dialCode;
    });

    function useBalance(total) {
      let balance = {{ $availableComission }}

      // console.log(balance);


      if (balance >= total) {
        document.getElementById('paycomission').style.display = 'initial';
      } else {
        document.getElementById('paycomission').style.display = 'none';
      }
    }

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

      tax1add = parseFloat(tax1add);

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

    document.getElementById('id-novo-endereco').addEventListener("click", function() {
      document.getElementById('formHome2').style.display = "block";
      document.getElementById('formHome1').style.display = "none";
      document.getElementById('wpickup').style.display = "none";
    });

    document.getElementById('selectCountry').addEventListener("change", function() {

      select = document.getElementById('selectCountry').value;
      paises = @json($countryes);
      let frete = 0;

      paises.forEach(element => {
        if (element.country == select) {

          newtax_add = addNewTax(select)
          new_vat = calcularVAT(select);
          frete = parseFloat(newtax_add) + parseFloat(<?php echo $semVat; ?>) + parseFloat(new_vat);

          document.getElementById('total_shipping').value = newtax_add;
          document.getElementById('input_vat').value = parseFloat(new_vat)

          // newtax_add = newtax_add.toLocaleString('pt-BR', {
          //   minimumFractionDigits: 2,
          //   maximumFractionDigits: 2,
          //   useGrouping: true,
          // });

          document.getElementById('p_vat').innerHTML = parseFloat(new_vat).toFixed(2);
          document.getElementById('p_shipping').innerHTML = "€" + newtax_add;
          document.getElementById('shipping_table').innerHTML = newtax_add;
        }
      });

      useBalance(frete);
      const numeroFormatado = frete.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = numeroFormatado;
      document.getElementById('button-buy-smartshipping').style.display = 'initial'
      document.getElementById('button-buy').style.display = 'initial'
    })



    function atualizaPreco() {
      select = document.getElementById('selectCountry').value;
      paises = @json($countryes);
      let frete = 0;

      paises.forEach(element => {
        if (element.country == select) {

          newtax_add = addNewTax(select)
          new_vat = calcularVAT(select);
          frete = parseFloat(newtax_add) + parseFloat(<?php echo $semVat; ?>) + parseFloat(new_vat);
          document.getElementById('input_vat').value = parseFloat(new_vat)

          document.getElementById('total_shipping').value = newtax_add;

          // newtax_add = newtax_add.toLocaleString('pt-BR', {
          //   minimumFractionDigits: 2,
          //   maximumFractionDigits: 2,
          //   useGrouping: true,
          // });

          let n = parseFloat(newtax_add)


          document.getElementById('p_vat').innerHTML = parseFloat(new_vat).toFixed(2);
          document.getElementById('lognPrice').innerHTML = `€${n}`;
          document.getElementById('shipping_table').innerHTML = n;
        }

        document.getElementById('button-buy-smartshipping').style.display = 'initial'
        document.getElementById('button-buy').style.display = 'initial'
      });

      useBalance(frete);
      const numeroFormatado = frete.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = numeroFormatado;
    }

    let btn_smart = document.getElementById('button-buy-smartshipping');
    if (btn_smart) {
      document.getElementById('button-buy-smartshipping').addEventListener('click', () => {
        meuForm = document.getElementById('form-checkout-backoffice');
        meuForm.action = "{!! route('packages.smartshipping') !!}";
      })

    }

    document.getElementById('button-buy').addEventListener('click', () => {
      meuForm = document.getElementById('form-checkout-backoffice');
      meuForm.action = "{!! route('packages.newProcessPackage5') !!}";
    })
    // Get the modal elements
    var modalOverlay = document.querySelector(".modal-overlay");
    var modalBox = document.querySelector(".modal-box");
    var closeButton = document.querySelector("#close-modal-button");

    // Get the button that opens the modal
    var modalButton = document.querySelector("#btn-wpickup");

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

    document.addEventListener(
      "ppl-parcelshop-map",
      (event) => {
        // attribute
        document.getElementById('name_ppl_selected').style.display = 'block';
        document.getElementById('name_ppl_selected').innerHTML = `Selected ${event.detail.name}`;

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

        document.getElementById('address1').style.display = 'initial';
        document.getElementById('address1').innerHTML =
          `Pickup: ${event.detail.name} - ${event.detail.street}, ${event.detail.city} ${event.detail.zipCode}`;

        modalOverlay.style.display = "none";
        modalBox.style.display = "none";

        document.getElementById('button-buy-smartshipping').style.display = 'initial'
        document.getElementById('button-buy').style.display = 'initial'

        atualizaPrecoPickup(event.detail.country);
      }
    );

    let nAdress = document.getElementById('formHome2').style;
    let wpickup = document.getElementById('wpickup').style;

    function atualizaPreco() {
      select = document.getElementById('selectCountry').value;
      paises = @json($countryes);
      let frete = 0;

      paises.forEach(element => {
        if (element.country == select) {

          newtax_add = addNewTax(select)
          new_vat = calcularVAT(select);
          frete = parseFloat(newtax_add) + parseFloat(<?php echo $semVat; ?>) + parseFloat(new_vat);
          document.getElementById('input_vat').value = parseFloat(new_vat)

          document.getElementById('total_shipping').value = newtax_add

          // newtax_add = newtax_add.toLocaleString('pt-BR', {
          //   minimumFractionDigits: 2,
          //   maximumFractionDigits: 2,
          //   useGrouping: true,
          // });

          let n = parseFloat(newtax_add)
          document.getElementById('p_vat').innerHTML = parseFloat(new_vat).toFixed(2);
          document.getElementById('lognPrice').innerHTML = `€${n}`;
          document.getElementById('shipping_table').innerHTML = n;
        }

        document.getElementById('button-buy-smartshipping').style.display = 'initial'
        document.getElementById('button-buy').style.display = 'initial'
      });

      useBalance(frete);
      const numeroFormatado = frete.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = numeroFormatado;
    }


    function atualizaPrecoPickup(pais) {
      select = pais.toLowerCase();
      paises = @json($allPickup);
      let frete = 0;

      paises.forEach(element => {
        if (element.country_code.toLowerCase() == select) {
          newtax_add = precoPickup(pais)
          new_vat = calcularVAT(pais);
          frete = parseFloat(newtax_add) + parseFloat(<?php echo $semVat; ?>) + parseFloat(new_vat);

          document.getElementById('input_vat').value = parseFloat(new_vat)
          document.getElementById('total_shipping').value = newtax_add

          // newtax_add = newtax_add.toLocaleString('pt-BR', {
          //   minimumFractionDigits: 2,
          //   maximumFractionDigits: 2,
          //   useGrouping: true,
          // });

          let n = parseFloat(newtax_add)
          document.getElementById('p_vat').innerHTML = parseFloat(new_vat).toFixed(2);
          document.getElementById('logpickup').innerHTML = "€" + n;
          document.getElementById('shipping_table').innerHTML = "€" + n;


        }
      });

      useBalance(frete);
      const numeroFormatado = frete.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = numeroFormatado;
    }

    function alterarValor(valor) {
      // console.log('wopekf');
      let wpickup = document.getElementById('wpickup').style;
      let nAdress = document.getElementById('formHome2');
      if (valor == 'home') {

        nAdress.style.display = 'none';
        wpickup.display = 'none';
        frete = {{ $priceShippingHome + $subtotal }}
        document.getElementById('total_shipping').value = {{ $priceShippingHome }};
        document.getElementById('shipping_table').innerHTML = {{ $priceShippingHome }};

        document.getElementById('address1').style.display = 'none';

        document.getElementById('input_vat').value = "<?php echo $total_VAT ?? 0; ?>"
        document.getElementById('p_vat').innerHTML = "<?php echo $total_VAT ?? 0; ?>"
        document.getElementById('button-buy-smartshipping').style.display = 'initial'
        document.getElementById('button-buy').style.display = 'initial'

      } else if (valor == 'home2') {
        nAdress.style.display = 'block';
        document.getElementById('formHome1').style.display = "none";
        wpickup.display = 'none';
        document.getElementById('address1').style.display = 'none';
        document.getElementById('request_price').value = {{ number_format($subtotal, 2, '.', '') }};
        document.getElementById('value_order').innerHTML = {{ number_format($subtotal, 2, '.', '') }};

        return;
      } else {

        wpickup.display = 'block';
        nAdress.style.display = 'none';
        frete = {{ $subtotal }}
        document.getElementById('total_shipping').value = {{ $priceShippingPickup ?? 0 }};

        document.getElementById('button-buy-smartshipping').style.display = 'none'
        document.getElementById('button-buy').style.display = 'none'
      }

      useBalance(frete);

      const numeroFormatado = frete.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
        useGrouping: true,
      });

      document.getElementById('request_price').value = numeroFormatado;
      document.getElementById('value_order').innerHTML = numeroFormatado;
    }

    function submitCheckout(e) {

      if (document.getElementById('flexRadioDefault3').checked == false) {
        this.submit();
        return;
      } else {
        e.preventDefault();
      }

      if (document.getElementById('tel').value == null || document.getElementById('tel').value == '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('campo_cep').value == null || document.getElementById('campo_cep').value == '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('campo_endereco').value == null || document.getElementById('campo_endereco').value ==
        '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('n_residence').value == null || document.getElementById('n_residence').value == '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('campo_bairro').value == null || document.getElementById('campo_bairro').value == '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('campo_cidade').value == null || document.getElementById('campo_cidade').value == '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('campo_estado').value == null || document.getElementById('campo_estado').value == '') {
        alert('Fill in all fields');
        return;
      };

      if (document.getElementById('selectCountry').value == null || document.getElementById('selectCountry').value ==
        '') {
        alert('Fill in all fields');
        return;
      };
    }
  </script>


  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#form-checkout-backoffice').on('submit', function() {
        // Desabilita o botão após o envio do formulário
        $('#button-buy').prop('disabled', true);
        $('#button-buy-smartshipping').prop('disabled', true);
      });

      $(document).ready(function() {
        $('#diminuir_quant').on('click', function(event) {
          // Impede o comportamento padrão do link
          event.preventDefault();

          // Desabilita o link após o primeiro clique
          $(this).off('click'); // Remove o evento de clique
          $('#aumentar_quant').off('click'); // Remove o evento de clique
          $(this).hide();
          $('#aumentar_quant').hide();

          // Redireciona após um pequeno atraso (por exemplo, 1 segundo)
          var href = $(this).attr('href');
          setTimeout(function() {
            window.location.href = href; // Redireciona para o href após 1 segundo
          }, 500); // Tempo em milissegundos (1000 ms = 1 segundo)
        });
      });

      $(document).ready(function() {
        $('#aumentar_quant').on('click', function(event) {
          // Impede o comportamento padrão do link
          event.preventDefault();

          // Desabilita o link após o primeiro clique
          $(this).off('click'); // Remove o evento de clique
          $('#diminuir_quant').off('click');
          $(this).hide();
          $('#diminuir_quant').hide();

          // Redireciona após um pequeno atraso (por exemplo, 1 segundo)
          var href = $(this).attr('href');
          setTimeout(function() {
            window.location.href = href; // Redireciona para o href após 1 segundo
          }, 500); // Tempo em milissegundos (1000 ms = 1 segundo)
        });
      });

      $(document).ready(function() {
        $('#limpa_carrinho').on('click', function(event) {
          // Impede o comportamento padrão do link
          event.preventDefault();

          // Desabilita o link após o primeiro clique
          $(this).off('click'); // Remove o evento de clique
          $(this).hide();

          // Redireciona após um pequeno atraso (por exemplo, 1 segundo)
          var href = $(this).attr('href');
          setTimeout(function() {
            window.location.href = href; // Redireciona para o href após 1 segundo
          }, 500); // Tempo em milissegundos (1000 ms = 1 segundo)
        });
      });
    });
  </script>

@endsection
