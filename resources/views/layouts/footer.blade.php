@include('layouts.footer_newsite')

{{-- <style>
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
</style>

<footer id="footer-primary">
  <div class="containerr row col-12">
    <div class="col-12" id="colunm1" style="margin-top: 20px; padding: 0px 4%;">
      <div id="colunmxd" class="col-4">
        <div>

          <a href="{{ route('general_terms_conditions') }}">
            <p style="color: white">@lang('header.general_term')</p>
          </a>

          <a href="{{ route('return_policy') }}">
            <p style="color: white">@lang('header.retunr_and_complaint')</p>
          </a>
        </div>
        <div>

          <a href="{{ route('gdpr_policy') }}">
            <p style="color: white">@lang('header.general_regulation')</p>
          </a>

          <a href="{{ route('payment_policy') }}">
            <p style="color: white">@lang('header.pay_policy')</p>
          </a>
        </div>
      </div>
      <div id="colunm2" class="col-4">
        <button id="modal_action_new" class="button_newlatter">Newsletter</button>

        <div class="modal_news">
          <div class="box_modal">
            <div class="line_popup">
              <div class="ba">
                <p class="title_popup">@lang('header.signup')</p>
              </div>
              <div class="bb">
                <p id="closed_popup" style="float: right; margin-top: 2px; font-size: 16px; cursor: pointer;">x</p>
              </div>
            </div>
            <form action="{{ route('registered.newsletter.ecomm') }}" method="POST">
              @csrf
              <div class="line_popup">
                <div class="ba">
                  <p class="form-text-1">@lang('header.name')</p>
                  <input class="input_popup" type="text" placeholder="Name" name="name" required>
                </div>
                <div class="bb">
                  <p class="form-text-1">@lang('header.surname')</p>
                  <input class="input_popup" type="text" placeholder="Surname" name="surname" required>
                </div>
              </div>
              <div class="line_popup">
                <p class="form-text-1">Email *</p>
                <input class="input_popup" type="text" placeholder="name@email.com" name="email" required>
              </div>
              <center><button class="button_popup">@lang('header.register_now')</button></center>
            </form>
          </div>
        </div>
      </div>
      <div id="colunm3" class="col-4">
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
        </ul>
        <ul class="footer-network">

          <li><a href="https://www.facebook.com/lifeprosper.eu"><i class="fa-brands fa-facebook"
                style="font-size: 25px;"></i></a></li>
          <li><a href="https://www.tiktok.com/@lifeprosper.eu"><i class="fa-brands fa-tiktok"
                style="font-size: 25px;"></i></a></li>
          <li><a href="https://www.instagram.com/lifeprosper.eu/" target="blank_"><i class="fa-brands fa-instagram"
                style="font-size: 25px;"></i></a></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!--  -->
<footer class="footer-secundary">
  <div class="containerr row col-12" style="padding: 30px 3% 15px 3%;">
    <div class="row">
      <div class="col-5">
        <p style="color: #ffffff; font-size: 13px;">@lang('header.all_rights') &copy; 2023. <br>@lang('header.all_trademarks')</p>
      </div>
      <div class="col-3">
        <p style="color: #ffffff; font-size: 13px; margin-bottom: 0px;">Phone: +420234688024</p>
        <p style="color: #ffffff; font-size: 13px;">E-mail: support@lifeprosper.eu</p>
      </div>
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
</footer> --}}
