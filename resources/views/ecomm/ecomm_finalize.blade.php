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

    .title-ecomm {
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

    .btn-finalize-pay {
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
        background: #212121;
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
                <p class="title-ecomm">Finalizar</p>
            </div>
        </div>

        {{-- <div class="col-12 box-register" id="box_pickup" style="display: blick; width:100%; height: ;">
        <div id="ppl-parcelshop-map"></div>
      </div> --}}
        {{-- <button id="modal-button">Open Modal</button> --}}

        <!-- Modal overlay -->
        <div class="modal-overlay" id="modal-overlay"></div>

        <!-- Modal box -->
        <div class="modal-box" id="modal-box" style="background-color: #f0f0f0;">
            <div id="ppl-parcelshop-map"></div>
            <h5 id="name_ppl_selected" style="display: none; background-color: #f0f0f0; font-size:2rem; color:green;">
                Selecionar
            </h5>
            <button id="close-modal-button" class="btn_address">Fechar</button>
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
                    @if (!$tipo && (!isset($user->address2) and empty($user->address2)))
                    <div class="band-registration">
                        <p class="subtitle_form">
                            Seu pedido será enviado para <strong id="address_delivery">{{ $user->address }},
                                {{ $user->neighborhood }} -
                                {{ $user->number }} {{ $user->zip }}</strong></p>

                    </div>
                    <div class="band-registration">
                        <p class="link-loginn">
                            <a href="{{ route('orders.settings.ecomm') }}" style="color: white" class="btn_address">
                                Editar endereço
                            </a>
                        </p>
                    </div>
                    @else
                    <div class="band-registration">
                        <p class="subtitle_form">
                            Seu pedido estará disponivel em sua conta após a confirmação do pagamento
                    </div>
                    @endif
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
                    <div class="small-form">
                        <input class="form-input-finalize" form="formCheckout" placeholder="Nome" id="" type="text"
                            name="first_name">
                    </div>
                    <div class="small-form">
                        <input class="form-input-finalize" form="formCheckout" placeholder="Sobrenome" id=""
                            type="text" name="last_name">
                    </div>
                    <div class="row-form">
                        <div class="small-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="Celular" id="tel"
                                type="number" name="phone">
                        </div>
                        <div class="min-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="Cep" oninput="cepValidate()"
                                aria-label="CEP" id="campo_cep" type="number" name="zip">
                        </div>
                        <div class="lowm-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="Endereço" id="campo_endereco"
                                type="text" name="address">
                        </div>
                        <div class="min-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="N° da residencia" type="number"
                                name="number">
                        </div>
                    </div>

                    <div class="row-form">
                        <div class="small-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="Complemento" type="text"
                                name="complement">
                        </div>
                        <div class="min-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="Bairro" id="campo_bairro"
                                type="text" name="neighborhood">
                        </div>
                        <div class="small-form">
                            <input class="form-input-finalize" form="formCheckout" placeholder="Cidade" id="campo_cidade"
                                type="text" name="city">
                        </div>
                        <div class="small-form">
                            <input type="text" name="country" value="BR" readonly class="form-input-finalize" form="formCheckout" placeholder="State">
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>


                @endif
                <div class="box-register-form">
                    <form action="{{ route('api.finalizeEcomm') }}" style="mt-[30px];" method="POST"
                        id="formCheckout">
                        @csrf
                        @if (isset($user))
                        <input type="text" name="id_user_login" id="id_user_login" value="{{ $user->id }}" style="display:none">
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
                            Infelizmente, ainda não enviamos para seu país.
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



                        @endif
                        <!-- CONTINUE FORM -->
                </div>
            </div>

            <div class="box-resume">
                <p class="subtitle_form" style="text-align: center">Seu pedido</p>
                <div class="box-products">
                    <p class="name-product-cart">{{ $count_order }} produtos</p>

                    {{-- PRODUCTS --}}
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
                            <p class="text-total">Produtos:</p>
                        </div>

                        <div class="camp-value-total">
                            <p class="text-value">{{ $count_order }}</p>
                        </div>
                    </div>
                    <div class="raw">
                        <div id="allowed-shipping-methods"></div>
                        <input type="hidden" id="send_method" name="send_method">
                        <input type="hidden" id="send_value" name="send_value">
                    </div>

                    <div class="raw">
                        <div class="camp-text-total">
                            <p class="text-total">Preço total:</p>
                        </div>

                        <div class="camp-value-total">
                            <p class="text-value" id="value_order">R$ {{ str_replace(['.', ','], ['', '.'], $format_price)}}</p>
                            <input class="text-value" id="request_price" name="price" value="{{ str_replace(['.', ','], ['', '.'], $format_price)}}"
                                style="display: none" />
                        </div>
                    </div>

                    <input type="text" name="method_shipping" value="home1" style="display: none;"
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
                        <input type="hidden" name="zipCode_ppl" id="zipCode_ppl" value="{{ $user->zip }}">
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
              <div class="form-group">
                <label class="form-check-label" for="flexRadioDefault1">
                  Metodo de pagamento
                </label>
                <select name="payment_method" id="" class="form-control">
                    <option value="mp">Mercado Pago</option>
                </select>
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
                <div class="form-group">
                <label class="form-check-label" for="flexRadioDefault1">
                  Metodo de pagamento
                </label>
                <select name="payment_method" id="" class="form-control">
                    <option value="paypal">Paypal</option>
                    <option value="mp">Mercado Pago</option>
                </select>
              </div>
                <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                    <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                    <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
                        Eu concordo com os
                        <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                            termos</a>
                    </label>
                </div>
                <button class="btn-finalize-pay" onclick="submitMethod()"
                    id="bt_submit">Pagar</button>


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
        document.getElementById('formCheckout').addEventListener('submit', event => {
            event.preventDefault();
        })
        window.location = "{!! route('finalize.shop.smart') !!}"
        // if (document.getElementById("radio_method_payment" == 'home2')) {
        //    document.getElementById('formCheckout').submit();
        // }

        // if (selectMethod.value == '' || selectMethod.value == null) {
        //   bt_submit.style.opacity = .5;
        //   bt_submit.disabled = true;
        // } else {}
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
            let url = "{!! route('api.finalizeEcomm') !!}"
            document.getElementById('formCheckout').action = url;
        }
    }

    function alterarValor(valor) {

        if (valor == 'home1') {
            document.getElementById('radio_method_payment').value = 'home1';
            frete = {
                {
                    $priceShippingHome + $subtotal
                }
            }
            document.getElementById('p_shipping').innerHTML = "R$ " + {
                {
                    $priceShippingHome
                }
            };
            document.getElementById('total_shipping').value = {
                {
                    $priceShippingHome
                }
            };
            document.getElementById('text_my_address_pickup').style.display = 'none';
            document.getElementById('input_vat').value = "<?php echo $total_VAT; ?>"
            document.getElementById('span_vat').innerHTML = "<?php echo $total_VAT; ?>"

        } else if (valor == 'home2') {

            document.getElementById('radio_method_payment').value = 'home2';
            document.getElementById('request_price').value = {
                {
                    $subtotal
                }
            };
            document.getElementById('value_order').innerHTML = "R$ " + {
                {
                    $subtotal
                }
            };
            document.getElementById('text_my_address_pickup').style.display = 'none';
            document.getElementById('p_shipping').innerHTML = "R$ " + {
                {
                    0
                }
            };
            return;
        } else if (valor == 'pickup') {
            document.getElementById('radio_method_payment').value = 'pickup';
            document.getElementById('p_shipping').innerHTML = "R$ " + {
                {
                    0
                }
            };
            document.getElementById('total_shipping').value = {
                {
                    0
                }
            };
            frete = {
                {
                    0 + $subtotal
                }
            }
        }

        const numeroFormatado = frete.toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
            useGrouping: true,
        });

        document.getElementById('request_price').value = numeroFormatado;
        document.getElementById('value_order').innerHTML = "R$ " + numeroFormatado;
    }
