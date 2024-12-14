@extends('layouts.header_page')
@section('title', 'Pribonasorte | Test Kit')
@section('content')

<style>
    .text-title{
        font-size: 50px;
        font-weight: bold;
        color: #ffffff;
        text-transform: uppercase;
    }
    .line-content{
        margin-bottom: 30px;
    }
    .subtitulo-content{
        font-weight: bold;
        font-size: 18px;
    }
    .text-content{
        margin-top: 30px;
        font-size: 16px;
    }
    .block-contact{
        width: 100%;
        display: inline-block;
    }
    .block-contact-prime{
        width: 50%;
        float: left;
        display: inline-block;
    }
    p.text-contact{
        font-size: 18px;
        padding: 0px;
        margin: 0px;
    }
</style>

<main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);"
        id="herosection">
        <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
            style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <center><p class="text-title">TESTE KIT</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary">
    <section class="master_1">
        <div class="container" style="padding: 50px 10%;">

            <div class="line-content">
                <p class="subtitulo-content">Test kits</p>
            </div>

            <div class="line-content">
                <p class="text-content">Revitalize your well-being with our cutting-edge testing kits, the epitome of precision and reliability. Explore the limitless possibilities of personalized health assessments, unlocking a world of knowledge within. Experience the future of self-care, as our kits empower you to take control of your journey to optimal vitality. Unveil the hidden secrets of your body's unique chemistry and unravel the mysteries that lie beneath the surface. Our kits harness the power of scientific advancement to enlighten and guide you towards holistic wellness.
                </p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Key benefits:</p>

                <ul>
                    <li><p class="text-content">Gain a deep understanding of your body's specific needs and make informed health decisions.
                    </p></li>

                    <li><p class="text-content">Take charge of your well-being by accessing valuable information in the comfort of your home.
                    </p></li>

                    <li><p class="text-content">Uncover potential health risks and address them proactively before they manifest.
                    </p></li>

                    <li><p class="text-content">Optimize your nutrient intake based on your individual requirements.

                    </p></li>

                    <li><p class="text-content">Save time and effort with our user-friendly testing process and quick, accurate results.
                    </p></li>

                    <li><p class="text-content">Rely on state-of-the-art technology and expert analysis to drive your health transformation.
                    </p></li>
                </ul>
            </div>
        </div>
    </section>
</main>

<section id="background-primary" style="background-image: url('/images/fundo_master2.png'); background-size: 100%; background-repeat: no-repeat;">
</section>

@endsection
