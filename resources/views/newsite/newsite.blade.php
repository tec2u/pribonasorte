@extends('layouts.header_newsite')
@section('title', 'Lifeprosper')
@section('content')
  <!--  -->
  <div id="video-modal" class="modal" style="z-index: 2;">
    <div class="modal-container">
      <span class="close" onclick="closeModal()">&times;</span>
      <video id="videoModal" controls>
        <source src="/videos/video-section-1.mp4" type="video/mp4">
      </video>
    </div>
  </div>
  <section id="background-top-2">
    <!-- class="carousel" -->
    <!--  -->
    <div class="slide-item slide item-1">
      <!-- <video autoplay muted loop class="bg_video bg-video-newsite" id="autoPlayForce">
                                                                                              <source src="/videos/video-1.mp4" type="video/mp4">
                                                                                            </video> -->
      <center class="reposition-join-btn content-join-now">
        <button class="btn-join-now" onclick="hideOrHiddenOptions()">JOIN NOW</button>
        <div class="options-link">
          <a href="{{ route('page.login.ecomm') }}">Customer</a>
          <a href="{{ route('register') }}">Distributor</a>
        </div>
      </center>
    </div>
    <!-- <div class="slide-item slide">
                                                                                            <video autoplay muted loop class="bg_video bg-video-newsite" id="autoPlayForce">
                                                                                              <source src="/videos/video-2.mp4" type="video/mp4">
                                                                                            </video>
                                                                                            <center class="reposition-join-btn">
                                                                                              <a href="{{ route('ecomm') }}">
                                                                                                <button class="btn-join-now">JOIN NOW</button>
                                                                                              </a>
                                                                                            </center>
                                                                                          </div>
                                                                                          <div class="slide-item slide">
                                                                                            <video autoplay muted loop class="bg_video bg-video-newsite" id="autoPlayForce">
                                                                                              <source src="/videos/video-3.mp4" type="video/mp4">
                                                                                            </video>
                                                                                            <center class="reposition-join-btn">
                                                                                              <a href="{{ route('ecomm') }}">
                                                                                                <button class="btn-join-now">JOIN NOW</button>
                                                                                              </a>
                                                                                            </center>
                                                                                          </div>
                                                                                          <div class="slide-item slide">
                                                                                            <video autoplay muted loop class="bg_video bg-video-newsite" id="autoPlayForce">
                                                                                              <source src="/videos/video-4.mp4" type="video/mp4">
                                                                                            </video>
                                                                                            <center class="reposition-join-btn">
                                                                                              <a href="{{ route('ecomm') }}">
                                                                                                <button class="btn-join-now">JOIN NOW</button>
                                                                                              </a>
                                                                                            </center>
                                                                                          </div> -->
    <!-- <div class="text-center position-absolute w-100 message_banner_bottom0">Teach, Travel, Transform</div> -->
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
                <div class="card-img"
                  style="background-image: url('/img/Foci-brand-HP-320.jpg'); background-size: 100%;">
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
  <section class="sections-default d-flex align-items-end container-video1" style="z-index: 0;">
    <div class="background-complete-bloco background-complete-bloco1 container-div-video">
      <div class="section-1 d-flex justify-content-between div-video1">
        <div class="w-100 pe-5">
          <div class="d-flex justify-content-center">
            <div class="d-flex align-items-center">
              <div class="pre">
                <h3 class="title-section-1" style="font-size: 2.1vw;">
                  UPGRADE YOUR HEALTH TO THE NEXT
                  LEVEL
                </h3>
                <p class="subtitle-section-size-emphasis">
                  "Assess your health in
                  5 minutes with
                  the scanner. Get recommended
                  products your
                  body needs quickly and easily.
                  Test your health now!"
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100 d-flex flex-column justify-content-center" style="width: 90%;">
          <video autoplay muted loop class="bg_video_player w-100" id="autoPlayForce">
            <source src="/videos/video-section-1.mp4" type="video/mp4">
          </video>
          <div class="w-100 content-modal-btn d-flex justify-content-center"><button class="play-button"
              onclick="openModal()">Play Video</button></div>
        </div>
      </div>
    </div>
  </section>
  <section>
    <div class="background-complete-bloco1-2 carousel-steps">
      <div class="" style="border: none;">
        <div class="w-100 pe-5">
          <h3 class="font-title-sections text-center">
            4 PROBLEM-SOLVING-PROCESS
          </h3>
        </div>
        {{-- <div class="mt-80 line-horizontal">
                <div class="w-100 d-flex justify-content-between list-circles">
                    <i class="fas fa-circle font-35"></i>
                    <i class="fas fa-circle font-35"></i>
                    <i class="fas fa-circle font-35"></i>
                    <i class="fas fa-circle font-35"></i>
                </div>
            </div>
            <div class="carousel-prev-next">
                <div class="carousel mt-80">
                    <div class="slide d-flex justify-content-center">
                        <div style="width: 80%;">
                            <div>
                                <h3 class="title-section-1 font-50">
                                    <div>STEP 1 )</div>
                                </h3>
                                <div class="subtitle-section-size color-purple2 fw-bold">SCAN</div>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div>
                                    <img src="{{asset('/images/digital.png')}}" alt="" width="200">
        </div>
        <div class="ms-3">
            <p class="emphasis-min">Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift ant thorough real-time analysis of all organs and systems.</p>
            <p class="emphasis-min">Goal: Provide clientes with a comprehensive view of their health status and needs</p>
        </div>
    </div>
    </div>
    </div>
    <div class="slide d-flex justify-content-center">
        <div style="width: 80%;">
            <div>
                <h3 class="title-section-1 font-50">
                    <div>STEP 2 )</div>
                </h3>
                <div class="subtitle-section-size color-purple2 fw-bold">RECOMMEND</div>
            </div>
            <div class="d-flex">
                <div>
                    <img src="{{asset('/images/icon-recommend.png')}}" alt="" width="200">
                </div>
                <div class="ms-3">
                    <p class="emphasis-min">Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift ant thorough real-time analysis of all organs and systems.</p>
                    <p class="emphasis-min">Goal: Provide clientes with a comprehensive view of their health status and needs</p>
                </div>
            </div>
        </div>
    </div>
    <div class="slide d-flex justify-content-center">
        <div style="width: 80%;">
            <div>
                <h3 class="title-section-1 font-50">
                    <div>STEP 3 )</div>
                </h3>
                <div class="subtitle-section-size color-purple2 fw-bold">EXPAND</div>
            </div>
            <div class="d-flex">
                <div>
                    <img src="{{asset('/images/icon-exapand.png')}}" alt="" width="200">
                </div>
                <div class="ms-3">
                    <p class="emphasis-min">Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift ant thorough real-time analysis of all organs and systems.</p>
                    <p class="emphasis-min">Goal: Provide clientes with a comprehensive view of their health status and needs</p>
                </div>
            </div>
        </div>
    </div>
    <div class="slide d-flex justify-content-center">
        <div style="width: 80%;">
            <div>
                <h3 class="title-section-1 font-50">
                    <div>STEP 4 )</div>
                </h3>
                <div class="subtitle-section-size color-purple2 fw-bold">SUPPORT</div>
            </div>
            <div class="d-flex">
                <div>
                    <img src="{{asset('/images/icon-support.png')}}" alt="" width="200">
                </div>
                <div class="ms-3">
                    <p class="emphasis-min">Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift ant thorough real-time analysis of all organs and systems.</p>
                    <p class="emphasis-min">Goal: Provide clientes with a comprehensive view of their health status and needs</p>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="d-flex flex-column align-items-center container-prev">
        <i class="fas prev btn-prev btn-change-carousel fa-chevron-left font-50 emphasis-text"></i>
    </div>
    <div class="d-flex flex-column align-items-center container-next">
        <i class="fas next btn-next btn-change-carousel fa-chevron-right font-50 emphasis-text"></i>
    </div>
    </div> --}}

        <div class="container-cards">
          <section class="section is--scroll-cards">
            <div class="w-layout-blockcontainer w-container container-cards-all">
              <div class="scroll-cards">
                <div data-w-id="a964f1d2-1c0f-28c3-6764-a4210c69d5d2" class="scroll-cards__inner">
                  <div class="scroll-cards__card _1 card-height card-scan">
                    <!-- <div class="scroll_cards__card__left">
                                                                                                                      <div class="scroll-card__heading">
                                                                                                                          <div class="scroll-card__tag-wrap">
                                                                                                                              <h4 class="h3 m-32 linguana-ignore">1. SCAN</h4>
                                                                                                                          </div>
                                                                                                                          <div class="text--xl text--white-op-70 max-65">
                                                                                                                              Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                                              ant thorough real-time analysis of all organs and systems.
                                                                                                                              Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                                          </div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__mid">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">Verified</span> Web3 projects</div>
                                                                                                                          <img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">Top 10</span> blockchain in the
                                                                                                                              world</div><img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">160,000</span> unique wallets</div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__bot"><a href="https://ultron.foundation/" target="_blank" class="button is--transparent w-inline-block">
                                                                                                                              <div class="button-icon-wrap">
                                                                                                                                  <img src="images/658fe775560e9077a38756d4_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon idle">
                                                                                                                                  <img src="images/65a585d81043b74dc60935d5_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon hover">
                                                                                                                              </div>
                                                                                                                          </a>
                                                                                                                      </div>
                                                                                                                  </div>
                                                                                                                  <div class="image-scroll-carosel">
                                                                                                                      <img src="{{ asset('/images/digital.png') }}" loading="lazy" sizes="(max-width: 991px) 100vw, (max-width: 1919px) 46vw, 758.578125px" alt="" class="scroll-card-img _1 desktop">
                                                                                                                  </div> -->
                  </div>
                  <div class="scroll-cards__card _2 card-height card-recommend">
                    <!-- <div class="scroll_cards__card__left">
                                                                                                                      <div class="scroll-card__heading">
                                                                                                                          <div class="scroll-card__tag-wrap">
                                                                                                                              <h4 class="h3 m-32 linguana-ignore">2. RECOMMEND</h4>
                                                                                                                          </div>
                                                                                                                          <div class="text--xl text--white-op-70 max-65">
                                                                                                                              Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                                              ant thorough real-time analysis of all organs and systems.
                                                                                                                              Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                                          </div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__mid">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">$ 50M</span> sold</div><img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">1,000,000</span> jackpot guaranteed
                                                                                                                          </div><img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">$ 430B +</span> industry</div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__bot">
                                                                                                                          <a href="https://www.lottoday.io/" target="_blank" class="button is--transparent w-inline-block">
                                                                                                                              <div class="button-icon-wrap">
                                                                                                                                  <img src="images/658fe775560e9077a38756d4_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon idle">
                                                                                                                                  <img src="images/65a585d81043b74dc60935d5_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon hover">
                                                                                                                              </div>
                                                                                                                          </a>
                                                                                                                          <div class="scroll-card__bot-logos"><a href="#" class="scroll-card__bot-logo-link w-inline-block">
                                                                                                                                  <img src="images/65940d710893516ae60c9c88_Group%2048097791.svg" loading="lazy" alt="" class="scroll-card__bot-logo"></a><a href="#" class="scroll-card__bot-logo-link w-inline-block"><img src="images/65940d710893516ae60c9c7b_Chainlink%20Logo%20White%201.svg" loading="lazy" alt="" class="scroll-card__bot-logo"></a></div>
                                                                                                                      </div><img src="images/65b172fce5ebf959ceb4cc9e_scroll-bg-1-65b172df0c62c.webp" loading="lazy" alt="" class="scroll-card__bg-img full">
                                                                                                                  </div>
                                                                                                                  <div class="image-scroll-carosel">
                                                                                                                      <img src="{{ asset('/images/icon-recommend.png') }}" loading="lazy" sizes="(max-width: 991px) 100vw, (max-width: 1919px) 46vw, 758.578125px" alt="" class="scroll-card-img _1 desktop">
                                                                                                                  </div> -->
                  </div>
                  <div class="scroll-cards__card _3 card-height card-expand">
                    <!-- <div class="scroll_cards__card__left _3">
                                                                                                                      <div class="scroll-card__heading">
                                                                                                                          <div class="scroll-card__tag-wrap">
                                                                                                                              <h4 class="h3 m-32 linguana-ignore">3. EXPAND</h4>
                                                                                                                          </div>
                                                                                                                          <div class="text--xl text--white-op-70 max-65">
                                                                                                                              Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                                              ant thorough real-time analysis of all organs and systems.
                                                                                                                              Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                                          </div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__mid">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">$ 25M+</span> already gone</div><img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">TradFi</span> &amp; <span class="text--white">DeFi</span> supported</div><img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">5B +</span> future users potential
                                                                                                                          </div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__bot"><a href="https://flip-me.com/" target="_blank" class="button is--transparent w-inline-block">
                                                                                                                              <div class="button-icon-wrap"><img src="images/658fe775560e9077a38756d4_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon idle">
                                                                                                                                  <img src="images/65a585d81043b74dc60935d5_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon hover">
                                                                                                                              </div>
                                                                                                                          </a>
                                                                                                                          <div class="scroll-card__bot-logos gap-s">
                                                                                                                              <div class="scroll-card__bot-logo-link"><img src="images/65940ec78f3f7a64437746ca_Download_on_the_App_Store_Badge_US-UK_RGB_blk_092917%201.svg" loading="lazy" alt="" class="scroll-card__bot-logo">
                                                                                                                              </div>
                                                                                                                              <div class="scroll-card__bot-logo-link"><img src="images/65940ed704d90dc84a988de9_Isolation_Mode.svg" loading="lazy" alt="" class="scroll-card__bot-logo _2"></div>
                                                                                                                          </div>
                                                                                                                      </div><img src="images/65b172fce5ebf959ceb4cc9e_scroll-bg-1-65b172df0c62c.webp" loading="lazy" alt="" class="scroll-card__bg-img full">
                                                                                                                  </div>
                                                                                                                  <div class="image-scroll-carosel">
                                                                                                                      <img src="{{ asset('/images/icon-exapand.png') }}" loading="lazy" sizes="(max-width: 991px) 100vw, (max-width: 1919px) 46vw, 758.578125px" alt="" class="scroll-card-img _1 desktop">
                                                                                                                  </div> -->
                  </div>
                  <div class="scroll-cards__card _4 card-height card-support">
                    <!-- <div class="scroll_cards__card__left _3">
                                                                                                                      <div class="scroll-card__heading">
                                                                                                                          <div class="scroll-card__tag-wrap">
                                                                                                                              <h4 class="h3 m-32 linguana-ignore">4. SUPPORT</h4>
                                                                                                                          </div>
                                                                                                                          <div class="text--xl text--white-op-70 max-65">
                                                                                                                              Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                                              ant thorough real-time analysis of all organs and systems.
                                                                                                                              Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                                          </div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__mid">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">2.6M</span> Gigabytes of data
                                                                                                                              analysed</div><img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">Binance</span> Link partner</div>
                                                                                                                          <img src="images/6592ecdf764d512c26a0a120_seperator.svg" loading="lazy" alt="" class="curve-seperator">
                                                                                                                          <div class="text--28 text--white-op-70"><span class="text--white">All-in-one App</span> (education,
                                                                                                                              trading, insights)</div>
                                                                                                                      </div>
                                                                                                                      <div class="scroll-card__bot"><a href="https://finup.ai/" target="_blank" class="button is--transparent w-inline-block">
                                                                                                                              <div class="button-icon-wrap"><img src="images/658fe775560e9077a38756d4_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon idle"><img src="images/65a585d81043b74dc60935d5_Arrow%20Right.svg" loading="lazy" alt="" class="button__icon hover"></div>
                                                                                                                          </a></div><img src="images/65b172fce5ebf959ceb4cc9e_scroll-bg-1-65b172df0c62c.webp" loading="lazy" alt="" class="scroll-card__bg-img full">
                                                                                                                  </div>
                                                                                                                  <div class="image-scroll-carosel">
                                                                                                                      <img src="{{ asset('/images/icon-support.png') }}" loading="lazy" sizes="(max-width: 991px) 100vw, (max-width: 1919px) 46vw, 758.578125px" alt="" class="scroll-card-img _1 desktop">
                                                                                                                  </div> -->
                  </div>
                </div>
              </div>
            </div>
          </section>
          <!-- <div class="card-section col-2 card-1">
                                                                                                  <img src="{{ asset('/images/digital.png') }}" alt="">
                                                                                                  <h5 style="color:#bd7afc;">1. SCAN</h5>
                                                                                                  <div>
                                                                                                    <p>Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                      ant thorough real-time analysis of all organs and systems.
                                                                                                      Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                    </p>
                                                                                                  </div>
                                                                                                </div>

                                                                                                <div class="card-section col-2 card-2">
                                                                                                  <img src="{{ asset('/images/icon-recommend.png') }}" alt="">
                                                                                                  <h5 style="color:#a751f7;">2. RECOMMEND</h5>
                                                                                                  <div>
                                                                                                    <p>Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                      ant thorough real-time analysis of all organs and systems.
                                                                                                      Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                    </p>

                                                                                                  </div>
                                                                                                </div>

                                                                                                <div class="card-section col-2 card-3">
                                                                                                  <img src="{{ asset('/images/icon-exapand.png') }}" alt="">
                                                                                                  <h5 style="color:#781acf;">3. EXPAND</h5>
                                                                                                  <div>
                                                                                                    <p>Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                      ant thorough real-time analysis of all organs and systems.
                                                                                                      Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                    </p>
                                                                                                  </div>
                                                                                                </div>

                                                                                                <div class="card-section col-2 card-4">
                                                                                                  <img src="{{ asset('/images/icon-support.png') }}" alt="">
                                                                                                  <h5 style="color:#7906e6;">4. SUPPORT</h5>
                                                                                                  <div>
                                                                                                    <p>Our advanced technology scans 200 biomarkers in just 5 minutes. offering a swift
                                                                                                      ant thorough real-time analysis of all organs and systems.
                                                                                                      Goal: Provide clientes with a comprehensive view of their health status and needs
                                                                                                    </p>
                                                                                                  </div>
                                                                                                </div> -->
        </div>
      </div>
    </div>
  </section>

  <section class="sections-default why-life">
    <div class="background-complete-bloco background-complete-bloco1">
      <div class="section-1 justify-content-center w-100 ">
        <div class="d-flex w-100 justify-content-center pe-5" style="flex-wrap: wrap;">
          <div class="mt-5 me-4 why-life-div-txt" style="width: 50%">
            <h3 class="font-title-sections text-center"
              style="margin-bottom: 20px !important; margin-top: 40px !important;">
              <span style="font-weight: 400; color: #1a1a1a;">WHY </span><span>LIFEPROSPER?</span>
            </h3>
            <p class="emphasis-text-2 text-center">LifeProsper and its employees change lives by using individually
              measured metabolic values to recommend nutritional supplement solutions with our proprietary epigenetic
              nutraceuticals/dietary supplements.</p>
            <p class="emphasis-text-2 text-center">In a crowded nutritional supplement market, we stand out because our
              development team recommends solutions that address the causes of your current health situation, not just its
              symptoms.</p>
            <p class="emphasis-text-2 text-center">LifeProsper's state-of-the-art scanner technology evaluates up to 200
              biomarkers within 5 minutes across four areas: digestion, detox, hormonal balance, and immunity. This gives
              you insight into the causes of your current health situation and how these can be improved with LifeProsper
              products, as demonstrated through the scan report system.</p>
            <p class="emphasis-text-2 text-center">Based on the immediate evaluation of the scan report in all four
              areas, LifeProsper offers you solutions with our products. The uncompromising quality of ingredients in
              LifeProsper products ensures you get the most effective ingredients you need.</p>
            <p class="emphasis-text-2 text-center">LifeProsper offers solutions for your health, improves the quality of
              life, and increases the standard of living. Now is your chance to join us on this journey of life.</p>
          </div>
          {{-- <div class="mt-5 ms-4 d-flex align-items-center why-life-div-img"> --}}
          <div class="content-img-why" style="max-width: 100%">
            <img src="{{ asset('images/why-lifeprosper.png') }}" class="card-img-content card-img-content-2"
              alt="">
          </div>
          {{-- </div> --}}
        </div>
      </div>
    </div>
  </section>

  <section class="sections-default div-founders">
    <div class="background-complete-bloco background-complete-bloco1">
      <div class="section-1 justify-content-center w-100">
        <div class="w-100  d-flex justify-content-center">
          <h3 class="font-title-sections text-start">
            <span style="font-weight: 400; color: #1a1a1a;">WORDS FROM OUR </span><span>FOUNDER</span>
          </h3>
        </div>
        <div class="d-flex w-100 justify-content-center founders-content">
          <div class="mt-5 d-flex me-4 justify-content-center align-items-center" style="width: 35%">
            <p class="subtitle-section-size text-center">"Take Charge of Your Health: My Health = My Business. Get
              Personalized Solutions Tailored to Your Needs. Join Our Vision and Earn in the Billion-Dollar Wellness
              Industry"</p>
          </div>
          <div class="mt-5 ms-4 d-flex justify-content-center">
            <div class="content-img d-flex flex-column align-items-center">
              <img src="{{ asset('images/our-team.png') }}" class="card-img-content" alt="">
              <p class="legend-img">JURAJ & IRENA MOJZIS</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="sections-default" style="height: max-content">
    <div
      class="background-complete-bloco background-complete-bloco1 flex-column align-items-center px-0"style="height: max-content">
      <div class="section-1 justify-content-center" style="width: 91%;">
        <div class="w-100 pe-5">
          <h3 class="font-title-sections text-center">
            WHAT PEOPLE SAY
          </h3>
        </div>
        <div class="mt-5">
          <div class="d-flex justify-content-between videos">
            <div>
              <img src="{{ asset('/images/video-empty.png') }}" class="midia-people-say" alt="">
            </div>
            <div>
              <img src="{{ asset('/images/video-empty.png') }}" class="midia-people-say" alt="">
            </div>
            <div>
              <img src="{{ asset('/images/video-empty.png') }}" class="midia-people-say" alt="">
            </div>
          </div>
        </div>
      </div>
      <div class="mt-5 section-1 container-carosel-images">
        <div class="d-flex justify-content-center content-carosel-images">
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-1.png') }}" class="img-item-carosel" alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel" alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel" alt="">
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    $(document).ready(function() {
      $('.carousel').slick({
        autoplay: true,
        autoplaySpeed: 5000, // Tempo de transição entre os slides em milissegundos (5 segundos neste exemplo)
        dots: true, // Exibir navegação por bolinhas
        arrows: false, // Ocultar setas de navegação
        infinite: true, // Permitir rolagem infinita
        speed: 1000, // Velocidade da transição dos slides
        slidesToShow: 1, // Quantidade de slides a serem exibidos simultaneamente
        slidesToScroll: 1 // Quantidade de slides a serem rolados por vez
      });

      $('.carousel-prev-next .prev').click(function() {
        $('.carousel').slick('slickPrev');
      });

      $('.carousel-prev-next .next').click(function() {
        $('.carousel').slick('slickNext');
      });
    });


    function animateValue(obj, start, end, duration) {
      if (start === end) return;
      var range = end - start;
      var current = start;
      var increment = end > start ? 1 : -1;
      var stepTime = Math.abs(Math.floor(duration / range));
      var timer = setInterval(function() {
        current += increment;
        obj.innerHTML = current;
        if (current == end) {
          clearInterval(timer);
        }
      }, stepTime);
    }

    window.onload = function() {
      const counters = document.querySelectorAll('.contador')
      counters.forEach((counter, index) => {
        animateValue(counter, 0, 97, 3000);
      })
      // Adicione mais chamadas de função para os outros elementos, se necessário
    };
    // Função para abrir o modal
    function openModal() {
      var modal = document.getElementById("video-modal");
      modal.style.display = "flex";
      document.getElementById("videoModal").play();
    }

    // Função para fechar o modal
    function closeModal() {
      var modal = document.getElementById("video-modal");
      modal.style.display = "none";
      document.getElementById("videoModal").pause();
    }

    function hideOrHiddenOptions() {
      if ($('.options-link').css('display') == 'none') {
        $('.options-link').css('display', 'flex')
      } else {
        $('.options-link').css('display', 'none')
      }

    }
  </script>
@endsection
