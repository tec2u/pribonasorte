@extends('layouts.header')
@section('content')
  <style>
    .modal_action {
      background-color: rgba(0, 0, 0, 0.2);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 10000;
    }

    .action_box {
      width: 700px;
      height: 500px;
      border-radius: 10px;
      background: #ffffff;
      margin: 5% auto;
      padding: 30px;
      /* margin-top: 50%; */
    }

    p.text-smart {
      font-weight: bold;
      font-size: 20px;
      margin-top: 20px;
      line-height: 20px;
    }

    ul.list_button {
      margin-top: 2px;
      margin-left: -10px;
    }

    ul.list_button li {
      list-style: none;
      display: inline-block;
      margin: 0px 10px;
    }

    .header_box {
      width: 100%;
      display: inline-block;
    }

    .block1 {
      width: 50%;
      float: left;
      display: inline-block;
    }

    .body_box {
      width: 100%;
      display: inline-block;
    }

    .col-1 {
      width: 30%;
      float: left;
      display: inline-block;
    }

    .col-2 {
      width: 70%;
      float: left;
      display: inline-block;
    }

    <style>#card-package {
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
      height: 300px;
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
  </style>
  </style>

  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>Choose the package</h1>
              <div class=" shadow my-3">
                @forelse($packages as $package)
                  <div class="col-sm-4 card-deck hover ">
                    <a href="{{ route('packages.newProcessPackage2', $package->id) }}">
                      <div id="card-package" class="card ">

                        <div class='base-img'>
                          <div class='moldure'>
                            <img class='imagem_zoon' src='/img/packages/{{ $package->img }}'>
                          </div>
                        </div>

                        <h5 class="tittle-name">{{ $package->name }}</h5>
                        <h6 class="text-price">€ {{ $package->price }}</h6>

                        <div class="container-description">
                          @if (!empty($package->long_description))
                            <h6 class="text-description">{!! $package->long_description !!}</h6>
                          @else
                            <h6 class="text-description">No description at the moment!</h6>
                          @endif
                        </div>
                      </div>
                    </a>
                  </div>
                @empty
                  <p>@lang('package.any_products_registered')</p>
                @endforelse
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
