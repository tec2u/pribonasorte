@extends('layouts.header_page')
@section('title', 'Lifeprosper | Melatonin')
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
    .buster{
        display: inline-block;
        margin-top: 30px;
    }
    .content-buster{
        display: inline-block;
        margin: 30px 0px
    }
    .card-buster{
        width: 48%;
        margin: 0px 1%;
        display: inline-block;
        float: left;
    }
    .box-icon-card{
        width: 100%;
        height: 130px;
        /* background: #212121; */
        border: solid 1px #eeeeee;
    }
    .box-icon-card-icon{
        width: 100%;
        height: 80px;
        /* background: #212121; */
        border: solid 1px #eeeeee;
    }
    .box-title-card{
        width: 100%;
        height: 60px;
    }
    .box-text-card{
        width: 100%;
        height: 180px;
    }

    /* RESPONSIVIDADE */
    @media all and (min-width:2px) and (max-width:820px) {
        .card-buster{
        width: 100%;
        margin: 0px 1%;
        display: inline-block;
        float: left;
        margin-bottom: 10px;
        }
    }
</style>

<main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);"
        id="herosection">
        <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
            style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    <center><p class="text-title">Melatonin</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary">
    <section class="master_1">
        <div class="container" style="padding: 50px 10%;">

            <div class="line-content">
                <p class="subtitulo-content">Melatonin</p>
            </div>

            <div class="line-content">
                <p class="text-content">Dive into the tranquil world of dream-filled nights with our Serenity Dream Melatonin Supplements. Crafted with utmost precision and care, these extraordinary capsules are meticulously designed to embrace your senses and lull you into a deep, restorative sleep. Let the enchanting power of melatonin transport you to a realm of unparalleled tranquility, where stress and restlessness fade away. As the moon casts its soothing glow, our melatonin supplements work harmoniously with your body's natural rhythm, gently guiding you into a peaceful slumber. Awaken refreshed, rejuvenated, and ready to seize the day. Experience the magic of Serenity Dream and embrace the profound benefits it brings to your sleep routine.
                </p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Key benefits:</p>

                <ul>
                    <li><p class="text-content">Induces peaceful slumber for enhanced sleep quality.
                    </p></li>

                    <li><p class="text-content">Regulates sleep-wake cycles for improved circadian rhythm.
                    </p></li>

                    <li><p class="text-content">Reduces the time required to fall asleep, promoting faster onset of rest.
                    </p></li>

                    <li><p class="text-content">Supports healthy sleep patterns to ensure restorative rest.
                    </p></li>

                    <li><p class="text-content">Facilitates waking up refreshed and energized for the day ahead.
                    </p></li>

                </ul>
            </div>

            <div class="buster">
                <div class="line-content">
                    <p class="subtitulo-content">Your Path to Restful Sleep</p>
                </div>

                <div class="line-content">
                    <p class="subtitulo-content">Embrace Tranquility and Experience Blissful Sleep!</p>
                    <p class="text-content">TINY Melatonin is a revolutionary sleep-enhancing liquid, designed to support fast falling asleep and deep, restorative sleep. Crafted with natural ingredients and powered by patented TINYsphere® technology, this highly efficient sleep aid will transform your bedtime routine. Get ready to embrace tranquility and experience blissful sleep with TINY Melatonin!</p>
                </div>

                <div class="line-content">
                    <div class="content-buster">
                        <div class="line-content">
                            <p class="subtitulo-content">The Science Behind TINY Melatonin</p>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/istockphoto-1300036753-612x612.jpg'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Patented TINYsphere® Technology:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>TINY Melatonin utilizes a proprietary TINYsphere® technology, which encapsulates the melatonin in microscopic spheres. This breakthrough delivery system ensures twice the bioavailability of conventional melatonin, allowing your body to absorb it more efficiently.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/depositphotos_54413495-stock-illustration-stopwatch-flat-icon-with-long.webp'); background-size: 100%; background-position: center;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Fast-Acting Formula:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>With TINY Melatonin, you can enjoy a rapid onset of its sleep-enhancing effects. The carefully formulated liquid is designed to be absorbed through the oral mucosa, bypassing the gastrointestinal tract. This means you can experience its benefits quickly, regardless of your meal schedule.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/moléculas-41032158.webp'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Enhanced Bioavailability:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>TINY Melatonin's TINYsphere® technology enables optimal absorption of melatonin into the body. This increased bioavailability ensures you receive the maximum benefits from each dose, supporting your journey to restful sleep.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="line-content">
                <p class="subtitulo-content">Nature's Best for Your Peaceful Nights!</p>

                <div class="content-buster">
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/pingos-de-chuva.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Water:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>Purified water forms the foundation of TINY Melatonin, ensuring a safe and clean product.</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/ponto-de-condensacao-da-agua.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Humectant (Glycerin):</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>Glycerin, a natural humectant, helps retain moisture and maintain the liquid's viscosity, providing a smooth and consistent texture. </p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/garrafa-de-prescricao.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Solvent (Ethanol): </b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>Ethanol serves as a solvent in TINY Melatonin, aiding extraction and formulation. </p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/mao-segurando-agua.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Sunflower Oil:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>This natural oil contributes to TINY Melatonin's overall quality and stability, ensuring its freshness and potency.</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/arvore-de-folha-caduca.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Emulsifier (Lecithin - Soy):</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>Lecithin derived from soy acts as an emulsifier, allowing the ingredients to mix uniformly and maintain a consistent suspension.</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/capsulas.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Melatonin:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>The star ingredient, melatonin, is a natural hormone produced by the pineal gland. TINY Melatonin encapsulates it within TINYspheres®, providing optimal absorption and maximizing its sleep-enhancing benefits. </p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/meio-conta-gotas.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Antioxidant (Alpha-Tocopherol):</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>lpha-tocopherol, a potent antioxidant, safeguards the integrity and freshness of TINY Melatonin, ensuring its efficacy.</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/vento.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Aroma (Orange Peel Oil):</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>The delightful aroma of orange peel oil enhances your experience, creating a soothing and inviting ambiance. </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Unlock a World of Serenity and Rejuvenation!</p>

                <ul>
                    <li><p class="text-content"> <b>Reduced Sleep Onset Time:</b> TINY Melatonin helps shorten the time to fall asleep, allowing you to transition into a peaceful slumber faster.
                    </p></li>

                    <li><p class="text-content"> <b>Enhanced Sleep Quality:</b> By improving the depth and duration of your sleep, TINY Melatonin ensures you wake up feeling revitalized and ready to take on the day.
                    </p></li>

                    <li><p class="text-content"> <b>Alleviation of Jet Lag:</b> TINY Melatonin aids in reducing the subjective feelings of jet lag, helping you adjust and adapt to new time zones more smoothly.
                    </p></li>

                    <li><p class="text-content"> <b>Premium Quality:</b> TINY Melatonin is produced according to strict food-grade standards, ensuring you receive a product of the highest quality and purity.
                    </p></li>

                    <li><p class="text-content"> <b>Safety and Compliance:</b> We prioritize your well-being. TINY Melatonin is a vegan, vegetarian, and gluten-free nutritional supplement. However, it's not suitable for pregnant women and children. Please keep it out of reach of children and store it in a cool, dry place, protected from light and heat.
                    </p></li>
                </ul>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Unwind and Indulge in Blissful Sleep with TINY Melatonin!</p>

                <p class="subtitulo-content">Embrace Tranquility, Embrace TINY Melatonin!</p>
            </div>
        </div>
    </section>
</main>

@endsection
