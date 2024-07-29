<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8" />
  <title>Report Lifeprosper</title>

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
      flex-direction: column;
      width: 80vw;
      padding: 2rem;
    }

    /* table, */
    th,
    td {
      border: 1px solid black;
      margin: auto;
      padding: .2rem;
      margin: 0;
      gap: 0;
    }

    tr {
      border-bottom: 1px solid black;

    }

    th {
      border-bottom: none;
    }

    table {
      margin-left: 5rem;
    }
  </style>
</head>

<body style="padding-bottom: 5rem">
  <button id="download-pdf"
    style="background-color: #27032F;padding:1rem;font-size:1.5rem;color:white;cursor:pointer">Download
    PDF</button>
  <div id="container-invoice">
    <h2 style="width: 100%;text-align:left">Information about orders {{ $moviment->filtro }}</h2>
    <table style="width:100%; ">
      <tr>
        <th>Day</th>
        <th>Products</th>
        <th>Name</th>
        <th>Last name</th>
        <th>Country</th>
        <th>Order ID</th>
        <th>Amount</th>
      </tr>
      @if (count($moviment) > 0)
        @foreach ($moviment as $item)
          <tr>
            <td>{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->client_name }}</td>
            <td>{{ $item->client_last_name }}</td>
            <td>{{ $item->client_country }}</td>
            <td>{{ $item->number_order }}</td>
            <td>{{ $item->amount }}</td>
          </tr>
        @endforeach
      @endif
    </table>

    <br>
    <br>
    <br>
    <br>

    <h2 style="width: 100%;text-align:left">Sent Stock summary for {{ $moviment->filtro }}</h2>
    <table style="width:100%; ">
      <tr>
        <th>Products</th>
        <th>Shipped</th>
        <th>In Stock</th>
        <th style="width: 5rem;">Sign</th>
      </tr>
      @foreach ($sent['stock'] as $index => $item)
        @if ($index != 'kit Product')
          <tr>
            <td>{{ $index }}</td>
            <td>{{ $sent['sent'][$index] ?? 0 }}</td>
            <td>{{ $item }}</td>
            <td style="width: 5rem;">____________________________</td>
          </tr>
        @endif
      @endforeach
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
