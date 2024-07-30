@extends('layouts.header_newsite')
@section('title', 'Lifeprosper | Contact')
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
    <section style="" class="background_new_pages" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <!-- <p class="text-title text_new_pages">Contact</p> -->
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <section id="background-primary">
    <div class="container" style="padding: 50px 15%;">
      <div class="line-content" style="margin-top: 50px;">
        <p class="subtitulo-content">MANDE SUA DÚVIDA OU SUGESTÃO QUE TE ATENDEREMOS O MAIS BREVE POSSÍVEL:
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">
        </p>
      </div>

      <div class="block-contact">
        <div class="d-flex justify-content-between">
          <div class="block-contact-prime">

            <div class="line-content" style="margin-top: 40px;">
              <p class="subtitulo-content">Suporte:</p>
            </div>
            <p class="text-contact">Celular: +55 11 96071-9101</p>
            <p class="text-contact">E-mail: contato@pribonasorte.com.br</p>

          </div>
          <div class="content-img-contact">
            <img src="/images/favicon.png" class="img-contact-only">
          </div>
        </div>
        <div class="block-contact">
          <p class="subtitulo-content" style="margin-top: 30px;">PARA UM TEMPO DE RESPOSTA MAIS RÁPIDA NOS CHAME PELO
            WHATSAPP.</p>
        </div>
        <div class="line-content text-center" style="margin-top: 40px;">
          <h1 class="subtitulo-content" style="font-size: 30px;">Redes sociais</h1>
          <ul class="footer-network d-flex justify-content-center">
            <li><a href="https://www.instagram.com/pribonasortematrizes/" target="blank_"><i
                  class="fa-brands fa-instagram" style="font-size: 30px; color: #fd2c4e !important;"></i></a></li>
            <li><a href="https://api.whatsapp.com/send/?phone=5511960719101&text&type=phone_number&app_absent=0"
                target="blank_"><i class="fa-brands fa-whatsapp"
                  style="font-size: 30px; color: #0dc242 !important"></i></a></li>
          </ul>
        </div>
      </div>
  </section>

@endsection
