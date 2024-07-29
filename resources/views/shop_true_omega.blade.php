@extends('layouts.header_page')
@section('title', 'Lifeprosper | True Omega')
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
                    <center><p class="text-title">True Omega</p></center>
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary">
    <section class="master_zero">
        <div class="container" style="padding: 50px 10%;">

            <div class="line-content">
                <p class="subtitulo-content">Omega</p>
            </div>

            <div class="line-content">
                <p class="text-content">Experience the pinnacle of wellness with Omega Supplements, meticulously crafted to nourish your mind, body, and spirit. Unleash your inner radiance as our high-end formula combines the power of nature with cutting-edge scientific advancements. Immerse yourself in a world of pure luxury and let our Omega Supplements be your ultimate companion on the path to holistic well-being. With every capsule, you embark on a transformative journey, revitalizing your vitality and enhancing your overall health. Elevate your life and embrace the extraordinary benefits that await you.
                </p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Key benefits:</p>

                <ul>
                    <li><p class="text-content">Amplify brain function and enhance cognitive abilities.
                    </p></li>

                    <li><p class="text-content">Promote a healthy heart and support cardiovascular well-being.
                    </p></li>

                    <li><p class="text-content">Boost joint flexibility and improve overall mobility.
                    </p></li>

                    <li><p class="text-content">Nourish skin from within, revealing a youthful glow.
                    </p></li>

                    <li><p class="text-content">Fortify your immune system for enhanced resilience.
                    </p></li>

                    <li><p class="text-content">Support eye health and maintain optimal vision.
                    </p></li>

                    <li><p class="text-content">Alleviate inflammation and enhance overall well-being.
                    </p></li>

                    <li><p class="text-content">Improve sleep quality and promote a restful night's sleep.
                    </p></li>

                    <li><p class="text-content">Unleash a renewed sense of energy and vitality.
                    </p></li>
                </ul>
            </div>

            <div class="buster">
                <div class="line-content">
                    <p class="subtitulo-content">Fuel your health, nourish your body, and embrace vitality with True Omega</p>
                    <p class="text-content">Revitalize your health and embrace the goodness of nature with True Omega, our premium quality supplement packed in a sleek 250 ml bottle. Crafted with care and precision, True Omega is a synergistic blend of Cod Liver Oil and Norwegian Camelina Oil, carefully extracted to preserve the purest form of omega fatty acids essential for your overall well-being. Let's dive deeper into the extraordinary benefits and exceptional qualities of True Omega.</p>
                </div>

                <div class="line-content">
                    <p class="subtitulo-content">Nature's Symphony of Essential Nutrients!</p>
                    <p class="text-content">True Omega is a symphony of essential nutrients carefully selected to optimize your well-being. Let's dive into the treasure trove of ingredients that make True Omega a great health elixir:</p>

                    <div class="content-buster">
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/omega.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Omega Fatty Acids:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>True Omega harnesses the full potential of omega-3 fatty acids, including Omega 3, Omega 7, and Omega 9, which promote heart, brain, and vision health.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/omega2.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>DHA (Docosahexaenoic Acid) and EPA (Eicosapentaenoic Acid):</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>These vital omega-3 fatty acids are essential for optimal brain function, heart health, and normal blood triglyceride levels. An 8 ml serving of True Omega provides over 1500 mg of different omega-3 fatty acids, including 500 mg of EPA and 700 mg of DHA, surpassing the recommended daily intake.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/acid.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>ALA (Alpha-Linolenic Acid):</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>True Omega also contains ALA, another omega-3 fatty acid that supports overall health and well-being.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/Cholecalciferol.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Cholecalciferol:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>Including cholecalciferol, also known as vitamin D3, ensures your body receives adequate levels of this crucial nutrient, promoting bone health and supporting your immune system.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/Sweetener.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Natural Sweetener:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>To enhance your True Omega experience, we've added a natural sweetener from steviol glycosides. No artificial additives, just pure indulgence!</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/Oil.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Fish Oil and Camelina Oil:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>We've combined the finest quality wild arctic fish oil with nourishing camelina oil to deliver a comprehensive blend of healthy fats that support brain, heart, and vision health.</p>
                            </div>
                        </div>
                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/Antioxidant.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Antioxidant (Mixed Tocopherols):</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>True Omega contains natural antioxidants, protecting delicate fatty acids from oxidation and ensuring the utmost freshness and potency.</p>
                            </div>
                        </div>

                        <div class="card-buster">
                            <div class="box-icon-card" style="background-image: url('/images/Natural_Orange_Citrus_Flavor.png'); background-size: 100%;"></div>
                            <div class="box-title-card">
                                <p class="text-content"> <b>Natural Orange/Citrus Flavor:</b></p>
                            </div>
                            <div class="box-text-card">
                                <p>Enjoy the refreshing and invigorating taste of True Omega with its natural hint of citrus. Say goodbye to fishy aftertastes!.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="line-content">
                <p class="subtitulo-content">Unleash the Benefits of True Omega</p>

                <ul style="display: none;">
                    <li><p class="text-content"> <b>Boost Brain Health:</b> True Omega's DHA content maintains normal brain function, supporting cognitive performance and overall mental well-being. Unlock your full potential with a clear and focused mind!
                    </p></li>

                    <li><p class="text-content"> <b>Enhance Vision:</b> The DHA present in True Omega aids in the normal development of the eye, ensuring optimal visual acuity. Keep your eyes sharp and vibrant!
                    </p></li>

                    <li><p class="text-content"> <b>Support Heart Health:</b> True Omega's potent combination of DHA and EPA contributes to the heart's normal function, promoting cardiovascular well-being. Prioritize your heart's health!
                    </p></li>

                    <li><p class="text-content"> <b>Maintain Healthy Blood Triglyceride Levels:</b> True Omega's double dose of omega-3 fatty acids DHA helps maintain normal blood triglyceride levels, supporting a balanced lipid profile.
                    </p></li>

                    <li><p class="text-content"> <b>Nourish the Growing Fetus and Breastfed Infants:</b> For pregnant and lactating mothers, True Omega offers essential DHA that contributes to the fetus's and breastfed infants' normal brain development. Ensure a strong foundation for your little ones!
                    </p></li>

                    <li><p class="text-content"> <b>Support Visual Development in Infants:</b> True Omega's DHA plays a crucial role in the normal visual development of infants up to 12 months of age. Nurture their sight as they explore the world!
                    </p></li>
                </ul>

                <div class="content-buster">
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/cerebro.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Boost Brain Health:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>True Omega's DHA content maintains normal brain function, supporting cognitive performance and overall mental well-being. Unlock your full potential with a clear and focused mind!</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/olho.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Enhance Vision:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>The DHA present in True Omega aids in the normal development of the eye, ensuring optimal visual acuity. Keep your eyes sharp and vibrant!</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/coracao.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Support Heart Health:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>True Omega's potent combination of DHA and EPA contributes to the heart's normal function, promoting cardiovascular well-being. Prioritize your heart's health!</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/coracao-de-mao.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Maintain Healthy Blood Triglyceride Levels:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>True Omega's double dose of omega-3 fatty acids DHA helps maintain normal blood triglyceride levels, supporting a balanced lipid profile.</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/cabeca-de-crianca.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Nourish the Growing Fetus and Breastfed Infants:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>For pregnant and lactating mothers, True Omega offers essential DHA that contributes to the fetus's and breastfed infants' normal brain development. Ensure a strong foundation for your little ones!</p>
                        </div>
                    </div>
                    <div class="card-buster">
                        <div class="box-icon-card-icon"><center><img style="width: 80px;" src="/images/peca-de-quebra-cabecas.png" alt=""></center></div>
                        <div class="box-title-card">
                            <center><p class="text-content"> <b>Support Visual Development in Infants:</b></p></center>
                        </div>
                        <div class="box-text-card">
                            <p>True Omega's DHA plays a crucial role in the normal visual development of infants up to 12 months of age. Nurture their sight as they explore the world!</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Experience Pure Bliss for Your Body and Mind!</p>
                <p class="text-content">True Omega goes beyond ordinary supplements, providing a genuinely extraordinary health experience. Here's why you should make True Omega your daily companion:</p>

                <ul>
                    <li><p class="text-content"> <b>Unmatched Purity:</b> True Omega is sourced from the freshest wild arctic fish and camelina oil, ensuring the highest quality and purity for your well-being. We prioritize your health above all else!
                    </p></li>

                    <li><p class="text-content"> <b>Non-Detectable Fish Taste and Smell:</b> Bid farewell to unpleasant fishy flavors and odors. True Omega has been carefully crafted to provide a delectable taste experience without lingering aftertaste.
                    </p></li>

                    <li><p class="text-content"> <b>100% Traceability:</b> : We believe in transparency. With True Omega, you can trace the origin of every ingredient, assuring you of its superior quality and sustainable sourcing.
                    </p></li>

                    <li><p class="text-content"> <b>Perfect for the Whole Family:</b> True Omega is a supplement you can trust and share. Its premium quality and delightful taste makes it ideal for the entire family's well-being.
                    </p></li>
                </ul>
            </div>

            <div class="line-content">
                <p class="subtitulo-content-max">Omega 3 Dry Blood Testing</p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Unlock the Secrets to Optimal Health</p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Harness the Power of Knowledge for a Healthier You!</p>
            </div>

            <div class="line-content">
                <p class="text-content">Step into a world of personalized health with Omega 3 Dry Blood Testing. Discover the key to unlocking your Omega-3 Index and optimizing your well-being. This comprehensive testing kit provides the tools you need to measure, modify, and monitor your Omega-3 levels in just three easy steps. Elevate your health journey with precision and confidence!</p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Measure Your Omega-3 Index - Know Your Baseline</p>

                <ul>
                    <li><p class="text-content"> <b>Sample Collection Card:</b> Our Omega 3 Dry Blood Testing kit includes a convenient sample collection card that allows you to obtain a small blood sample effortlessly. Simple and mess-free, it ensures accurate results.
                    </p></li>

                    <li><p class="text-content"> <b>Lancet, Bandage, and Gauze Pad:</b> The kit includes a sterile lancet for painless fingertip pricking and a bandage and gauze pad for easy and hygienic sample collection. Your comfort and safety are our top priorities.
                    </p></li>

                    <li><p class="text-content"> <b>Alcohol Swab:</b> Maintain cleanliness and minimize the risk of contamination with the included alcohol swab. We take every precaution to ensure accurate results.
                    </p></li>

                    <li><p class="text-content"> <b>Return Envelope:</b> Once you've collected your blood sample, seal it in the provided return envelope and return it to our laboratory. Convenient and secure, your sample will be handled with utmost care.
                    </p></li>

                </ul>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Modify Your Omega-3 Index - Personalized Dietary Changes</p>
            </div>

            <div class="line-content">
                <p class="text-content">Knowing your Omega-3 Index is the first step toward optimization. With the insights gained from your test results, you can make simple dietary changes to elevate your Omega-3 levels. Empower yourself with the knowledge to enhance your overall health and well-being.
                </p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Monitor Your Omega-3 Index - Stay on Track</p>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Stay Ahead of the Curve - Proactively Manage Your Health!</p>

                <ul>
                    <li><p class="text-content"> <b>Regular Testing:</b> We recommend testing your Omega-3 Index every four months to ensure your levels remain optimal. You can make informed decisions about your dietary habits, and supplementation needs by monitoring your progress.
                    </p></li>

                    <li><p class="text-content"> <b>Personalized Guidance:</b> Our expert team supports you on your health journey. Each test will give you detailed insights and personalized recommendations to help you reach and maintain your desired Omega-3 Index.
                    </p></li>
                </ul>
            </div>

            <div class="line-content">
                <p class="subtitulo-content">Your Journey to Optimal Health Starts Here!</p>

                <p class="subtitulo-content">Open the door to a healthier future - embrace True Omega today!</p>
            </div>
        </div>
    </section>
</main>

@endsection
