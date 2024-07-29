@extends('layouts.header_page')
@section('title', 'Lifeprosper | Molecule of life')
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
                    <center><p class="text-title">Molecule of life</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary">
    <section class="master_1">
        <div class="container" style="padding: 50px 10%;">

            <div class="line-content">
                <p class="subtitulo-content">Molecule of life</p>
            </div>

            <div class="line-content">
                <p class="text-content">Unleash the Power Within with Molecule of Life, the ultimate elixir of vitality and well-being. Experience the synergy of cutting-edge science and natural wonders as you embark on a transformative journey to unlock your true potential. Our product has been meticulously designed to elevate your health and ignite your inner radiance. This groundbreaking creation is a testament to our unwavering commitment to your optimal health. Immerse yourself in a symphony of rejuvenation as Molecule of Life awakens your inner potential, paving the way for a vibrant and exuberant existence.
                </p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Key benefits:</p>

                <ul>
                    <li><p class="text-content">Revitalize your body and mind with our potent blend of bioactive compounds.
                    </p></li>

                    <li><p class="text-content">Enhance your immune system and bolster your natural defenses against daily challenges.
                    </p></li>

                    <li><p class="text-content">Fuel your energy reserves and overcome fatigue with our invigorating formula.
                    </p></li>

                    <li><p class="text-content">Achieve optimal cognitive function and sharpen your mental acuity.
                    </p></li>

                    <li><p class="text-content">Nourish your cells from within, promoting longevity and a youthful glow.
                    </p></li>

                    <li><p class="text-content">Support overall well-being and maintain a harmonious balance in body and spirit.
                    </p></li>

                    <li><p class="text-content">Embrace a life of vitality, radiance, and wellness with Molecule of Life.
                    </p></li>
                </ul>
            </div>
        </div>
    </section>
</main>

<section id="background-primary" style="background-image: url('/images/fundo_master2.png'); background-size: 100%; background-repeat: no-repeat;">
</section>

@endsection
