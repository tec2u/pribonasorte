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
      <div class="wrap-reset100">
        <span class="login100-form-title p-b-48 mt-3">
          <img class="imagetest" src="{{ asset('images/nolimitslogo.png') }}" alt="">
        </span>
        <h4 class="title-login" style="color: black">Resetar senha</h4>
        @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
        @endif

        <form class="login100-form validate-form" method="POST" action="{{ route('password.email') }}">
          @csrf

          <div class="wrap-input100 validate-input mt-5">
            <input id="email" type="text" class="input100 @error('email') is-invalid @enderror" name="email"
              value="{{ old('email') }}" required autocomplete="email" autofocus>
            <span class="focus-input100" data-placeholder="Email"></span>
            @error('email')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="container-login100-form-btn">
            <div class="wrap-login100-form-btn">
              <div class="login100-form-bgbtn"></div>
              <button type="submit" class="login100-form-btn">
                Resetar
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
@endsection
