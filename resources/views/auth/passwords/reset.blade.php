@extends('layouts.app')

@section('content')
  {{-- <video autoplay muted loop class="bg_video">
    <source src="/videos/fitnessAuraWay.mp4" type="video/mp4">
  </video> --}}

  <style>
    body {
      background-image: url(../img/fundo-newhome.jpg) !important;
    }
  </style>


  <div class="limiter">
    <div class="container-login100">
      <div class="wrap-login100">


        <span class="login100-form-title p-b-48">
          <img class="imagetest" src="{{ asset('images/nolimitslogo.png') }}" alt="">
        </span>
        <h4 class="title-login mt-3">{{ __('Reset Password') }}</h4>

        <form class="login100-form validate-form" method="POST" action="{{ route('password.update') }}">
          @csrf

          {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}


          <div class="mb-3">
            <label for="email" class="form-label teste1234">Code</label>
            <input id="email" type="text" class="form-control form-register" name="code" value="" required
              autofocus>
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong></strong>
              </span>
            @enderror
          </div>

          <div class="mb-3 wrap-input100 validate-input" style="border-bottom: none;">
            <label for="password" class="form-label teste1234">{{ __('Password') }}</label>
            <span class="btn-show-password" style="height: 140%;">
              <i class="fa fa-eye"></i>
            </span>
            <input id="password" type="password"
              class="form-control form-register @error('password') is-invalid @enderror" name="password" required
              autocomplete="new-password">
            @error('password')
              <span class="invalid-feedback" role="alert">
                <strong></strong>
              </span>
            @enderror
          </div>

          <div class="wrap-input100 validate-input" style="border-bottom: none;">
            <label for="password-confirm" class="form-label teste1234">{{ __('Confirm Password') }}</label>
            <span class="btn-show-password" style="height: 140%;">
              <i class="fa fa-eye"></i>
            </span>
            <input id="password-confirm" type="password" class="form-control form-register" name="checkpass" required
              autocomplete="new-password">
          </div>


          <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
              <div class="login100-form-bgbtn"></div>
              <button type="submit" class="login100-form-btn">
                {{ __('Reset Password') }}
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    (function($) {
      "use strict";
      /*==================================================================
      [ Show pass ]*/
      var showPass = 0;
      $('.btn-show-password').on('click', function() {
        if (showPass == 0) {
          $(this).next('input').attr('type', 'text');
          $(this).find('i').removeClass('fa-eye');
          $(this).find('i').addClass('fa-eye-slash');
          showPass = 1;
        } else {
          $(this).next('input').attr('type', 'password');
          $(this).find('i').addClass('fa-eye');
          $(this).find('i').removeClass('fa-eye-slash');
          showPass = 0;
        }

      });
    })(jQuery);
  </script>
@endsection
