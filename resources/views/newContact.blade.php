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
            <p class="subtitulo-content">Contact LifeProsper: Enhance Your Well-being Now!
            </p>
        </div>

        <div class="line-content" style="margin-bottom: 50px;">
            <p class="text-content">Reach out to start your health and wellness journey. Our team is ready to answer your questions, provide expert advice, and help you choose the best products for your needs. Experience the LifeProsper difference today
            </p>
        </div>

        <div class="block-contact">
            <div class="d-flex justify-content-between">
                <div class="block-contact-prime">

                    <div class="line-content">
                        <p class="subtitulo-content">LifeProsper</p>
                    </div>
                    <p class="text-contact">LifeProsper/Intermodels s.r.o.</p>
                    <p class="text-contact">Pod Nouzovem 971/17</p>
                    <p class="text-contact">19700 Praha</p>
                    <p class="text-contact">ICO: 26126893</p>
                    <p class="text-contact">DIC: CZ26126893</p>
                    <p class="text-contact">Czech Republic</p>

                    <div class="line-content" style="margin-top: 40px;">
                        <p class="subtitulo-content">Support:</p>
                    </div>
                    <p class="text-contact">Phone: +420234688024</p>
                    <p class="text-contact">E-mail: support@lifeprosper.eu</p>

                    <p class="text-contact">Our opening hours are Monday – Friday</p>
                    <p class="text-contact">9 a.m – 2 p.m CEST</p>
                </div>
                <div class="content-img-contact">
                    <img src="{{ asset('images/contact-image.webp') }}" class="img-contact-only">
                </div>
            </div>
            <div class="block-contact">
                <p class="subtitulo-content" style="margin-top: 30px;">Please kindly check the return address on the specific
                    country. Please, contact Customer Service before you send any goods back.</p>
            </div>
            <div class="line-content text-center" style="margin-top: 40px;">
                <h1 class="subtitulo-content" style="font-size: 30px;">We are social</h1>
                <ul class="footer-network d-flex justify-content-center">
                    <li><a href="https://www.facebook.com/profile.php?id=61557861185751"><i class="fa-brands fa-facebook" style="font-size: 30px; color: #5576c0 !important;"></i></a></li>
                    <li><a href="https://www.tiktok.com/@lifeprosper.eu"><i class="fa-brands fa-tiktok" style="font-size: 30px; color: #000000 !important;"></i></a></li>
                    <li><a href="https://www.instagram.com/lifeprosper_official/" target="blank_"><i class="fa-brands fa-instagram" style="font-size: 30px; color: #fd2c4e !important;"></i></a></li>
                    <li><a href="https://wa.me/+421918142520" target="blank_"><i class="fa-brands fa-whatsapp" style="font-size: 30px; color: #0dc242 !important"></i></a></li>
                </ul>
            </div>
        </div>
</section>

@endsection
