@extends('layouts.header_page')
@section('title', 'Pribonasorte | Vitamin and Minerals')
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
    .box-product{
        width: 100%;
        display: inline-block;
    }
    .image-product{
        width: 50%;
        height: 430px;
        background-color: #eeeeee;
        float: left;
    }
    .info-product{
        width: 50%;
        display: inline-block;
        float: left;
        padding-left: 40px;
    }
    .title-product{
        font-size: 30px;
        line-height: 33px;
        font-weight: bold;
        margin-bottom: 40px;
    }
    .texto-product{
        margin-top: 10px;
    }
    .sub-text-product{
        font-size: 15px;
    }
    .price-product b{
        font-size: 25px;
    }
    button.btn-buy-product{
        width: 100%;
        height: 40px;
        border-radius: 5px;
        background: #212121;
        color: #ffffff;
        margin-top: 10px;
        text-transform: uppercase;
        font-weight: bold;
    }
    ul.list-button-descriptions{
        margin: 30px 0px 0px -30px;
    }
    ul.list-button-descriptions li{
        display: inline-block;
        list-style: none;
        margin-right: 30px;
    }
    ul.list-button-descriptions li p{
        font-size: 15px;
        border-bottom: 2px solid orange;
        padding-bottom: 10px;
        cursor: pointer;
    }
</style>

<main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);"
        id="herosection">
        <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
            style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <center><p class="text-title">Vitamin and Minerals</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary">
    <section class="master_1">
        <div class="container" style="padding: 50px 10%;">

            <div class="line-content">
                <p class="subtitulo-content">Vitamin and minerals</p>
            </div>

            <div class="line-content">
                <p class="text-content">Discover the Fountain of Vitality with our revolutionary line of vitamin and mineral supplements. Elevate your health and wellness journey to new heights with our high-end formulas, meticulously crafted to optimize your body's performance. Unleash the power of nature's bounty as our supplements replenish and fortify your body with essential nutrients, unlocking your true potential and enhancing your overall well-being.
                </p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Key benefits:</p>

                <ul>
                    <li><p class="text-content">Boost your energy levels and experience sustained vitality throughout the day.
                    </p></li>

                    <li><p class="text-content">Strengthen your immune system, providing a shield against illness and promoting optimal health.
                    </p></li>

                    <li><p class="text-content">Enhance mental clarity and focus, improving cognitive function and sharpening your mind.
                    </p></li>

                    <li><p class="text-content">Support bone health and maintain strong, resilient skeletal structure.
                    </p></li>

                    <li><p class="text-content">Promote healthy skin, hair, and nails, radiating a natural and vibrant appearance.
                    </p></li>
                </ul>
            </div>

            <div class="box-product" style="display: none; margin-top: 30px;">
                <div class="image-product">
                    <div class="image-product-page"></div>
                    <img src="" alt="">
                </div>
                <div class="info-product">
                    <p class="title-product">Vitamin and Minerals</p>

                    <p class="texto-product">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iure, similique numquam? Ex perspiciatis animi obcaecati reprehenderit minima facilis quas veritatis laborum consequatur doloremque aut excepturi maiores sunt officia, maxime repudiandae.</p>
                    <p class="texto-product">Lorem ipsum dolor sit amet consectetur adipisicing elit. Non quae consectetur eos eum ab, voluptate doloremque cumque similique dignissimos perferendis dolorum reprehenderit eaque dicta amet nesciunt fugit culpa saepe atque?</p>

                    <p class="sub-text-product"><b>Content:</b> 100ml</p>
                    <p class="price-product"><b>$34,90</b></p>

                    <button class="btn-buy-product">Shop Now</button>
                </div>
            </div>

            <div style="display: none;" class="box-product">
                <ul class="list-button-descriptions">
                    <li><p id="description-pro">Description</p></li>
                    <li><p id="downloads-pro">Download</p></li>
                </ul>
            </div>

            <div style="display: none;" class="box-product">
                <div class="block-description">
                    <center>
                        <p style="margin-top: 30px;">No description</p>
                    </center>
                </div>
                <div style="display: none;" class="block-downloads">
                    <center>
                        <p style="margin-top: 30px;">No downloads</p>
                    </center>
                </div>
            </div>

        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
<script src="/js/script.js"></script>
<script>
    $(document).ready(function(){
        $("#description-pro").click(function(event){
            event.preventDefault();
            $(".block-description").fadeIn();
            $(".block-downloads").hide();
        });
    });

    $(document).ready(function(){
        $("#downloads-pro").click(function(event){
            event.preventDefault();
            $(".block-downloads").fadeIn();
            $(".block-description").hide();
        });
    });
</script>

@endsection
