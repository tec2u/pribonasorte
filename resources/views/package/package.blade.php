@extends('layouts.header')
@section('content')
  <style>
    #card-package {
      width: 350px;
      margin: 0px 32.5%;
      padding: 30px;
    }

    p.title-package {
      margin-left: 10px;
      font-size: 30px;
      font-weight: bold;
      text-transform: uppercase;
    }

    .moldure {
      width: 100%;
      height: 350px;
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

    .container-description {
      width: 100%;
      height: 120px;
    }

    p.number-packages {
      margin-left: 10px;
    }

    #button-space {
      margin-top: 20px;
    }
  </style>
  @php

    use App\Models\User;
    use Illuminate\Support\Facades\Auth;

    $userCorporate = User::where('id', User::find(Auth::id())->id)
        ->whereNotNull('id_corporate')
        ->whereNotNull('corporate_nome')
        ->first();

    $userCorporate = isset($userCorporate);
  @endphp
  <main id="main" class="main">
    @include('flash::message')
    <section id="produto" class="content">
      <div class="fade">
        <div class="container-fluid">

          <div class="row justify-content-evenly" style="margin-bottom: 30px;">
            <ul class="list-group list-group-horizontal" style="margin-left: 20px;">
              <li class="list-group-item"><a href="{{ route('packages.index') }}">Packages</a></li>
              <li class="list-group-item fw-bold">{{ $package->name }}</li>
            </ul>
          </div>

          <div class="row justify-content-evenly">

            <div id="card-package" class="card ">

              <div class='base-img'>
                <div class='moldure'>
                  <img class='imagem_zoon' src='/img/packages/{{ $package->img }}'>
                </div>
              </div>

              <h5 class="tittle-name">{{ $package->name }}</h5>
              <h6 class="text-price">€ {{ $package->price }}</h6>
              <h6 class="text-price">VAT €{{ number_format($vatValue, 2, '.', '') }}</h6>

              <div class="container-description">
                @if (!empty($package->long_description))
                  <h6 class="text-description">{!! $package->long_description !!}</h6>
                @else
                  <h6 class="text-description">No description at the moment!</h6>
                @endif
              </div>

              <div class="card-body">
                {{-- BUTTONS PAY --}}
                <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                  <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
                    Eu concordo com os
                    <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                      termos</a>
                  </label>
                </div>

                <div id="btn-falso" style="opacity: .5">


                  <a id="button-space-btc" class="btn btn-primary rounded-pill" disabled>Pagar com BTC</a>
                  <a id="button-space-eth" class="btn btn-primary rounded-pill" disabled>Pagar com ETH</a>
                  <a id="button-space-card" class="btn btn-primary rounded-pill" disabled>Pagar com Cartão</a>

                </div>

                <div id="btn-vdd" style="display: none;">


                  <a id="button-space-btc"
                    href="{{ route('packages.buy_package', ['id' => $package->id, 'method' => 'BTC']) }}"
                    class="btn btn-primary rounded-pill" disabled>Pagar com BTC</a>

                  <a id="button-space-eth"
                    href="{{ route('packages.buy_package', ['id' => $package->id, 'method' => 'ETH']) }}"
                    class="btn btn-primary rounded-pill" disabled>Pagar com ETH</a>

                  <a id="button-space-card"
                    href="{{ route('packages.buy_package', ['id' => $package->id, 'method' => 'CARD_CZ_CSOB_2']) }}"
                    class="btn btn-primary rounded-pill" disabled>Pagar com Cartão</a>

                </div>

                <script>
                  const checkbox = document.getElementById('flexCheckDefault');
                  const falso = document.getElementById('btn-falso');
                  const vdd = document.getElementById('btn-vdd');


                  checkbox.addEventListener('change', function() {

                    if (checkbox.checked) {

                      falso.style.display = 'none';
                      vdd.style.display = 'block';
                    } else {

                      falso.style.display = 'block';
                      vdd.style.display = 'none';
                    }
                  });
                </script>

                {{-- @if ($package->plan_id == null)
                  <a id="button-space"
                    href="{{ route('payment.payment', ['package' => $package->id, 'value' => $package->price]) }}"
                    class="btn btn-primary rounded-pill">@lang('package.buy_now_USDTTRC20')</a>
                  <a id="button-space"
                    href="{{ route('payment.paymentBTC', ['package' => $package->id, 'value' => $package->price]) }}"
                    class="btn btn-primary rounded-pill">@lang('package.buy_now_BTC')</a>
                @else
                  <a onclick="confirmPlan()" class="btn btn-primary rounded-pill m-4">Subscribe Now</a>
                  <script>
                    function confirmPlan() {
                      if (confirm("You just selected the {!! $package->name !!} plan, is that correct?")) {
                        location.href = "{!! route('payment.subscriptionClub', ['package' => $package->id]) !!}"
                      } else {
                        alert("Please go back and choose the correct plan!");
                        history.go(-1)
                      }
                    }
                  </script>
                @endif --}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
