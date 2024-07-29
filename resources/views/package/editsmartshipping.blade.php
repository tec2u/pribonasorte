@extends('layouts.header')
@section('content')
  <style>
    .modal_action {
      background-color: rgba(0, 0, 0, 0.2);
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 10000;
    }

    .action_box {
      width: 700px;
      height: 500px;
      border-radius: 10px;
      background: #ffffff;
      margin: 5% auto;
      padding: 30px;
      /* margin-top: 50%; */
    }

    p.text-smart {
      font-weight: bold;
      font-size: 20px;
      margin-top: 20px;
      line-height: 20px;
    }

    ul.list_button {
      margin-top: 2px;
      margin-left: -10px;
    }

    ul.list_button li {
      list-style: none;
      display: inline-block;
      margin: 0px 10px;
    }

    .header_box {
      width: 100%;
      display: inline-block;
    }

    .block1 {
      width: 50%;
      float: left;
      display: inline-block;
    }

    .body_box {
      width: 100%;
      display: inline-block;
    }

    .col-1 {
      width: 30%;
      float: left;
      display: inline-block;
    }

    .col-2 {
      width: 70%;
      float: left;
      display: inline-block;
    }
  </style>

  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>EDIT SMARTSHIPPING</h1>
              <div class="card shadow my-3">
                @if (session('success'))
                  <div class="alert alert-success">
                    {{ session('success') }}
                  </div>
                @endif
                @php
                  session()->forget('success');
                @endphp

                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th></th>
                        <th><b>ID</b></th>
                        <th><b>Product</b></th>
                        <th><b>Price</b></th>
                        <th><b>Amount</b></th>
                        <th><b>VAT</b></th>
                        <th><b>Subtotal</b></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      <form action="{{ route('packages.ecommOrdersDetailEditPost') }}" method="POST" id="formEditSmart">
                        @csrf
                        <input type="hidden" name="order" value="{{ $order }}">
                        @foreach ($products as $product)
                          @php
                            $id_img = $product->id;
                            $pro_img = Illuminate\Support\Facades\DB::select(
                                "SELECT * FROM products WHERE id = '$id_img'",
                            );

                          @endphp

                          <tr>
                            <th><img style="width: 60px; height: 60px;" src="/img/products/{{ $pro_img[0]->img_1 }}"
                                alt=""></th>
                            <th>{{ $product->id }}</th>
                            <th>{{ $product->name }}</th>
                            <th>{{ number_format($product->backoffice_price, 2, ',', '.') }}</th>
                            <th>
                              <input class="form-control" type="hidden" name="product_{{ $product->id }}"
                                value="{{ $product->id }}">
                              <input class="form-control" type="hidden" name="amount_name"
                                value="amount_{{ $product->id }}">
                              <input class="form-control" type="hidden" name="price_{{ $product->id }}"
                                value="{{ $product->backoffice_price }}">
                              @if ($product->kit == 1)
                                <input class="form-control" type="number"
                                  style="width: 80px; font-size: 12px; height: 30px; float: left;" min="0"
                                  max="1" name="amount_{{ $product->id }}"
                                  @if (isset($array_impts)) value="{{ $array_impts[$product->id]['amount'] ?? '' }}"
                                  @else
                                  value="{{ $ids[$product->id] ?? '' }}" @endif>
                              @else
                                <input class="form-control" type="number"
                                  style="width: 80px; font-size: 12px; height: 30px; float: left;" min="0"
                                  max="{{ $product->Stock($product) }}" name="amount_{{ $product->id }}"
                                  @if (isset($array_impts)) value="{{ $array_impts[$product->id]['amount'] ?? '' }}"
                                  @else
                                  value="{{ $ids[$product->id] ?? '' }}" @endif>
                              @endif
                            </th>

                            <th>
                              @if (isset($array_impts) and !empty($array_impts))
                                @foreach ($array_impts as $arr)
                                  @if ($arr['product'] == $product->id)
                                    {{ number_format($arr['vat'], 2, ',', '.') }}
                                  @endif
                                @endforeach
                              @endif
                            </th>

                            <th>
                              @if (isset($array_impts) and !empty($array_impts))
                                @foreach ($array_impts as $arr)
                                  @if ($arr['product'] == $product->id)
                                    {{ number_format($arr['subtotal'], 2, ',', '.') }}
                                  @endif
                                @endforeach
                              @endif
                            </th>

                            <th><a style="font-weight: bold; color: green;"
                                href="{{ route('reports.smartshippingOrderDetail', ['id' => $product->id]) }}">Detail</button>
                            </th>
                          </tr>
                        @endforeach
                    </tbody>
                  </table>

                  <button
                    style="padding: 0px 40px; background: green; color: #ffffff; height: 40px; margin: 10px 0px 10px 20px;">Calculate
                    orders</button>

                  </form>

                  @if (isset($frete) && isset($array_impts) and !empty($array_impts))
                    <button onclick="confirmEdit()"
                      style="padding: 0px 40px; background: #480D54; color: #ffffff; height: 40px; margin: 10px 0px 10px 20px;">Save
                      orders</button>
                  @endif

                  @if (isset($total) and !empty($total))
                    <input class="form-control" id="total" readonly
                      style="width: 100px; height: 30px; float: right; margin: 10px 20px 0px 0px;" type="text"
                      value="{{ number_format($total, 2, ',', '.') }}" name="total">
                  @else
                    <input class="form-control" id="total" readonly
                      style="width: 100px; height: 30px; float: right; margin: 10px 20px 0px 0px;" type="text"
                      value="" name="total">
                  @endif


                  <h4 style="float: right; margin: 10px 20px 0px 0px;">Total:</h4>

                  @if (isset($frete))
                    <input class="form-control" id="total" readonly
                      style="width: 100px; height: 30px; float: right; margin: 10px 20px 0px 0px;" type="text"
                      value="{{ $frete ?? 0 }}" name="total">

                    <h6 style="float: right; margin: 10px 20px 0px 0px;">Shipping:</h4>
                  @endif
                </div>
                <div class="card-footer clearfix py-3">
                  <ul class="pagination pagination-sm m-0 float-right">
                    {{-- {{$newrecruits->links()}} --}}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    function confirmEdit() {
      // let confirmEdit = document.getElementById('confirmEdit').value = 1;
      let formEditSmart = document.getElementById('formEditSmart');

      formEditSmart.action =
        '{{ route('packages.ecommOrdersDetailEditPostSave') }}';

      formEditSmart.submit();
    }
  </script>
@endsection
