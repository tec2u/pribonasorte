<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Invoice Pribonasorte</title>

  <style>
    * {
      box-sizing: border-box;
      padding: 0;
      margin: 0;
    }

    #download-pdf {
      margin-bottom: 5rem;
    }

    #container-invoice {
      display: flex;
      align-items: center;
      flex-direction: column
    }



    .invoice-box-main {
      max-width: 1000px;
      width: 90vw;
      border: 1px solid #27032F;
    }

    .titulo-topo {
      display: flex;
      justify-content: space-between;
      width: 90vw;
      max-width: 1000px;
    }

    .titulo-topo h1,
    .titulo-topo h2 {
      font-size: .75rem !important;
    }

    .section-1 {
      width: 100%;
      display: flex;
    }

    .left1,
    .right1 {
      display: flex;
      width: 50%;
      flex-direction: column;
    }

    .right1 {
      flex-direction: column-reverse;
      border-left: 1px solid #27032F;
    }

    .left1 {
      border-right: 1px solid #27032F;
    }

    .left1 .grande,
    .right1 .grande {
      width: 100%;
      height: 100%;
      /* background: silver; */
      border-top: 1px solid #27032F;
      border-bottom: 1px solid #27032F;
      padding: .5rem;
      display: flex;
      flex-direction: column;
      gap: 2rem;
    }

    .left1 .pequeno,
    .right1 .pequeno {
      width: 100%;
      height: fit-content;
      /* background: rgb(241, 241, 241); */
      padding: .5rem;
    }

    .section-2 {
      display: flex;
    }

    .left2,
    .right2 {
      display: flex;
      width: 50%;
      height: fit-content;
      padding: .5rem;
      flex-direction: column;
    }

    .left2 {
      border-right: 1px solid #27032F;
      display: flex;
      flex-direction: column;
    }

    .border {
      border-top: 1px solid #27032F;
      border-bottom: 1px solid #27032F;
    }

    .section-3 {
      width: 100%;
    }

    .section-3 .top {
      padding: .5rem;
      width: 100%;
      display: flex;
      justify-content: space-between;
      font-weight: 600;
      font-size: .8rem;
    }

    .section-3 .middle {
      padding: .5rem;
      width: 100%;
      display: flex;
      justify-content: space-between;
      font-size: .8rem;
    }

    .section-3 .top span,
    .section-3 .middle span {
      width: 14%;
    }

    .section-3 .middle span {}

    .section-3 .bottom {
      padding: .5rem;
      width: 100%;
      display: flex;
      flex-direction: column;
      gap: .5rem;
    }

    .section-3 .bottom>div {
      width: 100%;
      display: flex;
      justify-content: space-between;
    }

    .section-4 {
      width: 100%;
      padding: .5rem;
      height: 50px;
    }
  </style>
</head>

@php
  $dataObjeto = new DateTime($data['paid_data']);
  $dataIdOrder = $dataObjeto->format('Ymd');

  $dataFormatada = $dataObjeto->format('d/n/Y');

  $maiorTaxa = 0;
@endphp

