@extends('layouts.header_page')
@section('title', 'Pribonasorte | Payment')
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
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
        style="width: 100%;height: 50vh;background: linear-gradient(rgba(0,0,0,0.83), rgba(0,0,0,0.78)), url(&quot;../assets/img/fitness.jpg?h=19923c9d1c5b6e5752b86d1ffaf52718&quot;) center / cover no-repeat;">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <p class="text-title">Return and Complaint Policy</p>
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <section id="background-primary"
    style="background-image: url('/images/fundo_master2.png'); background-size: 100%; background-repeat: no-repeat;">
    <div class="container" style="padding: 50px 15%;">

      <div class="line-content" style="margin-top: 50px;">
        <p class="subtitulo-content">Return and Complaint Policy</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">1 - Returns We believe you will be satisfied with our products. However, if for any reason
          you are not happy with your purchase, you have the right to return unopened goods within 14 days from the date
          of delivery.</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">2 - Conditions for Returns To be eligible for a return, the merchandise must be unused,
          undamaged, and in its original packaging. The customer is responsible for proper packaging of the returned item.
          Returned goods must be accompanied by a valid proof of purchase.</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">3 - Opened Goods Please note that opened goods that are affected by the return will not be
          accepted. Only unopened goods in their original packaging can be returned.</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">4 - Return Procedure If you wish to return an item, please contact us at
          return@Pribonasorte.euand inform us of your intention to return the goods. We will provide you with detailed
          instructions on how to proceed with the return.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">5 - Refunds Refunds for returned goods will be processed within 14 days from the receipt
          of the returned item. The refund will be issued using the same payment method used for the original transaction,
          unless otherwise agreed.</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">6 - Faulty Goods In the event that you receive damaged or faulty goods, please notify us
          immediately at return@Pribonasorte.eu. We will handle the complaint in accordance with applicable laws and
          regulations.</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">7 - Exceptions Some products may have limited or modified return conditions. If
          applicable, this information will be specified on the product page.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">8 - Changes to the Return and Complaint Policy We reserve the right to modify or update
          this Return and Complaint Policy at any time. Any changes to the policy will be effective immediately upon
          posting the updated version on our website.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content">9 - Contact If you have any questions or concerns regarding our Return and Complaint
          Policy, please feel free to contact us at return@Pribonasorte.eu.</p>
      </div>

    </div>
  </section>

@endsection
