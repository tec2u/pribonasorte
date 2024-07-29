@extends('layouts.header_page')
@section('title', 'Lifeprosper | About')
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

    .base-id {
      width: 100%;
      height: 100px;
    }

    .box_general {
      width: 100%;
      display: inline-block;
    }

    .box_general2 {
      width: 100%;
      margin-top: -30px;
      display: inline-block;
    }

    .box_float1 {
      width: 65%;
      margin-right: 5%;
      display: inline-block;
      float: left;
    }

    .box_float2 {
      width: 30%;
      display: inline-block;
      float: left;
    }

    img.image_ceo {
      width: 100%;
      margin-top: 30px;
    }

    @media all and (min-width:2px) and (max-width:820px) {
      .box_float1 {
        width: 100%;
        margin-right: 5%;
        display: inline-block;
        float: left;
      }

      .box_float2 {
        width: 100%;
        display: inline-block;
        float: left;
      }

      img.image_ceo {
        width: 100%;
        margin-top: 10px;
        margin-bottom: 30px;
      }

      p.name_ceo {
        margin-top: 20px;
      }
    }
  </style>

  <main id="main" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <p class="text-title">About</p>
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <main id="background-primary">
    <section class="master_3">
      <div class="container" style="padding: 50px 10%;">

        <div class="base-id" id="lifeprosper"></div>
        <div class="line-content">
          <p class="subtitulo-content">About LifeProsper?</p>
        </div>

        <div class="line-content">
          <p class="text-content">LifeProsper is your trusted partner in the pursuit of optimal health and wellness. We
            are a leading health and wellness company dedicated to empowering individuals on their transformative journeys
            towards a vibrant and fulfilling life. With our exceptional range of supplements, minerals, vitamins, and test
            kits, we aim to provide you with the tools and resources necessary to achieve holistic well-being.</p>
        </div>

        <div class="base-id" id="vision"></div>
        <div class="line-content">
          <p class="subtitulo-content">Our Vision:</p>
        </div>

        <div class="line-content">
          <p class="text-content">To inspire and enable individuals to take charge of their health and unlock their full
            potential. </p>
        </div>

        <div class="line-content">
          <p class="text-content">We envision a world where everyone has access to the knowledge and products needed to
            live their healthiest and happiest lives. Through continuous innovation, rigorous research, and unwavering
            commitment, we strive to revolutionize the health and wellness industry and make a positive impact on people's
            lives.</p>
        </div>

        <div class="base-id" id="management"></div>
        <div class="line-content">
          <p class="subtitulo-content">Our Management:</p>
        </div>

        <div class="line-content">
          <p class="text-content">Behind LifeProsper stands a team of visionary leaders who are passionate about promoting
            wellness and making a difference. Our management team combines expertise from diverse fields, including
            nutrition, medicine, research, and business. With their wealth of knowledge and experience, they guide the
            company towards delivering exceptional products and services that meet the evolving needs of our valued
            customers.</p>
        </div>

        <div class="line-content">
          <p class="text-content">Each member of our management team brings a unique perspective and skill set, ensuring a
            dynamic approach to our operations. Their unwavering dedication to quality, transparency, and innovation is
            what sets us apart in the industry. Together, we strive to create a company culture that fosters growth,
            encourages collaboration, and prioritizes the well-being of our customers.</p>
        </div>

        <div class="line-content">
          <p class="text-content">Join us on this incredible journey of health and wellness. Together, let's unlock your
            potential and thrive with LifeProsper.</p>
        </div>


        <div class="base-id" id="vision"></div>
        <div class="line-content">
          <p class="subtitulo-content">CEO</p>
        </div>

        <div class="box_general">

          <div class="box_float1">

            <div class="line-content">
              <p class="text-content">In today's world, where business and innovation go hand in hand, it is unique to
                encounter an individual with an exceptional perspective on building international teams. Juraj Mojžiš, our
                new CEO, brings with him a wealth of experience in constructing teams of hundreds of thousands of
                distributors around the globe, not just once, but multiple times. His story and message promise a
                different approach to the MLM industry and true value for everyone involved.</p>
            </div>

            <div class="line-content">
              <p class="text-content">Unlimited Education
                True success in business lies in the growth of individuals and teams. Juraj's approach emphasizes the
                importance of education and personal development. His vision of a university uniting the best minds from
                various fields supports the growth not only of team members but of anyone aspiring to achieve more. In
                this way, he forges strong and high-quality teams while reminding us that the success of business can
                never surpass the success and growth of the people themselves.</p>
            </div>

          </div>

          <div class="box_float2">
            <img class="image_ceo" src="/img/ceo_lifeprosper.jpeg" alt="ceo">
            <p style="margin-top: 10px;"><b>JURAJ MOJZIS</b>, CEO LIFEPROSPER</p>
          </div>

        </div>

        <div class="box_general2">

          <div class="line-content">
            <p class="text-content">The Future Written by Innovations
              Juraj Mojžiš is not just a CEO; he is one of us, striving to bring about positive changes. Together, we can
              write a story that leaves a lasting impact. He is a visionary who pushes boundaries and builds upon
              fundamental values. His approach to building international teams and advancing MLM into new dimensions sets
              him apart. His determination to bring values, education, and real solutions is the driving force of a new
              era in this industry.</p>
          </div>

          <div class="line-content">
            <p class="text-content">Conclusion
              Juraj Mojžiš brings a new twist to the world of MLM. His story of team-building expertise and exceptional
              perspective on education, products, and innovation promises a new era for this field. We are excited to
              witness how his vision will influence society and open doors to new possibilities.</p>
          </div>
        </div>

      </div>
    </section>
  </main>

@endsection
