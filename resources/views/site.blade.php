@extends('layouts.header_page')
@section('title', 'Pribonasorte')
@section('content')
  <!--  -->
  <section id="background-top">
    <video autoplay muted loop class="bg_video" id="autoPlayForce">
      <source src="/videos/fitnessAuraWay.mp4" type="video/mp4">
    </video>
    <video autoplay muted loop class="bg_video_vertical" id="autoPlayForce2">
      <source src="/videos/fitnessAuraWay.mp4" type="video/mp4">
    </video>
    <!--  -->
    <div id="box-inf">
      <p id="title-welcome">Welcome!</p>
      <p id="title-landing">Pribonasorte</p>
      <center>
        <a href="{{ route('ecomm') }}">
          <button class="btn-shop-now">Shop now</button>
        </a>
      </center>
    </div>
  </section>
  <!--  -->
  <section id="background-primary">
    <div class="container" style="padding: 0px;">

      {{-- <center>
        <button type="button" id="button-lg" class="btn btn-secondary btn-lg">Every order saves 75 trees</button>
        <!--  -->
        <div class="row" style="margin-top: 0px;">
          <div class="col-5" id="wdt-l1">
            <hr>
          </div>
          <div class="col-2" id="wdt-c1">
            <p id="title-feat">Featured Brands</p>
          </div>
          <div class="col-5" id="wdt-r1">
            <hr>
          </div>
        </div>
      </center> --}}
      <!--  -->
      {{-- <div class="line-card" style="padding: 30px 0px 50px 0px;">
        <div class="card-item">
          <div style="background-image: url('/img/lifeprosprer_brands.png'); background-size: 100%;" class="card-img">
          </div>
          <div class="line-card-text">
            <p class="text-card">Lifeproper</p>
          </div>
        </div>
      </div> --}}
      <!--  -->

      <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel"
        style="margin-top: 40px; display: none;">
        <div class="carousel-indicators" id="indicators">
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
            aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner" id="line-winner">
          <div class="carousel-item active" id="line-actve" data-bs-interval="10000">
            <div class="line-card">
              <div class="card-item">
                <div style="background-image: url('/img/Biohack-brand-photo-HP-320.jpg'); background-size: 100%;"
                  class="card-img"></div>
                <div class="line-card-text">
                  <p class="text-card">Nuud edit</p>
                </div>
              </div>
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/Brilliant-Smile-brand-HP-320.jpg'); background-size: 100%;"></div>
                <div class="line-card-text">
                  <p class="text-card">Brilliant Smile edit</p>
                </div>
              </div>
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/Coffee-lab-brand-cover-HP-320.jpg'); background-size: 100%;"></div>
                <div class="line-card-text">
                  <p class="text-card">Foci</p>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item" data-bs-interval="2000">
            <div class="line-card">
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/Defendie-Brand-HP-320.jpg'); background-size: 100%;"></div>
                <div class="line-card-text">
                  <p class="text-card">Defendie</p>
                </div>
              </div>
              <div class="card-item">
                <div class="card-img" style="background-image: url('/img/Foci-brand-HP-320.jpg'); background-size: 100%;">
                </div>
                <div class="line-card-text">
                  <p class="text-card">ONEPan</p>
                </div>
              </div>
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/Hauger-brand-cover-HP-320.jpg'); background-size: 100%;"></div>
                <div class="line-card-text">
                  <p class="text-card">Hauger</p>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="line-card">
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/onepan-brand-page-320.jpg'); background-size: 100%;"></div>
                <div class="line-card-text">
                  <p class="text-card">Biohakk</p>
                </div>
              </div>
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/Saint-Roches-brand-cover-HP-400-1.jpg'); background-size: 100%;">
                </div>
                <div class="line-card-text">
                  <p class="text-card">Saint Rouchés</p>
                </div>
              </div>
              <div class="card-item">
                <div class="card-img"
                  style="background-image: url('/img/nuud-brand-page-320.jpg'); background-size: 125%;"></div>
                <div class="line-card-text">
                  <p class="text-card">Coffee Lab Stories</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <button id="btn-controll1" class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark"
          data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true" style="margin-left: -220px;"></span>
          <span class="visually-hidden">Previous</span>
        </button>
        <button id="btn-controll2" class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark"
          data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true" style="margin-right: -240px;"></span>
          <span class="visually-hidden">Next</span>
        </button>
      </div>
    </div>
  </section>
  <!--  -->
  <section id="section-secundary1" class="background-complete-bloco1 background-coastiline">
    <div class="box-form">
      <center>
        <div class="bar-top"></div>
      </center>
      <p class="text-subscript-block" style="text-transform: uppercase;">Elevate Your Health, Empower Your Life!</p>
      <center>
        <p style="font-size: 18px; color: #ffffff; margin-top: 30px;">Pribonasorte is your trusted destination for premium
          supplements and cutting-edge testing kits. Discover a world of wellness and unlock your full potential with our
          high-quality products and personalized solutions.</p>
      </center>
    </div>
  </section>

  <section style="width: 100%; height: 100vh; display: inline-block; background: url('/images/WhatsApp_Image_2023-10-03_at_15.53.29.jpeg'); background-size: cover;"></section>
  <!--  -->
  <section id="background-primary" style="display: none;">
    <div class="container">
      <center>
        <!--  -->
        <div class="row" style="margin-top: 0px;">
          <div class="col-5" id="wdt-l2">
            <hr>
          </div>
          <div class="col-2" id="wdt-c2">
            <p id="title-feat">Featured Products</p>
          </div>
          <div class="col-5" id="wdt-r2">
            <hr>
          </div>
        </div>
      </center>
    </div>
    <!--  -->
    <div class="row" id="line-products">
      @foreach ($products as $item)
        <div class="col-product">
          <div id="product{{ $item->id }}" class="img-product" {{-- @if (isset($item->img_2)) onmouseover="this.style.backgroundImage = 'url({!! asset('img/products/' . $item->img_2) !!})';" @endif --}} {{-- onmouseout="this.style.backgroundImage = 'url({!! asset('img/products/' . $item->img_1) !!})';" --}}
            style="background-image: url({{ asset('img/products/' . $item->img_1) }})">
            <style>
              .img-product {
                background-size: cover;
                background-position: center center;
                height: 250px;
                width: 250px;
                background-repeat: no-repeat;
                cursor: pointer;
                border: 1px #cdcdcd solid;
                transition: 2s;
              }
            </style>

            <a id="linkPage{{ $item->id }}"><button id="button-product1" class="btn-quickview">quick
                view</button></a>
          </div>
          <div class="info-product">
            <p class="name-product">{{ $item->name }}</p>

            <p class="title-feat resume{{ $item->id }}" style="font-weight: bold; text-transform: uppercase; "
              id="item_resume">
            </p>

            <p class="price-product">R${{ $item->price }}</p>
          </div>
          <div style="margin-top: 20px;height: 90px; overflow: hidden;" class="description{{ $item->id }}">
          </div>
          <a href="{{ route('detals', ['id' => $item->id]) }}">see more...</a>

          <script>
            window.addEventListener('DOMContentLoaded', (event) => {
              var id = `{!! $item->id !!}`;
              var htmlString = `{!! $item->resume !!}`;
              var htmlStringDescription = `{!! $item->description !!}`;
              var linkPageA = document.getElementById(`linkPage${id}`);
              htmlString = htmlString.replace(/<\/?[^>]+(>|$)/g, '');
              htmlString = htmlString.replace(/\s/g, '_');
              htmlString = htmlString.toLowerCase();

              linkPageA.setAttribute('href', `/shop/${htmlString}`);

              if ("{!! isset($item->img_2) !!}") {
                var img1Path = "{!! asset('img/products/' . $item->img_1) !!}";
                var img2Path = "{!! asset('img/products/' . $item->img_2) !!}";


                var divImg = document.getElementById(`product${id}`);

                divImg.addEventListener('mouseover', function() {
                  divImg.style.backgroundImage = "url('" + img2Path + "')";
                });

                divImg.addEventListener('mouseout', function() {
                  divImg.style.backgroundImage = "url('" + img1Path + "')";
                });
              }

              document.getElementsByClassName(`resume${id}`)[0].innerHTML = htmlString;
              document.getElementsByClassName(`description${id}`)[0].innerHTML = htmlStringDescription;
            });
          </script>
        </div>
      @endforeach

    </div>
  </section>

  <section style="width: 100%; display: inline-block; background: #ffffff; margin-top: -5px;">
    <div style="width: 45%; height: 100vh; float: left; display: inline-block; background-image: url('/images/WhatsApp_Image_2023-10-10_at_14.26.40.jpeg'); background-size: cover; background-position: center;">.</div>
    <div style="width: 55%; height: 100vh; float: left; display: inline-block; padding: 0px 5%;">
        <center>
            <p style="font-size: 25px; font-weight: bold; margin-top: 40%; magin-bottom: 30px;">Assess Your Omega-3 Balance Now!</p>
            <p style="color: #212121; font-size: 20px; line-height: 23px;">Revitalize Your Omega-3 Ratio with Pure Arctic Oil Derived from 100% Fresh, Traceable, and Sustainable Wild Cod Liver and Camelina Oil. Sourced off the Pristine Norwegian Coast.</p>
            <a href="/ecomm/detals/9"><button style="padding: 0px 30px 0px 30px; margin-top: 20px; background: #3D0A47; color: #ffffff; height: 50px; border-radius: 5px; font-size: 14px;">SHOP NOW</button></a>
        </center>
    </div>
  </section>
  <!--  -->
  <section id="section-secundary1" style="margin-top: -5px;">
    <div class="background-complete-bloco2"></div>
    <div class="box-form">
      <center>
        <div class="bar-top"></div>
      </center>
      <p class="text-subscript-block" style="text-transform: uppercase;">Unleash the Power of Science for Optimal
        Well-Being</p>
      <center>
        <p style="font-size: 18px; color: #ffffff; margin-top: 30px;">Get access to personalized health and wellness with
          Pribonasorte. Our mission is to empower individuals like you to take control of their well-being through a
          personal approach. By understanding your unique needs, we recommend the right products and tests to optimize
          your health journey.</p>
      </center>
    </div>
  </section>
  <!--  -->
  <section id="background-primary">
    <div class="container">
      <center>
        <!--  -->
        <div class="row" style="margin-top: 30px;">
          <div class="col-5" id="wdt-l3">
            <hr>
          </div>
          <div class="col-2" id="wdt-c3">
            <p id="title-feat">Consciousness Points</p>
          </div>
          <div class="col-5" id="wdt-r3">
            <hr>
          </div>
        </div>
        <div class="row-consciousness">
          <div class="col-consci">
            <i class="fa-solid fa-hands-holding-child" style="color: #1a1a1a; text-align: center; font-size: 70px;"></i>
            <p class="text-consciousness">Child Labour Free</p>
          </div>
          <div class="col-consci">
            <i class="fa-solid fa-dog" style="color: #1a1a1a; text-align: center; font-size: 70px;"></i>
            <p class="text-consciousness">No Animal Testing</p>
          </div>
          <div class="col-consci">
            <i class="fa-solid fa-compass" style="color: #1a1a1a; text-align: center; font-size: 70px;"></i>
            <p class="text-consciousness">High-Quality Standard</p>
          </div>
        </div>
        <a href="{{ route('about') }}"><button type="button" id="button-lg" class="btn btn-secondary btn-lg">read more</button></a>
      </center>
    </div>
  </section>
  <!--  -->
  <section class="section-brand" style="background: #ffffff;">
    <div class="box-text-brand">
      <center>
        <p class="text-subscript-brand">Transform Your Well-being with Personalized Solutions</p>
      </center>
      <center>
        <p class="text-brand">We are dedicated to being your trusted partner on your journey to optimal health. With our
          comprehensive range of supplements and testing kits, we provide the tools you need to take control of your
          well-being and unlock your true potential.</p>
      </center>
    </div>
  </section>
  <!--  -->
  <section class="section-new-block">
    <div class="master-block-info">
      <div class="block-master-l">
        <center>
          <p class="text-subscript-new-block">Experience a Revolution in Personalized Nutrition</p>
          <p class="text-prime-block">Pribonasorte brings you a range of personalized supplements and testing kits
            tailored to your unique needs. Discover the perfect balance of science and nature to nourish your body and
            thrive in every aspect of life.</p>
        </center>
      </div>
      <div class="block-master-r"></div>
    </div>
  </section>
  <!--  -->
  <section class="section-brand">
    <div class="box-text-brand">
      <center>
        <p class="text-subscript-brand">Your Partner in the Pursuit of Optimal Health</p>
      </center>
      <center>
        <p class="text-brand">We are dedicated to being your trusted partner on your journey to optimal health. With our
          comprehensive range of supplements and testing kits, we provide the tools you need to take control of your
          well-being and unlock your true potential.</p>
      </center>
    </div>
  </section>
  <!--  -->
  <section class="section-new-block">
    <div class="master-block-info">
      <div class="block-masterx-r"></div>
      <div class="block-master-l">
        <center>
          <p class="text-subscript-new-block">Elevate Your Wellness Routine with Premium Supplements</p>
          <p class="text-prime-block">Upgrade your wellness routine with our premium line of supplements. Crafted with
            the highest quality ingredients and backed by scientific research, our products support your health goals and
            help you live your best life.</p>
        </center>
      </div>
    </div>
  </section>
  <!--  -->
  <section class="section-brand" style="background: #ffffff;">
    <div class="box-text-brand">
      <center>
        <p class="text-subscript-brand">Made with Passion and Expertise</p>
      </center>
      <center>
        <p class="text-brand">Our products are developed and made with utmost care in Norway, a land renowned for its
          pristine nature and commitment to quality. We also collaborate with trusted partners in the Czech Republic,
          adhering to high-quality standards to deliver exceptional supplements that surpass expectations.</p>
      </center>
    </div>
  </section>
  <!--  -->

@endsection
