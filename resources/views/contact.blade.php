@extends('layouts.header_page')
@section('title', 'Pribonasorte | Contact')
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

    p.text-contact {
      font-size: 18px;
      padding: 0px;
      margin: 0px;
    }
  </style>

  <main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <p class="text-title">Contact</p>
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <section id="background-primary"
    style="background-image: url('/images/fundo_master2.png'); background-size: 100%; background-repeat: no-repeat;">
    <div class="container" style="padding: 50px 15%;">
      <div class="line-content" style="margin-top: 50px;">
        <p class="subtitulo-content">Get in Touch with Pribonasorte: Empower Your Well-being Today!
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">Contact us to embark on your transformative health and wellness journey. Our dedicated
          team is here to answer your questions, provide expert guidance, and assist you in selecting the perfect products
          for your needs. Experience the Pribonasorte difference and take charge of your well-being today.
        </p>
      </div>

      <div class="block-contact">
        <div class="block-contact-prime">

          <div class="line-content">
            <p class="subtitulo-content">Pribonasorte</p>
          </div>
          <p class="text-contact">Intermodels s.r.o.</p>
          <p class="text-contact">Pod Nouzovem 971/17</p>
          <p class="text-contact">19700 Praha</p>
          <p class="text-contact">ICO: 26126893</p>
          <p class="text-contact">DIC: CZ26126893</p>

          <div class="line-content" style="margin-top: 40px;">
            <p class="subtitulo-content">Support:</p>
          </div>
          <p class="text-contact">Phone: +420234688024</p>
          <p class="text-contact">E-mail: support@Pribonasorte.eu</p>

          <p class="text-contact">Our opening hours are Monday – Friday</p>
          <p class="text-contact">9 a.m – 2 p.m CEST</p>
        </div>
        {{-- <div class="block-contact-prime">
          <div class="line-content">
            <p class="subtitulo-content">Return address:</p>
          </div>
          <p class="text-contact">Pribonasorte</p>
          <p class="text-contact">Ležnická 170, 35731 Horní Slavkov</p>
          <p class="text-contact">Email: return@Pribonasorte.eu</p>
        </div> --}}
      </div>

      <div class="block-contact">
        <p class="subtitulo-content" style="margin-top: 30px;">Please kindly check the return address on the specific
          country. Please, contact Customer Service before you send any goods back.</p>
      </div>
    </div>
  </section>

@endsection
