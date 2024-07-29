<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />

  <title>Invoice Lifeprosper</title>

  <style>
    .invoice-box {
      max-width: 800px;
      margin: auto;
      padding: 30px;
      border: 1px solid #eee;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
      font-size: 16px;
      line-height: 24px;
      font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
      color: #555;
    }

    .invoice-box table {
      width: 100%;
      line-height: inherit;
      text-align: left;
    }

    .invoice-box table td {
      padding: 5px;
      vertical-align: top;
    }

    .invoice-box table tr td:nth-child(2) {
      text-align: right;
    }

    .invoice-box table tr.top table td {
      padding-bottom: 20px;
    }

    .invoice-box table tr.top table td.title {
      font-size: 45px;
      line-height: 45px;
      color: #333;
    }

    .invoice-box table tr.information table td {
      padding-bottom: 40px;
    }

    .invoice-box table tr.heading td {
      background: #eee;
      border-bottom: 1px solid #ddd;
      font-weight: bold;
    }

    .invoice-box table tr.details td {
      padding-bottom: 10px;
    }

    .invoice-box table tr.item td {
      border-bottom: 1px solid #eee;
    }

    .invoice-box table tr.item.last td {
      border-bottom: none;
    }

    .invoice-box table tr.total td:nth-child(2) {
      border-top: 2px solid #eee;
      font-weight: bold;
    }

    @media only screen and (max-width: 600px) {
      .invoice-box table tr.top table td {
        width: 100%;
        display: block;
        text-align: center;
      }

      .invoice-box table tr.information table td {
        width: 100%;
        display: block;
        text-align: center;
      }
    }

    /** RTL **/
    .invoice-box.rtl {
      direction: rtl;
      font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }

    .invoice-box.rtl table {
      text-align: right;
    }

    .invoice-box.rtl table tr td:nth-child(2) {
      text-align: left;
    }
  </style>
</head>


<body>
  <button id="download-pdf"
    style="background-color: #27032F;padding:1rem;font-size:1.5rem;color:white;cursor:pointer">Download
    PDF</button>
  <div id="container-invoice">
    <div class="invoice-box">
      <table cellpadding="0" cellspacing="0">
        <tr class="top">
          <td colspan="2">
            <table>
              <tr style="background-color: #27032F">
                <td class="title" style="display: flex;justify-content: space-between;align-items: center">
                  <img src="/img/Logo_AuraWay.png" style="width: 80%; max-width: 300px; " />
                  <span style="color: #fff !important; font-size: 2rem;padding-right: 1rem;">
                    Order #{{ $data['order'] }}
                  </span>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="information">
          <td colspan="2">
            <table>
              <tr>
                <td>
                  LIFEPROSPER <br>
                  INTERMODELS s.r.o. <br>
                  Pod Nouzovem 971/17<br>
                  197 00 Praha,<br>
                  ICO: 26126893<br>
                  DIC (VAT ID): CZ26126893<br>
                  Czech Republic<br>
                  Phone: +420234688024<br>
                  E-mail: support@lifeprosper.eu
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr class="heading">
          <td>Client Information</td>

          <td></td>
        </tr>

        <tr class="item">
          <td>Name: {{ $data['client_name'] }}</td>
        </tr>
        <tr class="item">
          <td>Email: {{ $data['client_email'] }}</td>
        </tr>
        <tr class="item">
          <td>Tel: {{ $data['client_tel'] }}</td>
        </tr>

        <tr class="heading">
          <td>Address</td>
          <td></td>
        </tr>

        @if ($data['mt_shipp'] != 'pickup')
          <tr class="item">
            <td>{{ $data['address'] }}, {{ $data['number'] }} - {{ $data['complement'] }} {{ $data['neighborhood'] }}
            </td>
          </tr>
          <tr class="item">
            <td>{{ $data['zip'] }} {{ $data['country'] }}</td>
          </tr>
        @endif
        <tr class="item">
          <td>Method: {{ $data['method_shipping'] }}</td>
        </tr>


        <br>

        <tr class="heading">
          <td>Products</td>

          <td>Total</td>
        </tr>

        @foreach ($data['products'] as $item)
          <tr class="item">
            <td>{{ $item['amount'] }} X {{ $item['name'] }} </td>

            <td></td>
          </tr>
        @endforeach
        <tr class="item">
          <td></td>

          <td>{{ $data['quant_products_box'] ?? '' }}</td>
        </tr>

      </table>
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
