@extends('layouts.header_page')
@section('title', 'Pribonasorte | LP Faq')
@section('content')

<style>
    .text-title{
        font-size: 50px;
        font-weight: bold;
        color: #ffffff;
        text-transform: uppercase;
    }
    .box-category{
        width: 100%;
        padding: 30px;
        border-radius: 10px;
        margin-bottom: 10px;
        background-color: #cdcdcd;
    }
    .title-categoy{
        font-size: 25px;
        font-weight: bold;
        color: #212121;
        margin-bottom: 10px;
    }

    .text-response{
        font-size: 15px;
    }
</style>

<main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);"
        id="herosection">
        <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
            style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <center><p class="text-title">LP FAQ</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<section id="background-primary" style="background-image: url('/images/fundo_master2.png'); background-size: 100%; background-repeat: no-repeat;">
    <div class="container" style="padding: 50px 10%;">

        <div class="box-category">
            <p class="title-categoy">COMPANY</p>
        </div>

        <div class="accordion accordion-flush" id="accordionFlushExample">
            @foreach ($faqs as $faq)

            @php
                $response = strip_tags($faq->response);
                $response = mb_convert_encoding($response, 'UTF-8', 'HTML-ENTITIES');
            @endphp

            <div class="accordion-item">
                <h2 class="accordion-header" id="flush-headingOne">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse{{ $faq->id }}" aria-expanded="false" aria-controls="flush-collapseOne">
                    {{ $faq->question }}
                </button>
                </h2>
                <div id="flush-collapse{{ $faq->id }}" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                <div class="accordion-body"><p class="text-response">{{ $response }}</p></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection


