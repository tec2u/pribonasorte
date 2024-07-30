<style>
  footer .container {
    padding: 35px 0 0 0;

  }

  @media screen and (min-width: 1800px) {
    footer .container {
      padding: 0 !important;
      margin: 0 auto !important;
      width: 90% !important;
    }
  }

  @media screen and (min-width: 1440px) {
    #footer-primary .content-footer {
      flex-wrap: nowrap;
    }

  }

  @media screen and (max-width: 500px) {
    #footer-primary .content-footer {
      display: flex;
      flex-wrap: nowrap;
      flex-direction: column;
    }

    #footer-primary .content-footer>div {
      width: 100% !important;
    }

  }

  #colunm1 {
    margin: 0 auto !important;
    width: 100% !important;
    display: flex;
    flex-wrap: wrap;
  }

  #colunm1>div {
    display: flex;
    padding-top: 2rem;
    gap: 2rem;
    justify-content: center;
  }

  #footer-primary {
    background-image: url(../img/fundo-newhome.jpg);
    padding-top: 1rem;
  }

  #footer-primary .content-footer {
    flex-wrap: wrap;
  }

  #footer-primary .footer-columns {
    margin-top: 0 !important;
    width: fit-content !important;
  }
</style>

<footer id="footer-primary" class="background-complete-bloco1">
  <div class="containerr row col-12 p-l-2 my-2 d-flex justify-content-start"
    style="padding: 0px 6%;margin-bottom:2rem !important">
    <img src="{{ asset('/img/logo-2-gradient.png') }}" alt="" style="width: 150px;">
  </div>
  <div class="containerr row col-12">
    <div class="col-12" style="padding: 0px 6%;">
      <div class="col-12 content-footer d-flex justify-content-between w-100">
        <div class=" p-2 pb-3" style="height: fit-content">

          <div class="navigation-footer-bar">
            <div style="width: 100%">
              <h5 class="fw-bold">Explorar</h5>
            </div>
            <ul>

              <li><a href="/">
                  <p>Inicio</p>
                </a></li>

              {{-- <li><a href="{{ route('about') }}">
                  <p>Sobre</p>
                </a></li> --}}

              {{-- <li><a href="{{ route('news') }}">
                  <p>@lang('header.news')</p>
                </a></li> --}}

              {{-- <li><a href="{{ route('blog') }}">
                  <p>@lang('header.blog')</p>
                </a></li> --}}

              <li><a href="{{ route('contact') }}">
                  <p>Contato</p>
                </a></li>
            </ul>
          </div>

          <ul class="footer-network d-flex ps-0">
            <li><a href="https://www.instagram.com/pribonasortematrizes/" target="blank_"><i
                  class="fa-brands fa-instagram" style="font-size: 25px;"></i></a></li>
            <li><a href="https://api.whatsapp.com/send/?phone=5511960719101&text&type=phone_number&app_absent=0"
                target="blank_"><i class="fa-brands fa-whatsapp" style="font-size: 25px;"></i></a></li>
          </ul>
        </div>

        <div class="footer-columns p-2">
          <div>
            <h5 class="fw-bold">Legal</h5>
          </div>
          <div class="navigation-footer-bar d-flex justify-content-between">
            <ul>
              <li><a href="{{ route('general_terms_conditions') }}">
                  <p>Termos</p>
                </a></li>

              <li><a href="{{ route('return_policy') }}">
                  <p>Política de cancelamento</p>
                </a></li>

              <li><a href="{{ route('gdpr_policy') }}">
                  <p>Política de privacidade</p>
                </a></li>
            </ul>
          </div>
        </div>

        <div class="footer-columns p-2">
          <div>
            <h5 class="fw-bold">Noticias</h5>
          </div>
          <div class="txt-reference">
            Se inscreva para receber novidades
          </div>
          <div class="mt-5 position-relative">
            <input type="email" placeholder="Email" class="form-control email-footer">
            <div class="position-absolute btn-send-footer">
              <i class="fa fa-paper-plane btn-send-footer-icon" style="color: #ffff;"></i>
            </div>
          </div>
          {{-- <div class="row mt-5">
            <ul class="list_payment_ul ps-0">             
              <li><img style="width: 50px;" src="/img/applepay.png"></li>
              <li><img style="width: 50px;" src="/img/googlepay.png"></li>
              <li><img style="width: 50px;" src="/img/mastercardpay.png"></li>
              <li><img style="width: 50px;" src="/img/visapay.png"></li>
            </ul>
          </div> --}}
        </div>
        <!-- <div>
                        <div class="row">
                            <div class="col-4">
                                <ul style="margin-top: 5px;" class="list_payment_ul">
                                    <li><img style="width: 90px;" src="/img/comgatepay.png"></li>
                                    <li><img style="width: 50px;" src="/img/applepay.png"></li>
                                    <li><img style="width: 50px;" src="/img/googlepay.png"></li>
                                    <li><img style="width: 50px;" src="/img/mastercardpay.png"></li>
                                    <li><img style="width: 50px;" src="/img/visapay.png"></li>
                                </ul>
                            </div>
                        </div>
                    </div> -->

      </div>
    </div>
  </div>
</footer>
<!--  -->
<!-- <footer class="footer-secundary">
    <div class="containerr row col-12" style="padding: 30px 3% 15px 3%;">
        <div class="row">
            <div class="col-4">
                <ul style="margin-top: 5px;" class="list_payment_ul">
                    <li><img style="width: 90px;" src="/img/comgatepay.png"></li>
                    <li><img style="width: 50px;" src="/img/applepay.png"></li>
                    <li><img style="width: 50px;" src="/img/googlepay.png"></li>
                    <li><img style="width: 50px;" src="/img/mastercardpay.png"></li>
                    <li><img style="width: 50px;" src="/img/visapay.png"></li>
                </ul>
            </div>
        </div>
    </div>
</footer> -->
<!--
<ul class="footer-menu" style="display: flex; gap:1rem; flex-wrap:wrap;">
          <div>

            <li><a href="{{ route('about') }}">
                <p>@lang('header.about_us')</p>
              </a></li>
            <li><a href="{{ route('news') }}">
                <p>@lang('header.news')</p>
              </a></li>
          </div>
          <div>

            <li><a href="{{ route('blog') }}">
                <p>@lang('header.blog')</p>
              </a></li>
            <li><a href="{{ route('contact') }}">
                <p>@lang('header.contact')</p>
              </a></li>
          </div>
        </ul> -->
