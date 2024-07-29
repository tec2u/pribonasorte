@extends('layouts.header_newsite')
@section('title', 'Lifeprosper | Blog')
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

    .subtitulo-content {
      font-weight: bold;
      font-size: 18px;
    }

    .text-content {
      margin-top: 30px;
      font-size: 16px;
    }

    .text-no-post {
      font-size: 20px;
    }

    .image-post {
      width: 100%;
    }
  </style>

  <main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <p class="text-title">Blog</p>
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <main id="background-primary">
    <section class="master_4">
      <div class="container" style="padding: 50px 10%;">

        @if ($blog)
          <div class="line-content">
            <p class="subtitulo-content">{{ $blog->title }}</p>
          </div>

          <div class="image-post">
            <img src="{{ asset('/img/blog/' . $blog->imagem) }}">
          </div>

          <div class="line-content">
            <p class="text-content">{!! $blog->post !!}</p>
          </div>
        @else
          <div class="line-content" style="padding: 50px 10%;">
            <center>
              <p class="text-no-post">no recents posts</p>
            </center>
          </div>
        @endif

      </div>
    </section>
  </main>

@endsection
