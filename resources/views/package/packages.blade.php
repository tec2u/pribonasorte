@extends('layouts.header')
@section('content')
  <style>
    #card-package {
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

  <main id="main" class="main">
    {{-- @include('flash::message') --}}
    <section id="produtos" class="content">
      <div class="fade">
        <div class="container-fluid">

          <div class="row-title">
            <p class="title-package">@lang('package.packages')</p>
            <p class="number-packages">
              @php $packages_number = count($packages); @endphp
              {{ $packages_number }} Packages
            </p>
          </div>

          <div class="container">
            <div class="row">
              @forelse($packages as $package)
                <div class="col-sm-4 card-deck hover ">
                  <div id="card-package" class="card ">

                    <div class='base-img'>
                      <div class='moldure'>
                        <img class='imagem_zoon' src='/img/packages/{{ $package->img }}'>
                      </div>
                    </div>

                    <h5 class="tittle-name">{{ $package->name }}</h5>
                    <h6 class="text-price">R$ {{ $package->price }}</h6>

                    <div class="container-description">
                      @if (!empty($package->long_description))
                        <h6 class="text-description">{!! $package->long_description !!}</h6>
                      @else
                        <h6 class="text-description">No description at the moment!</h6>
                      @endif
                    </div>

                    <div class="card-body">
                      @if ($user->contact_id == null)
                        <a href="{{ route('packages.detail', ['id' => $package->id]) }}"
                          class="btn btn-primary m-4 rounded-pill"> @lang('package.details')</a>
                      @else
                        <a href="{{ route('packages.detail', ['id' => $package->id]) }}"
                          class="btn btn-primary m-4 rounded-pill"> @lang('package.details')</a>
                      @endif
                    </div>
                  </div>
                </div>

              @empty
                <p>@lang('package.any_products_registered')</p>
              @endforelse

            </div>
            <div class="card-footer clearfix py-3">
              {{ $packages->links() }}
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
