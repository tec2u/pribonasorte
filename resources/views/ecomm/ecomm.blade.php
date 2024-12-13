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

    .card-ecomm {
      width: 23%;
      height: fit-content;
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
      height: fit-content;
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
      height: 250px;
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
      border: 1px #d26075 solid;
      margin-top: 30px;
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
      border: 1px #d26075 solid;
      margin-top: 30px;
      font-size: 14px;
      transition: 200ms linear;
    }

    .band-title {
      width: 80%;
      float: left;
      display: inline-block;
    }

    .band-quantify {
      width: 20%;
      float: left;
      display: inline-block;
    }

    p.title-ecomm {
      font-size: 40px;
      margin-left: 11px;
      font-weight: bold;
      color: #212121;
    }

    p.count-ecomm {
      float: right;
      font-size: 16px;
      margin-right: 11px;
      margin-top: 20px;
    }

    .text-price {
      font-size: 1rem;
    }

    .text-price-bottom {
      font-size: 12px;
    }

    @media all and (min-width:2px) and (max-width:820px) {
      .card-ecomm {
        width: 100%;
        display: inline-block;
        background: #ffffff;
        margin: 0px 0px;
        border-radius: 5px;
        border: 1px solid #eeeeee;
        float: left;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 2px 2px 5px 1px rgba(0, 0, 0, 0.1);
        transition: 200ms linear;
      }

      p.title-ecomm {
        font-size: 30px;
        margin-left: 0px;
        font-weight: bold;
        color: #212121;
      }

      .band-title {
        width: 60%;
        float: left;
        display: inline-block;
      }

      .band-quantify {
        width: 40%;
        float: left;
        display: inline-block;
      }

      p.count-ecomm {
        float: right;
        font-size: 16px;
        margin-right: 0px;
        margin-top: 10px;
      }
    }

    #background-primary {
      height: 100%;
    }

    .container-ecomm {
      height: 100%;
    }

    button.button-cart {
      width: fit-content;
      height: 45px;
      border-radius: 5px;
      background-color: #d26075;
      color: #ffffff;
      font-size: 14px;

      font-weight: bold;
      border: 1px #d26075 solid;
      text-transform: uppercase;
      transition: 200ms linear;
    }

    button.button-cart:hover {
      width: fit-content;
      height: 45px;
      border-radius: 5px;
      background-color: #eeeeee;
      color: #d26075;
      font-size: 14px;
      /* margin-top: 30px; */
      font-weight: bold;
      border: 1px #d26075 solid;
      text-transform: uppercase;
      transition: 200ms linear;
    }

    .quantify-buttom {
      width: 100px;
      height: 40px;
      border-radius: 100px;
      padding: 10px;
      border: 1px solid #919191;
      margin-top: 20px;
    }

    .container-products {
      display: flex;
      width: 100% !important;
      justify-content: center;
      align-items: flex-start;
      gap: 1rem;
    }

    .filter {
      width: 30%;
      display: flex;
      flex-direction: column;
      gap: .5rem;
      background-color: #fff;
      padding: 1rem;
    }

    .filter h2 {
      border-bottom: 1px solid #eee;
    }

    .products {
      border-radius: 10px;
      width: 70%;
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }

    .categories {
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }

    .categories>a {
      padding: .5rem;
      border-bottom: 1px solid #eee;
      background-color: #fdfdfd;
    }

    .categories>a:hover {
      width: 100%;
      background-color: #f1f1f1;
    }

    .product img {
      max-width: 100%;
      width: 200px;
    }

    .product {
      background-color: #ffffff;
      display: flex;
      gap: 1rem;
      border-bottom: #cecece 1px solid;
      cursor: pointer;
      border-radius: 10px;
    }

    .product:hover {
      transform: scale(1.05);
      transition: 200ms;
    }

    .text-product {
      padding: 1rem;
      display: flex;
      flex-direction: column;
      gap: 2rem;
      width: 100%
    }

    .text-product a {
      color: #d26075;
      font-weight: 800;
    }


    .text-product>div {
      display: flex;
      justify-content: space-between;
      width: 60%;
    }

    .prices {
      color: #d26075;
    }

    @media all and (max-width:820px) {
      .container-products {
        flex-direction: column;
      }

      .filter {
        width: 100% !important;
        padding: 1rem;
      }

      .categories {
        flex-direction: row;
        overflow-x: auto;
        gap: .5rem;
        width: 100% !important;
      }

      .products {
        width: 100%
      }

      .text-product>div {
        width: 90%;
        flex-wrap: wrap
      }

    }
  </style>

  <main id="main" style="display: none;" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%; height: 10vh; background: #d26075;">
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
      @php
        $categorias = Illuminate\Support\Facades\DB::table('categorias')->get();
      @endphp

      <div class="raw" style="margin-top: 20px;">
        <div class="autoplay" style="margin: 1rem auto;">
          @foreach ($allprod as $product)
            <a href="{{ route('detals', ['id' => $product->id]) }}">
              <img style="width:350px;height:250px;" class='imagem_zoon' src='/img/products/{{ $product->img_1 }}'>

            </a>
          @endforeach
        </div>

        <div class="container-products">
          <div class="filter">
            <h2>
              Categorias
            </h2>

            <div class="categories">
              <a href="{{ route('ecomm') }}" style="color: #000">
                Todos os produtos
              </a>
              @if ($categorias->count() > 0)
                @foreach ($categorias as $cat)
                  <a href="{{ route('ecomm.categoria', $cat->id) }}" style="color: #000">
                    <span style="text-transform: uppercase">{{ $cat->nome }}</span>
                  </a>
                @endforeach
              @endif
            </div>
          </div>
          <div class="products">


            @foreach ($products as $product)
              @php
                $price = $product->price;
                $priceTax = 0;

                $qv = $product->qv;
                $qv_format = number_format($qv, 2, ',', '.');
              @endphp


              <div class="product">
                <div class="">
                  <a href="{{ route('detals', ['id' => $product->id]) }}">
                    <img class='imagem_zoon' src='/img/products/{{ $product->img_1 }}'>
                  </a>
                </div>
                <div class="text-product">
                  <a href="{{ route('detals', ['id' => $product->id]) }}">
                    <h4>{{ $product->name }}</h4>
                  </a>
                  <div>
                    <div class="prices">
                      <h5 class="text-price">€{{ $price }} <strong style="font-size: .7rem"> (Exl. VAT)</strong>
                      </h5>
                      @if (isset($product->premium_price))
                        <h6 class="text-price-bottom">€{{ $product->premium_price }} --- Smartship</h6>
                      @endif
                    </div>

                    <div>
                      <form action="{{ route('add.cart.ecomm') }}" method="POST"
                        style="display: flex;flex-direction: row">
                        @csrf
                        @if ($product->stock > 0)
                          <div class="raw" style="display: none">
                            <input class="quantify-buttom" name="quant_product" value="1" type="number"
                              value="1" max="{{ $product->stock }}" maxlength="{{ $product->stock }}"
                              id="quant_product">
                            <input name="id_product" value="{{ $product->id }}" style="display: none">
                          </div>
                        @else
                          <p style="color: red;">Sem estoque</p>
                        @endif
                        <div class="raw">
                          @if ($product->stock > 0)
                            <button class="button-cart">Adicionar ao carrinho</button>
                          @endif
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>


    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js"
    integrity="sha512-HGOnQO9+SP1V92SrtZfjqxxtLmVzqZpjFFekvzZVWoiASSQgSr4cw9Kqd2+l8Llp4Gm0G8GIFJ4ddwZilcdb8A=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script src="/js/script.js"></script>

  <script>
    $('.autoplay').slick({
      slidesToShow: 5,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
    })
  </script>

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