</script>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="/js/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
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
<script defer>
    function changeSendMethod(methodID, methodPrice) {
        $('#send_method').val(methodID);
        $('#send_value').val(methodPrice);
        $('#value_order').text(parseFloat(parseFloat(methodPrice) + parseFloat($('#request_price').val())));
    }

    function calculateShippiment() {
        // $('.loading-shipping').each((index, element) => {
        //     element.disabled = true;
        // })
        // $('#button-buy').attr("disabled", true);
        const formData = {
            zip_code: $('#zipCode_ppl').val(),
        }

        axios.post(`/api/ecomm/calculate`, formData).then((response) => {
            const shippingMethods = response.data
            $('#allowed-shipping-methods').html('<p class="mt-0 mb-2 card-title-new text-start">Entrega feita por:</p>')

            shippingMethods.forEach((method) => {
                $('#allowed-shipping-methods').append(`
                    <div class="d-flex align-items-center"><input type="radio" name="send_selected" value="${method.price}" id="send_by_${method.id}" class="mr-2 form-check-input" onchange="changeSendMethod(${method.id}, ${method.price})" required/> <label class="mb-0 ms-2" for="send_by_${method.id}"><p class="text-total mb-0">${method.name} R$ ${parseFloat(method.price)}</p></label></div>
                `)
            })

            $('.loading-shipping').each((index, element) => {
                element.disabled = false;
            })
            $("#button-buy").attr("disabled", false);


        })
    }
    @if (!$tipo)
        calculateShippiment()
    @endif

</script>

@endsection
