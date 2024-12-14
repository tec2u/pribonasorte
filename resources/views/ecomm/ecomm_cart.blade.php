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
      padding: 4%;
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

    p.title-ecomm {
      font-size: 35px;
      font-weight: bold;
      color: #212121;
    }

    .box-cart-line1 {
      border-radius: 10px;
      background-color: #ffffff;
      border: 1px solid #eeeeee;
      border-bottom: 0px;
      padding: 30px;
      width: 70%;

      box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }

    .box-cart-line2 {
      border-radius: 10px;
      background-color: #fafafa;
      border: 1px solid #eeeeee;
      border-bottom: 0px;
      padding: 1rem;
      width: 25%;
      height: fit-content;

      box-shadow: rgba(149, 157, 165, 0.2) 0px 8px 24px;
    }

    .box-cart-line2 h5 {
      font-weight: 700;
    }

    .container-cart {
      display: flex;
      justify-content: space-between;
      flex-wrap: wrap;
    }

    table.table-cart {
      width: 100%;
    }

    .line-table {
      width: 100%;
      display: inline-block;
    }

    p.title-column {
      font-weight: bold;
      margin: 0px;
    }

    .box-amount {
      margin-top: 15px;
    }

    .input-amount {
      float: left;
      height: 30px;
      margin: 0px 5px;
      width: 50px;
      border-radius: 100px;
      text-align: center;
      border: solid #212121 1px;
    }

    .button-amount {
      float: left;
      width: 27px;
      height: 27px;
      border-radius: 100px;
      border: 0px;
      background: #d26075;
      color: #ffffff;
      transition: 200ms linear;
    }

    .button-amount:hover {
      float: left;
      width: 27px;
      height: 27px;
      border-radius: 100px;
      border: 0px;
      background: #cdcdcd;
      color: #d26075;
      transition: 200ms linear;
    }

    p.trash {
      float: right;
      margin-top: 30px;
      font-size: 18px;
      color: #d26075;
    }

    .total-column1 {
      width: 100%;
      display: flex;
      justify-content: space-between;
    }

    .total-column2 {
      width: 100%;
      display: inline-block;

    }

    .total-free1 {
      width: 100%;
      display: inline-block;

    }

    .total-free2 {
      width: 100%;
      display: inline-block;

    }

    .total-free3 {
      width: 15%;
      display: inline-block;

    }

    .text-column1 {
      font-weight: bold;
      font-size: 15px;

    }

    .text-column2 {
      font-weight: bold;
      font-size: 15px;

    }

    .box-clear-cart {
      width: 100%;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      gap: 1rem;
    }

    .box-clear-cart a {
      width: fit-content;
    }

    .box-clear-cart button {
      width: 25vw;
      max-width: 100%;
    }

    .box-finalize-cart {
      width: 100%;
    }


    button.clear-cart {
      /* width: 50%; */
      height: 50px;
      font-size: 15px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    button.clear-cart:hover {
      /* width: 50%; */
      height: 50px;
      font-size: 15px;
      border-radius: 5px;
      background: #DEEFFF;
      color: #000;
      font-weight: bold;
      border: 0px;
      transition: 200ms linear;
    }

    button.finalize-cart {
      height: 50px;
      font-size: 15px;
      border-radius: 5px;
      width: 100%;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
      float: right;
      border: 0px;
    }

    .input-shipping {
      width: 100%;
      height: 35px;
      border-radius: 5px;
      padding: 0px 10px;
      border: 1px solid #b3b3b3;
      outline: none;
      background: #ffffff;
    }

    .button-shipping {
      width: 100%;
      font-size: 13px;
      font-weight: bold;
      height: 35px;
      border: 0px 10px;
      margin-left: 10px;
      background: #d26075;
      border: 0px;
      border-radius: 5px;
      color: #ffffff;
    }

    .modal_popup {
      background-color: rgba(0, 0, 0, 0.1);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1000;
      display: none;
    }

    .pop_up_log {
      width: 400px;
      height: 200px;
      padding: 30px;
      border-radius: 20px;
      background-color: #ffffff;
      margin: 15% auto;
    }

    button.button_log_option {
      width: 100px;
      height: 40px;
      border-radius: 5px;
      background: #d26075;
      color: #ffffff;
      font-weight: bold;
    }

    ul.list_btn_logs {
      margin-left: -30px;
      margin-top: 20px;
    }

    ul.list_btn_logs li {
      margin: 0px 10px;
      display: inline-block;
    }

    p.text_pop_up {
      font-size: 20px;
      margin-top: 20px;
      font-weight: bold;
      color: #d26075;
    }

    @media all and (min-width:2px) and (max-width:820px) {
      p.title-ecomm {
        font-size: 30px;
        font-weight: bold;
        color: #212121;
      }


      .box-clear-cart a {
        width: 100%;
      }

      .box-clear-cart button {
        width: 100%;
      }

      #product {
        width: 35%;
      }

      #product p {
        display: none;
      }

      #value_product {
        width: 35%;
      }

      #unit {
        width: 0%;
      }

      #unit p {
        display: none;
      }

      #amount {
        width: 55%;
      }

      #value_amount {
        width: 55%;
      }

      #value_unit {
        width: 0%;
      }

      #value_unit p {
        display: none;
      }

      #name_product {
        display: none;
      }

      .text-column1 {}

      button.clear-cart {
        /* width: 80%; */
        height: 50px;
        font-size: 15px;
        border-radius: 5px;
        background: #d26075;
        color: #ffffff;
        font-weight: bold;
        border: 0px;
        transition: 200ms linear;
      }

      button.finalize-cart {
        height: 50px;
        font-size: 15px;
        border-radius: 5px;
        font-weight: bold;
        border: 0px;
      }

      .container-cart {
        flex-direction: column;
        gap: 1rem;
      }

      .container-cart>div {
        width: 100%;
      }
    }
  </style>

  <main id="main" style="display: none;" class="main p-0">
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
  </main>

  <main id="background-primary" style="margin-top: 30px;">
    <section class="container-ecomm">

      <div class="raw">
        <div class="band-title">
          <p class="title-ecomm">Carrinho</p>
          {{-- <a href="{{ route('clear.cart') }}">
            <p class="back-button"><i class="fi fi-rr-arrow-left"></i> Clear Cart</p>
          </a> --}}
        </div>
      </div>

      <div class="raw container-cart" style="margin-top: 20px;">
        <div class="box-cart-line1">
          <div class="line-table">
            <table class="table-cart">
              <tr>
                <td width="45%" id="product">
                  <p class="title-column">Produto</p>
                </td>
                <td width="10%" id="VAT">
                  <p class="title-column">Preço</p>
                </td>
                <td width="10%" id="unit">
                  <p class="title-column">VAT</p>
                </td>
                <td width="15%" id="amount">
                  <p class="title-column">Quantidade</p>
                </td>
                <td width="10%">
                  <p class="title-column">Preço total</p>
                </td>
                <td width="5%"></td>
              </tr>
            </table>
          </div>
          <hr>
          @foreach ($order_cart as $product_cart)
            {{-- @php
              dd($product_cart);
            @endphp --}}
            <div class="line-table">
              <table class="table-cart">
                <tr>
                  <td width="45%" id="value_product">
                    @php
                      $id_products = $product_cart->id_product;
                      $info_product = Illuminate\Support\Facades\DB::select(
                          "SELECT * FROM products WHERE id = '$id_products'",
                      );
                    @endphp
                    <table style="width: 100%;">
                      <td width="15%">
                        <p class="text-column"></p><img style="width: 50px;"
                          src="/img/products/{{ $info_product[0]->img_1 }}">
                      </td>
                      <td width="85%" id="name_product">
                        <p class="text-column" style="margin-top: 30px;">{{ $info_product[0]->name }}</p>
                      </td>
                    </table>
                  </td>
                  <td width="10%" id="value_VAT">
                    <p class="text-column" style="margin-top: 30px;">
                      R${{ number_format($product_cart->total, 2, ',', '.') }} </p>
                  </td>
                  <td width="10%" id="value_unit">
                    <p class="text-column" style="margin-top: 30px;">
                      R${{ number_format($product_cart->priceTax * $product_cart->amount, 2, ',', '.') }}</p>
                  </td>
                  <td width="15%" id="value_amount">
                    <div class="box-amount">
                      <a id="click-down-{{ $product_cart->id }}"
                        href="{{ route('down.amount', ['id' => $product_cart->id]) }}"><button
                          class="button-amount">-</button></a>
                      <input class="input-amount" type="text" value="{{ $product_cart->amount }}" readonly>
                      <a id="click-up-{{ $product_cart->id }}"
                        href="{{ route('up.amount', ['id' => $product_cart->id]) }}"><button
                          class="button-amount">+</button></a>
                    </div>
                  </td>
                  <td width="10%">
                    <p class="text-column" style="margin-top: 30px;">
                      R${{ number_format($product_cart->priceTax * $product_cart->amount + $product_cart->total, 2, ',', '.') }}
                    </p>
                  </td>
                  <td width="5%">
                    <a href="{{ route('delete.cart', ['id' => $product_cart->id]) }}">
                      <p class="trash"><i class="fi fi-ss-trash-xmark"></i></p>
                    </a>
                  </td>
                </tr>
              </table>
            </div>
          @endforeach
        </div>

        <div class="box-cart-line2">

          <h5>Resumo</h5>

          <div class="raw">
            <div class="total-free1">
              <form action="" method="POST" style="visibility: hidden;">
                @csrf
                <table style="width: 40%; margin-top: -8px;">
                  <tr>
                    <td width=""><input class="input-shipping" type="text"></td>
                    <td width=""><button class="button-shipping">Calculate shipping</button></td>
                  </tr>
                </table>
              </form>
            </div>
          </div>

          <div class="raw">
            <div class="total-column1">
              <p class="text-column1">QV:</p>
              <p class="text-column1">{{ number_format($qv, 2, ',', '.') }}</p>
            </div>
          </div>

          <div class="raw">
            <div class="total-column1">
              <p class="text-column1">Total VAT:</p>
              <p class="text-column1">R${{ $total_VAT }}</p>
            </div>
          </div>

          <div class="raw">
            <div class="total-column1">
              <p class="text-column1">Preço total:</p>
              <p class="text-column1">R${{ $format_price }}</p>
            </div>
          </div>

          <div class="box-finalize-cart">
            @if ($count_order > 0)
              @if (session()->has('buyer'))
                <a href="{{ route('finalize.shop') }}"><button class="finalize-cart">Continuar</button></a>
              @else
                <button id="poplog" class="finalize-cart">Continuar</button>
              @endif
            @endif
          </div>
        </div>

      </div>
      <div class="d-flex" style="margin-top: 30px; width: 100%">
        <div class="box-clear-cart">
          <a href="{{ route('clear.cart') }}"><button class="clear-cart">Limpar carrinho</button></a>

          <a href="{{ route('ecomm') }}"><button class="clear-cart">Voltar aos produtos</button></a>
        </div>
      </div>
      </div>
    </section>
  </main>

  <div class="modal_popup">
    <div class="pop_up_log">
      <center>
        <p class="text_pop_up">Você possui conta?</p>
        <ul class="list_btn_logs">
          <li><a href="{{ route('page.login.ecomm') }}"><button class="button_log_option">Sim</button></a></li>
          <li><a href="{{ route('register.user.ecomm') }}"><button class="button_log_option">Não</button></a></li>
        </ul>
      </center>
    </div>
  </div>

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

    $(document).ready(function() {
      $("#poplog").click(function(event) {
        event.preventDefault();
        $(".modal_popup").fadeIn();
      });
    });
  </script>

@endsection
