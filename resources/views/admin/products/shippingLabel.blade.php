@extends('adminlte::page')

@section('title', 'Package Orders')

@section('content_header')
  <h4>Orders Product</h4>
@stop

@section('content')
  @include('flash::message')
  <div class="card">
    <div class="card-header d-flex" style="justify-content: space-between">
      <div class="alignPackage">
        <h3>Shipping Product</h3>
      </div>
      @if (isset($token) && isset($urlPPL) && !$exists)
        <button type="button" class="btn btn-primary btn-sm m-0" disabled id="btn-loading">
          Loading...
        </button>

        <form action="{{ route('admin.packages.order_products_label.pdf') }}" id="form-label" method="POST"
          style="display: none">
          @csrf

          <input type="hidden" name="token" value="{{ $token }}" id="">
          <input type="hidden" name="urlPPL" value="{{ $urlPPL }}" id="">
          <input type="hidden" name="order" value="{{ $data['order'] }}" id="">
          <button type="submit" class="btn btn-success btn-sm m-0">
            shipping label pdf
          </button>
        </form>
      @elseif($exists && !isset($token) && !isset($urlPPL))
        <button type="button" class="btn btn-primary btn-sm m-0" disabled id="btn-loading">
          Loading...
        </button>

        <form action="{{ route('admin.packages.order_products_label.pdf') }}" id="form-label" method="POST"
          style="display: none">
          @csrf

          <input type="hidden" name="order" value="{{ $data['order'] }}" id="">
          <input type="hidden" name="exists" value="{{ $exists }}" id="">
          <button type="submit" class="btn btn-success btn-sm m-0">
            shipping label pdf
          </button>
        </form>
      @else
        <button type="submit" class="btn btn-danger btn-sm m-0" disabled>
          Failed in generate label
        </button>
        <a href="{{ route('admin.users.edit', $data['id_user']) }}">
          <button type="submit" class="btn btn-warning btn-sm m-0">
            Edit address
          </button>
        </a>
      @endif

      <form action="{{ route('admin.packages.change_status') }}" id="form-label" method="POST"
        style="display: flex;gap:.5rem">
        @csrf

        <input type="hidden" name="order" value="{{ $data['order'] }}" id="">

        <select class="form-select" aria-label="Default select example" name="status_order" required>
          <option selected value="">status</option>
          <option value="order placed">order placed</option>
          <option value="order sent">order sent</option>
        </select>

        <button type="submit" class="btn btn-primary btn-sm m-0">
          Set Status
        </button>
      </form>
    </div>



    <div class="card-body table-responsive">
      <span class="counter float-right"></span>
      <div class="">
        <p>
        <h5>Client Information</h5>
        </p>

        <p>
          <td>Name: {{ $data['client_name'] }}</td>
        </p>
        <p>
          <td>Email: {{ $data['client_email'] }}</td>
        </p>


        <p>
        <h4>Address</h4>
        </p>


        @if ($data['mt_shipp'] != 'pickup')
          <p>
            <td>{{ $data['address'] }}, {{ $data['number'] }} {{ $data['state'] ?? '' }} - {{ $data['complement'] }}
              {{ $data['neighborhood'] }}
            </td>
          </p>

          <p>
            <td>Zip: {{ $data['zip'] }}, country: {{ $data['country'] }}</td>
          </p>
        @endif
        <p>
          <td>Method: {{ $data['method_shipping'] }}</td>
        </p>

        <p>
        <h4>Products</h4>

        @foreach ($data['products'] as $item)
          <p>
            <span>{{ $item['amount'] }}X {{ $item['name'] }} -> Unit: €{{ $item['unit'] }}</span>

            <span style="margin-left: 1rem">-> Total: €{{ number_format($item['total'], 2, '.', ',') }}</span>
          </p>
        @endforeach

        <p>

          <td>Shipping: €{{ number_format($data['total_shipping'], 2, '.', ',') }}</td>
        </p>


        <p>

          <td>Vat: €{{ number_format($data['total_vat'], 2, '.', ',') }}</td>
        </p>


        <p>

          <td>QV: {{ number_format($data['qv'], 2, '.', ',') }}</td>
        </p>


        <p>
          <td>Paid: {{ date('m/d/Y', strtotime($data['paid_data'])) }} </td>

          <td>Total: €{{ $data['total_order'] }}</td>
        </p>

      </div>
    </div>
  @stop
  @section('css')
    <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
  @stop
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  @section('js')
    <script>
      $(document).ready(function() {
        $(".search").keyup(function() {
          var searchTerm = $(".search").val();
          var listItem = $('.results tbody').children('tr');
          var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

          $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array) {
              return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "")
                .toLowerCase()) >= 0;
            }
          });

          $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e) {
            $(this).attr('visible', 'false');
          });

          $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e) {
            $(this).attr('visible', 'true');
          });

          var jobCount = $('.results tbody tr[visible="true"]').length;
          $('.counter').text(jobCount + ' item');

          if (jobCount == '0') {
            $('.no-result').show();
          } else {
            $('.no-result').hide();
          }
        });
      });


      setTimeout(() => {
        document.getElementById('form-label').style.display = 'initial';
        document.getElementById('btn-loading').style.display = 'none';
      }, 5000);
    </script>
  @stop
