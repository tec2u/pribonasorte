@extends('layouts.header_newsite')
@section('title', 'Lifeprosper | Payment')
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

    ul.list_payment_ul li {
      list-style: none;
      display: inline-block;
      margin-right: 10px;
    }
  </style>

  <main id="main" class="main p-0">
    <section class="background_new_pages" id="herosection">
      <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5">
        <div class="container h-100">
          <div class="row justify-content-center align-items-center h-100">
            <center>
              <!-- <p class="text-title text_new_pages">Payment Policy</p> -->
            </center>
          </div>
        </div>
      </div>
    </section>
  </main>

  <section id="background-primary">
    <div class="container" style="padding: 50px 15%;">

      <div class="line-content" style="margin-top: 50px;">
        <p class="subtitulo-content">Payment Policy</p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">
          1. Accepted Payment Methods.
        </p>
        <p style="margin-top: 5px; font-size: 16px;">We accept the following payment methods for purchases made on our
          e-shop:
        <ul>
          <li>Credit cards (Visa, MasterCard);</li>
          <li>Debit cards;</li>
          <li>Apple Pay</li>
          <li>Google Pay;</li>
          <li>Bank transfers;</li>
          <li>USDt (crypto currency).</li>
        </ul>
        </p>
        <div style="display: inline-block;">
          <ul style="margin: 20px 0px 0px -25px; float: left;" class="list_payment_ul">
            <li><img style="width: 90px;" src="/img/comgatepay.png"></li>
            <li><img style="width: 50px;" src="/img/applepay.png"></li>
            <li><img style="width: 50px;" src="/img/googlepay.png"></li>
            <li><img style="width: 50px;" src="/img/mastercardpay.png"></li>
            <li><img style="width: 50px;" src="/img/visapay.png"></li>
          </ul>
        </div>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">
          2. Payment gateway provider ComGate.
        </p>
        <p style="margin-top: 5px; font-size: 16px;">ComGate Payments, a.s. link to the page: <a
            href="https://www.comgate.cz/cz/paymentbni-brana" target="_blank"
            style="color: #27032f;">https://www.comgate.cz/cz/paymentbni-brana</a></p>
        <p style="margin-top: 5px; font-size: 16px;">providing information on payment methods - payment by card and bank
          payment buttons with a basic explanation of the payment process</p>
        <a href="https://help.comgate.cz/v1/docs/cs/platby-kartou" target="_blank">
          <p style="margin-top: 5px; font-size: 16px; color: #27032f;">https://help.comgate.cz/v1/docs/cs/platby-kartou
          </p>
        </a>
        <a href="https://help.comgate.cz/docs/bankovni-prevody">
          <p style="margin-top: -15px; font-size: 16px; color: #27032f;">https://help.comgate.cz/docs/bankovni-prevody</p>
        </a>

        <p style="margin-top: 5px; font-size: 16px;">Contact information for ComGate Payments, a.s.</p>
        <p style="margin-top: -15px; font-size: 16px;">ComGate Payments, a.s.</p>
        <p style="margin-top: -15px; font-size: 16px;">Gočárova třída 1754 / 48b, Hradec Králové</p>
        <p style="margin-top: -15px; font-size: 16px;">E-mail: payment-podpora@comgate.cz</p>
        <p style="margin-top: -15px; font-size: 16px;">Phone: +420 228 224 267</p>

      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">3. Currency</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Currency All transactions on our e-shop are processed in EUR. Prices displayed on the website are inclusive of
          applicable taxes, unless otherwise stated.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">4. Payment Processing</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Payment Processing When you make a purchase, the payment will be processed immediately. In case of payment
          failure or any issues, please contact our customer support for assistance.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">5. Payment Security</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Payment Security We take the security of your payment information seriously. All online payments are processed
          through a secure and encrypted gateway to protect your personal and financial data. We do not store credit card
          or payment information on our servers.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">6. Order Confirmation</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Order Confirmation Once your payment is successfully processed, you will receive an order confirmation email
          with details of your purchase. Please keep this email for your records.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">7. Refunds and Returns</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Refunds and Returns for information on our Refund and Return Policy, please refer to our separate Refund and
          Return Policy page.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">8. Payment Disputes</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Payment Disputes in the event of any payment disputes or unauthorized transactions, please notify us
          immediately, and we will investigate and resolve the issue promptly.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">9. Failed Payments</p>
        <p style="margin-top: 5px; font-size: 16px;">
          Failed Payments if a payment fails during the checkout process, please ensure that your payment details are
          entered correctly or try using an alternative payment method. If you encounter persistent issues, please contact
          your bank or payment provider for assistance.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">10. Cancellation of orders </p>
        <p style="margin-top: 5px; font-size: 16px;">
          Cancellation of orders may be canceled before they are processed and shipped. If you wish to cancel your order,
          please contact our customer support as soon as possible.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">11. Changes to Payment Policy </p>
        <p style="margin-top: 5px; font-size: 16px;">
          Changes to Payment Policy. We reserve the right to modify or update this Payment Policy at any time. Any changes
          to the Payment Policy will be effective immediately upon posting the updated version on our website.
        </p>
      </div>

      <div class="line-content" style="margin-bottom: 50px;">
        <p class="text-content" style="font-weight: bold;">12. Contact</p>
        <p style="margin-top: 5px; font-size: 16px;">
          If you have any questions or concerns about our Payment Policy, please feel free to contact us at
          support@lifeprosper.eu
        </p>
      </div>

    </div>
  </section>

@endsection
