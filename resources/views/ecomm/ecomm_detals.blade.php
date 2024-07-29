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
      padding: 5%;
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

    @media all and (min-width:2px) and (max-width:820px) {
      .img-detals {
        width: 100%;
        padding: 40px;
        float: left;
        display: inline-block;
        background: #ffffff;
        margin-bottom: 30px;
        border-radius: 5px;
        box-shadow: 2px 2px 20px 3px rgba(0, 0, 0, 0.1);
      }

      .info-detals {
        width: 100%;
        float: left;
        padding-left: 0%;
        display: inline-block;
      }

      .moldure {
        width: 100%;
        height: auto;
        background-size: 100%;
        overflow: hidden;
        cursor: pointer;
      }
    }

    .container-product {
      background-color: #ffffff;
      display: flex;
      width: 100%;
      padding: 1rem;
    }



    .img-product {
      width: 40%;
      min-width: 40%;
      order: 1;
    }

    .info-product {
      width: 35%;
      min-width: 35%;
      order: 2;
    }

    .buy-product {
      width: 25%;
      min-width: 25%;
      order: 3;
    }


    .img-product {
      margin-bottom: 0;
      height: fit-content !important;
      background-color: #ffffff;
      display: flex;
      flex-direction: column;
      gap: .05rem;
    }

    .img-product>a img {
      max-width: 100%;
      width: 450px;
    }

    p.title-ecomm {
      font-size: 30px;
      margin-left: 0px;
      font-weight: bold;
      color: #212121;
    }

    .img-product>div {
      max-width: 100%;
      display: flex;
      overflow-x: auto;
      max-height: 150px
    }

    .img-product>div img {
      max-width: 100%;
      width: 100px;
    }

    .info-product {
      padding: 2rem;
      /* width: max-content !important; */
    }

    .info-product h3 {
      font-size: 2.5rem;
      font-weight: 500;
    }

    .buy-product {
      padding: 2rem;
      border: #d4d4d4 1px solid;
      border-radius: 10px;
      /* height: fit-content; */
    }

    @media all and (max-width:820px) {
      .container-product {
        flex-direction: column;
      }

      .container-product>div {
        width: 100%;
      }

      .img-product>a {
        display: flex;
        justify-content: center;
      }


      .buy-product {
        width: 25%;
        min-width: 25%;
        order: 2;
      }

      .info-product {
        width: 35%;
        min-width: 35%;
        order: 3;
      }

      .price-1 {
        display: none;
      }

      .price-2 {
        display: flex !important;
        flex-direction: column;
      }
    }

    .more-products {
      display: flex;
      align-items: center;
      flex-direction: column;
      width: 100%;
      gap: 1rem;

    }

    .categories {
      width: 100%;
      display: flex;
      justify-content: center;
      gap: .5rem;
      overflow-x: auto;
    }

    .categories>button {
      width: fit-content !important;
      background: #ffffff;
      border: #d26075 1px solid;
      color: #d26075;
    }

    .first-cat,
    .categories>button:hover,
    .categories>button:focus,
    .categories>button:active {
      width: fit-content !important;
      background: #d26075 !important;
      border: #fff 1px solid;
      color: #fff !important;
    }

    .products>div {
      width: 100%;
      display: flex;
      justify-content: center;
      gap: 1rem;
      flex-wrap: wrap;
    }

    .card img {
      height: 200px !important;
    }

    @keyframes zoom {
      0% {
        width: 100%;
      }

      100% {
        width: 95%;
      }
    }
  </style>

  <main id="main" style="display: none;" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%; height: 50vh; background: #d26075;">
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

      <div class="raw">
        <div class="band-title">
          <a href="{{ route('ecomm') }}">
            <p class="back-button"><i class="fi fi-rr-arrow-left"></i> Back to products</p>
          </a>
        </div>
      </div>

      <div class="raw" style="margin-top: 20px;">
        <div class="raw">
          <div class="band-title">
            <p class="title-ecomm">Most recently viewed</p>
          </div>
          <div class="band-quantify">
            <p class="count-ecomm">
            </p>
          </div>
        </div>

        <div class="autoplay">
          @foreach ($allprod as $prod)
            <a href="{{ route('detals', ['id' => $prod->id]) }}">
              <img style="width:350px;height:250px;" class='imagem_zoon' src='/img/products/{{ $prod->img_1 }}'>
            </a>
          @endforeach
        </div>

        @php
          $qv = $product->qv;
          $qv_format = number_format($qv, 2, ',', '.');
        @endphp

        <div class="container-product">
          <div class="img-product">
            <a data-src="/img/products/{{ $product->img_1 }}" data-fancybox="gallery">
              <img id="image1" class='' src='/img/products/{{ $product->img_1 }}'>
            </a>
            <div>
              @if (isset($product->img_2) and !empty($product->img_2))
                {{-- <img class='' src='/img/products/{{ $product->img_2 }}'> --}}

                <a data-src="/img/products/{{ $product->img_2 }}" data-fancybox="gallery">
                  <img style="height:90px;min-height:90px" src="/img/products/{{ $product->img_2 }}" />
                </a>
              @endif

              @if (isset($product->img_3) and !empty($product->img_3))
                <a data-src="/img/products/{{ $product->img_3 }}" data-fancybox="gallery">
                  <img style="height:90px;min-height:90px" src="/img/products/{{ $product->img_3 }}" />
                </a>

                {{-- <img class='' src='/img/products/{{ $product->img_3 }}'>> --}}
              @endif

              @if (isset($product->video) and !empty($product->video))
                <a data-src="/img/products/{{ $product->video }}" data-fancybox="gallery">
                  <img style="height:90px;min-height:90px" src="/img/products/{{ $product->video }}" />
                </a>

                {{-- <img class='' src='/img/products/{{ $product->video }}'> --}}
              @endif
            </div>
          </div>
          <div class="info-product">
            <div class="price-1">
              <h5 class="tittle-name">{{ $product->name }}</h5>
              <h3 class="text-price">€ {{ $product->price }}</h3>
              <h6 style="color: #51185D; font-size:1ren;">QV {{ $qv_format }}</h6>
              @if (isset($product->premium_price))
                <h6 class="text-price" style="font-size: 15px">Smartship Price: €{{ $product->premium_price }}</h6>
              @endif
            </div>

            @if (!empty($product->description))
              <p class="">{!! $product->description !!}</p>
            @else
              <p class="">Product without description</p>
            @endif
            @switch($product->id)
              @case(9)
                <a style="font-size: 1rem;color:#d26075;text-decoration: underline;" class="button-cart"
                  href="{{ route('true_omega') }}">See
                  more...</a>
              @break

              @case(10)
                <a style="font-size: 1rem;color:#d26075;text-decoration: underline;" class="button-cart"
                  href="{{ route('melatonin') }}">See more...</a>
              @break

              @case(11)
                <a style="font-size: 1rem;color:#d26075;text-decoration: underline;" class="button-cart"
                  href="{{ route('test_kit') }}">See more...</a>
              @break

              @default
            @endswitch
          </div>
          <div class="buy-product">
            <div class="price-2" style="display: none;">
              <h5 class="tittle-name">{{ $product->name }}</h5>
              <h3 class="text-price">€ {{ $product->price }}</h3>
              <h6 style="color: #51185D; font-size:1ren;">QV {{ $qv_format }}</h6>
              @if (isset($product->premium_price))
                <h6 class="text-price" style="font-size: 15px">Smartship Price: €{{ $product->premium_price }}</h6>
              @endif
            </div>

            <form action="{{ route('add.cart.ecomm') }}" method="POST">
              @csrf
              <div class="raw">

                @if ($product->stock > 0)
                  <p style="color: rgb(0, 202, 84);">Stock available</p>
                  <input class="quantify-buttom" name="quant_product" value="1" type="number"
                    max="{{ $product->stock }}" maxlength="{{ $product->stock }}" id="quant_product"
                    style="width: 100%">
                  <input name="id_product" value="{{ $product->id }}" style="display: none">
                @else
                  <p style="color: red;">No stock for this product</p>
                @endif
              </div>
              @if ($product->stock > 0)
                <div class="raw">
                  <button class="button-cart">Add to Cart</button>
                </div>
              @endif
            </form>
          </div>
        </div>

      </div>

      @if (isset($categorias) && count($categorias) > 0)

        <div class="more-products" style="margin-top: 2rem;">

          <div class="categories">
            @foreach ($categorias as $chave => $item)
              @if ($chave == 0)
                <button id="button-cat-{{ $item->id }}" class="btn-cat button-cart first-cat"
                  onclick="clickCat(event)">{{ $item->nome }}</button>
              @else
                <button id="button-cat-{{ $item->id }}" class="btn-cat button-cart"
                  onclick="clickCat(event)">{{ $item->nome }}</button>
              @endif
            @endforeach
          </div>
          <div class="products">
            @foreach ($categorias as $chave => $cat)
              @if ($chave == 0)
                <div class="section-cat" id="section-cat-{{ $cat->id }}">
                @else
                  <div class="section-cat" id="section-cat-{{ $cat->id }}" style="display: none">
              @endif

              @foreach ($cat->products as $item)
                <a href="{{ route('detals', ['id' => $item->id]) }}" class="col-3">
                  <div class="card">
                    <img src="/img/products/{{ $item->img_1 }}" class="card-img-top" alt="...">
                    <div class="card-body">
                      <p class="card-text">
                        {{ $item->name }}
                        <br>
                        ${{ $item->price }}
                      </p>
                    </div>
                  </div>
                </a>
              @endforeach
          </div>
      @endforeach

      </div>

      @endif
      </div>
    </section>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

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
    Fancybox.bind('[data-fancybox]', {});

    function clickCat(event) {

      let idBtn = event.target.id.replace("button-cat-", "");

      let allBtn = document.querySelectorAll('.btn-cat');
      allBtn.forEach(element => {
        element.classList.remove('first-cat');
      });

      let allSection = document.querySelectorAll('.section-cat');
      allSection.forEach(element => {
        element.style.display = 'none';
      });

      document.getElementById(`section-cat-${idBtn}`).style.display = 'flex';

    }
  </script>
  <script>
    const imgs = document.querySelectorAll('.img-select a');
    const imgBtns = [...imgs];
    let imgId = 1;

    imgBtns.forEach((imgItem) => {
      imgItem.addEventListener('click', (event) => {
        event.preventDefault();
        imgId = imgItem.dataset.id;
        slideImage();
      });
    });

    function slideImage() {
      const displayWidth = document.querySelector('.img-showcase img:first-child').clientWidth;

      document.querySelector('.img-showcase').style.transform = `translateX(${- (imgId - 1) * displayWidth}px)`;
    }

    window.addEventListener('resize', slideImage);

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

    document.getElementById("quant_product").addEventListener("change", ({
      target
    }) => {
      if (target.value > {{ $product->stock }}) {
        target.value = {{ $product->stock }};
      }
    })
  </script>

@endsection
