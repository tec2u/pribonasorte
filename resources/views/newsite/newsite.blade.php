@extends('layouts.header_newsite')
@section('title', 'Pribonasorte')
@section('content')
  <!--  -->
  <div id="video-modal" class="modal" style="z-index: 2;">
    <div class="modal-container">
      <span class="close" onclick="closeModal()">&times;</span>
      <video id="videoModal" controls>
        <source src="/videos/video-section-1.mp4" type="video/mp4">
      </video>
    </div>
  </div>
  <section id="background-top-22">
    <div class="slide-item slide " style="height: 10vh !important">
      <center class="reposition-join-btn content-join-now">
        {{-- <a href="{{ route('ecomm') }}" class="btn btn-join-now">CONHEÇA NOSSOS PRODUTOS</a> --}}
      </center>
    </div>
  </section>

  <section id="background-primary">
    <div class="container" style="padding: 0px;">
    </div>
  </section>


  <section class="sections-default d-flex align-items-end container-video1" style="z-index: 0; padding-top: 2rem">
    <div class="background-complete-bloco background-complete-bloco1 container-div-video">
      <div class="section-1 d-flex justify-content-between div-video1">
        <div class="w-100 pe-5">
          <div class="d-flex justify-content-center">
            <div class="d-flex align-items-center">
              <div class="pre">
                <h3 class="title-section-1" style="font-size: 2.1vw;text-transform: uppercase">
                  Matrizes e projetos de Bordado
                </h3>
                <p class="subtitle-section-size-emphasis">
                  "Clique em conheça nossos produtos" e descubra tudo que podemos te ofercer, venha conhecer nossos
                  produtos.
                </p>
              </div>
            </div>
          </div>
        </div>
        <div class="w-100 d-flex flex-column justify-content-center align-items-center" style="width: 90%;">
          <img src="/images/favicon.png" alt="" class="bg_video_player w-75" id="autoPlayForce">
          <div class="w-100 content-modal-btn d-flex justify-content-center"><a href="{{ route('ecomm') }}"
              class="btn play-button" onclick="">CONHEÇA NOSSOS PRODUTOS</a></div>
        </div>
      </div>
    </div>
  </section>

  <section>
    <div class="background-complete-bloco1-2 carousel-steps">
      <div class="" style="border: none;">
        <div class="w-100 pe-5">
          <h3 class="font-title-sections text-center">
            Pri Bonasorte
          </h3>
        </div>


        <div class="container-cards">
          <section class="section is--scroll-cards">
            <div class="w-layout-blockcontainer w-container container-cards-all">
              <div class="scroll-cards">
                <div data-w-id="a964f1d2-1c0f-28c3-6764-a4210c69d5d2" class="scroll-cards__inner">
                  <div class="scroll-cards__card _1 card-height card-scan">
                  </div>
                  <div class="scroll-cards__card _2 card-height card-recommend">
                  </div>
                  <div class="scroll-cards__card _3 card-height card-expand">
                  </div>
                  {{-- <div class="scroll-cards__card _4 card-height card-support">
                  </div> --}}
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
    </div>
  </section>

  <section class="sections-default why-life">
    <div class="background-complete-bloco background-complete-bloco1">
      <div class="section-1 justify-content-center w-100 ">
        <div class="d-flex w-100 justify-content-center pe-5" style="flex-wrap: wrap;">
          <div class="mt-5 me-4 why-life-div-txt" style="width: 50%">
            <h3 class="font-title-sections text-center"
              style="margin-bottom: 20px !important; margin-top: 40px !important;">
              <span style="font-weight: 400; color: #1a1a1a;">POR QUE </span><span>PRI BONASORTE?</span>
            </h3>
            <p class="emphasis-text-2 text-center">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptate
              dolores aperiam magni assumenda maxime quod corrupti dolorum nisi dolor nostrum. Optio, amet assumenda natus
              quis soluta qui pariatur repellat reprehenderit?mend nutritional supplement solutions with our proprietary
              epigenetic
              nutraceuticals/dietary supplements.</p>
            <p class="emphasis-text-2 text-center">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Voluptate
              dolores aperiam magni assumenda maxime quod corrupti dolorum nisi dolor nostrum. Optio, amet assumenda natus
              quis soluta qui pariatur repellat reprehenderit?mend nutritional supplement solutions with our proprietary
              epigenetic
              nutraceuticals/dietary supplements.</p>
          </div>
          {{-- <div class="mt-5 ms-4 d-flex align-items-center why-life-div-img"> --}}
          <div class="content-img-why" style="max-width: 100%">
            <img src="/images/favicon.png" class="card-img-content card-img-content-2" alt="">
          </div>
          {{-- </div> --}}
        </div>
      </div>
    </div>
  </section>

  <section class="sections-default div-founders">
    <div class="background-complete-bloco background-complete-bloco1">
      <div class="section-1 justify-content-center w-100">
        <div class="w-100  d-flex justify-content-center">
          <h3 class="font-title-sections text-start">
            <span style="font-weight: 400; color: #1a1a1a;">Palavras da </span><span>CRIADORA</span>
          </h3>
        </div>
        <div class="d-flex w-100 justify-content-center founders-content">
          <div class="mt-5 d-flex me-4 justify-content-center align-items-center" style="width: 60%">
            <p class="subtitle-section-size text-center">"------------- ----------- ----------- ------------ ----------"
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="sections-default" style="height: max-content">
    <div
      class="background-complete-bloco background-complete-bloco1 flex-column align-items-center px-0"style="height: max-content">
      <div class="section-1 justify-content-center" style="width: 91%;">
        <div class="w-100 pe-5">
          <h3 class="font-title-sections text-center">
            O que as pessoas dizem?
          </h3>
        </div>
        <div class="mt-5">
          <div class="d-flex justify-content-between videos">
            <div>
              <img src="{{ asset('/images/video-empty.png') }}" class="midia-people-say" alt="">
            </div>
            <div>
              <img src="{{ asset('/images/video-empty.png') }}" class="midia-people-say" alt="">
            </div>
            <div>
              <img src="{{ asset('/images/video-empty.png') }}" class="midia-people-say" alt="">
            </div>
          </div>
        </div>
      </div>
      <div class="mt-5 section-1 container-carosel-images">
        <div class="d-flex justify-content-center content-carosel-images">
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-1.png') }}" class="img-item-carosel" alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel" alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-2.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-3.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-4.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-5.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-6.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-7.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-8.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-9.png') }}" class="img-item-carosel ms-2"
              alt="">
          </div>
          <div>
            <img src="{{ asset('/images/last-section-newsite/img-10.png') }}" class="img-item-carosel" alt="">
          </div>
        </div>
      </div>
    </div>
  </section>
  <script>
    $(document).ready(function() {
      $('.carousel').slick({
        autoplay: true,
        autoplaySpeed: 5000, // Tempo de transição entre os slides em milissegundos (5 segundos neste exemplo)
        dots: true, // Exibir navegação por bolinhas
        arrows: false, // Ocultar setas de navegação
        infinite: true, // Permitir rolagem infinita
        speed: 1000, // Velocidade da transição dos slides
        slidesToShow: 1, // Quantidade de slides a serem exibidos simultaneamente
        slidesToScroll: 1 // Quantidade de slides a serem rolados por vez
      });

      $('.carousel-prev-next .prev').click(function() {
        $('.carousel').slick('slickPrev');
      });

      $('.carousel-prev-next .next').click(function() {
        $('.carousel').slick('slickNext');
      });
    });


    function animateValue(obj, start, end, duration) {
      if (start === end) return;
      var range = end - start;
      var current = start;
      var increment = end > start ? 1 : -1;
      var stepTime = Math.abs(Math.floor(duration / range));
      var timer = setInterval(function() {
        current += increment;
        obj.innerHTML = current;
        if (current == end) {
          clearInterval(timer);
        }
      }, stepTime);
    }

    window.onload = function() {
      const counters = document.querySelectorAll('.contador')
      counters.forEach((counter, index) => {
        animateValue(counter, 0, 97, 3000);
      })
      // Adicione mais chamadas de função para os outros elementos, se necessário
    };
    // Função para abrir o modal
    function openModal() {
      var modal = document.getElementById("video-modal");
      modal.style.display = "flex";
      document.getElementById("videoModal").play();
    }

    // Função para fechar o modal
    function closeModal() {
      var modal = document.getElementById("video-modal");
      modal.style.display = "none";
      document.getElementById("videoModal").pause();
    }

    function hideOrHiddenOptions() {
      if ($('.options-link').css('display') == 'none') {
        $('.options-link').css('display', 'flex')
      } else {
        $('.options-link').css('display', 'none')
      }

    }
  </script>
@endsection
