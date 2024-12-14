@extends('layouts.header_newsite')
@section('title', 'Pribonasorte | News')
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

    .block-contact {
      width: 100%;
      display: inline-block;
    }

    .block-contact-prime {
      width: 50%;
      float: left;
      display: inline-block;
    }

    .text-no-post {
      font-size: 20px;
    }

    p.text-contact {
      font-size: 18px;
      padding: 0px;
      margin: 0px;
    }
  </style>

  <main id="main" class="main p-0">
    <section id="herosection" class="background_new_pages">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <!-- <p class="text-title text_new_pages">blog</p> -->
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <main id="background-primary">
    <section class="">
      <div class="container" style="padding: 50px 10%;">

        <div class="line-content" style="padding: 50px 10%;">
          <center>
            <p class="text-no-post" style="padding: 50px 10%;">no recents news</p>
          </center>
        </div>

      </div>
    </section>
  </main>

@endsection