<body style="padding-bottom: 5rem">
  <button id="download-pdf"
    style="background-color: #27032F;padding:1rem;font-size:1.5rem;color:white;cursor:pointer">Download
    PDF</button>
  <div id="container-invoice">
    <div class="titulo-topo">
      <h1>INTERMODELS s.r.o.</h1>
      <h2>INVOICE - TAX DOCUMENT N0.{{ $dataIdOrder }}{{ $data['order'] }}</h2>
    </div>
    <div class="invoice-box-main">
      <div class="section-1">
        <div class="left1">
          <div class="grande">
            <div>
              <span>Supplier</span>
              <p style="font-weight: bold; margin-top:0.5rem">
                INTERMODELS s.r.o. <br>
                Pod Nouzovem 971/17 <br>
                197 00 Praha
              </p>
            </div>
            <div>
              <p style="color:blue">
                Identif. number: 26126893 <br>
                Tax identity: CZ26126893
              </p>
            </div>
          </div>
        </div>
        <div class="right1">
          <div class="grande">
            <div style="display: flex; justify-content: space-between;">
              <p style="color:blue; width:25%;">
                Customer:
              </p>
              <div style="width: ">
                <p>
                  Member ID: {{ $data['client_id'] }}
                </p>
                @if (isset($data['client_corporate']))
                  <p>
                    Tax identity: {{ $data['client_corporate'] }}
                  </p>
                @endif
              </div>
            </div>
            <div>
              <p style="margin-left: 1rem;">
                Name: {{ $data['client_name'] }} <br>
                Address: {{ $data['client_address'] ?? '' }} <br>
                Postcode: {{ $data['client_postcode'] ?? '' }} <br>
                Country: {{ $data['client_country'] ?? '' }} <br>
                @if (isset($data['client_cell']))
                  Phone: {{ $data['client_cell'] }} <br>
                @endif
                Email: {{ $data['client_email'] }}
              </p>
            </div>
          </div>
          <div class="pequeno">
            <div style="display: flex; justify-content: space-between;">
              <p>Order N0: {{ $data['order'] }}</p>
              <p>Date: {{ $dataFormatada }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="section-2">
        <div class="left2">
          <div style="width: 100%; display:flex; justify-content: space-between">
            Invoice date:
            <span style="border: 1px solid black;padding:.1rem;">{{ $dataFormatada }}</span>
          </div>
          <div style="width: 100%; display:flex; justify-content: space-between">
            Due date:
            <span style="border: 1px solid black;padding:.1rem;">{{ $dataFormatada }}</span>
          </div>
          <div style="width: 100%; display:flex; justify-content: space-between; color:blue;">
            Date of a taxable supply:
            <span style="border: 1px solid black;padding:.1rem;">{{ $dataFormatada }}</span>
          </div>
          <div style="width: 100%; display:flex; justify-content: space-between;">
            Payment:
            <span>paid</span>
          </div>
        </div>
        <div class="right2">
          <span style="color: blue; width: 100%">Ship to:</span>
          <div>
            @if ($data['mt_shipp'] == 'pickup')
              <p>
                <br>
                {{ $data['method_shipping'] }}
              </p>
            @else
              <p>
                <br>
                {{ $data['address'] }} - {{ $data['number'] }} <br>
                {{ $data['zip'] }} - {{ $data['country'] }}
              </p>
            @endif
            Method: {{ $data['metodo_pay'] }}
          </div>
        </div>
      </div>
      <div class="section-3">
        <div class="top border">
          <span>Description</span>
          <span>Qty</span>
          <span>Unit price</span>
          <span>Price</span>
          <span>%VAT</span>
          <span>VAT</span>
          <span>Total</span>
        </div>

        @foreach ($data['products'] as $item)
          {{-- product --}}
          <div class="middle">
            <span>{{ $item['name'] }}</span>
            <span>{{ $item['amount'] }}X</span>
            <span>{{ $item['unit'] }}</span>
            <span>{{ $item['unit'] }}</span>
            @php
              $taxaa = $item['porcentVat'];

              if ($taxaa > $maiorTaxa) {
                  $maiorTaxa = $taxaa;
              }

            @endphp
            <span>{{ number_format($item['porcentVat'], 2, ',', '.') }}%</span>
            <span>{{ $item['vat'] }}</span>
            <span>{{ $item['total'] }}</span>
          </div>
          {{--  --}}
        @endforeach
        @php
          if ($maiorTaxa > 0) {
              $vatFrete = $data['freteSemVat'] * ($maiorTaxa / 100);
          } else {
              $vatFrete = 0;
          }
        @endphp

        <div class="middle">
          <span>Shipping</span>
          <span></span>
          <span></span>
          <span>{{ $data['freteSemVat'] }}</span>
          @php
            $taxaa = $item['porcentVat'];

            if ($taxaa > $maiorTaxa) {
                $maiorTaxa = $taxaa;
            }

          @endphp
          <span>{{ number_format($maiorTaxa, 2, ',', '.') }}%</span>
          <span>{{ number_format($vatFrete, 2, ',', '.') }}</span>
          <span>{{ $data['total_shipping'] }}</span>
        </div>

        <div class="bottom border">
          <div>
            <span class="" style="width: 40%">Total Amount</span>
            <span class="" style="width: 25%">{{ $data['total_price_product'] + $data['freteSemVat'] }}</span>
            <span class=""
              style="width: 10%">{{ number_format($data['total_vat'] + $vatFrete, 2, ',', '.') }}</span>
            <span class="" style="width: 15%">{{ $data['total_order'] }}</span>
          </div>

          {{-- <div> --}}
          {{-- <span class="" style="width: 87.5%">Shipping</span> --}}
          {{-- <span class="" style="width: 30%">Currency EUR</span> --}}
          {{-- <span class="" style="width: 15.5%">{{ $data['total_shipping'] }}</span> --}}
          {{-- </div> --}}

          <div style="font-weight: 800">
            <span class="" style="width: 57.5%">Total Due</span>
            <span class="" style="width: 30%">Currency EUR</span>
            <span class="" style="width: 15.5%">{{ $data['total_order'] }}</span>
          </div>
        </div>
      </div>

      <div class="section-4">
        <p>Issuer:</p>
      </div>

      <div class="section-3">
        <div class="top border">
          <span style="width: 42%">Recapitulation in EUR</span>
          {{-- <span></span> --}}
          {{-- <span></span> --}}
          <span>Tax base in EUR</span>
          <span>%VAT</span>
          <span>VAT in EUR</span>
          <span>Total in EUR</span>
        </div>

        @foreach ($data['products'] as $item)
          {{-- product --}}
          <div class="middle">
            <span></span>
            <span></span>
            <span></span>
            <span>{{ $item['unit'] }}</span>
            @php
              $taxaa = $item['porcentVat'];

              if ($taxaa > $maiorTaxa) {
                  $maiorTaxa = $taxaa;
              }

            @endphp
            <span>{{ number_format($item['porcentVat'], 2, ',', '.') }}%</span>
            <span>{{ $item['vat'] }}</span>
            <span>{{ $item['total'] }}</span>
          </div>
          {{--  --}}
        @endforeach
        @php
          if ($maiorTaxa > 0) {
              $vatFrete = $data['freteSemVat'] * ($maiorTaxa / 100);
          } else {
              $vatFrete = 0;
          }
        @endphp

        <div class="middle">
          <span></span>
          <span></span>
          <span></span>
          <span>{{ $data['freteSemVat'] }}</span>
          @php
            $taxaa = $item['porcentVat'];

            if ($taxaa > $maiorTaxa) {
                $maiorTaxa = $taxaa;
            }

          @endphp
          <span>{{ number_format($maiorTaxa, 2, ',', '.') }}%</span>
          <span>{{ number_format($vatFrete, 2, ',', '.') }}</span>
          <span>{{ $data['total_shipping'] }}</span>
        </div>
      </div>
    </div>
  </div>
  </div>

  <script>
    document.getElementById('download-pdf').addEventListener('click', function() {
      document.getElementById('download-pdf').style.display = 'none';
      window.print();
      document.getElementById('download-pdf').style.display = 'initial';
    });
  </script>
</body>

</html>
