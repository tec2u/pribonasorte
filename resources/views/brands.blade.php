@extends('layouts.header_page')
@section('title', 'Pribonasorte | Brands')
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
</style>

<main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);"
        id="herosection">
        <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
            style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <center><p class="text-title">Our brands</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary">
    <section class="master_2">
        <div class="container" style="padding: 50px 20px;">

            <div class="line-content">
                <p class="subtitulo-content">Fuel Your Potential with Our Premium Supplements</p>
            </div>

            <div class="line-content">
                <p class="text-content">Auraway is dedicated to empowering individuals on their wellness journey. We offer a range of premium supplements from reputable brands meticulously crafted with high-quality ingredients to optimize your health and unleash your full potential.</p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Embrace a Healthier Lifestyle with Our Trusted Supplements</p>
            </div>

            <div class="line-content" style="margin-bottom: 200px;">
                <p class="text-content">Experience the transformative power of nature with our all-natural supplements. From boosting immunity to promoting vitality, our products are carefully formulated to enhance your overall well-being, providing you with the essential nutrients your body craves.</p>
            </div>
        </div>
    </section>
</main>

@endsection
