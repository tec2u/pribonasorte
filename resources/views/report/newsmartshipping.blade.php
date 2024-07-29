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
              <h1>NEW SMARTSHIPPING</h1>
              <div class="card shadow my-3">

                {{-- @php
                                dd(session('total'));
                                exit();
                            @endphp --}}

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
                      <form action="{{ route('reports.smartshippingSum') }}" method="POST">
                        @csrf
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
                                  @if (isset($array_impts) and !empty($array_impts)) @foreach ($array_impts as $arr1)
                                                    @if ($arr1['product'] == $product->id)
                                                    value="1" @endif
                                  @endforeach
                                >
                              @endif
                            @else
                              <input class="form-control" type="number"
                                style="width: 80px; font-size: 12px; height: 30px; float: left;" min="0"
                                max="{{ $product->Stock($product) }}" name="amount_{{ $product->id }}"
                                @if (isset($array_impts) and !empty($array_impts)) @foreach ($array_impts as $arr1)
                                                @if ($arr1['product'] == $product->id)
                                                value="{{ $arr1['amount'] }}" @endif
                                @endforeach
                              >
                        @endif
                        @endif
                        </th>

                        {{-- @if (isset($tax_vat) and !empty($tax_vat))
                                        <th>{{ number_format($tax_vat[0]->value,2,",",".") }}</th>
                                        @else
                                        <th>0,00</th>
                                        @endif --}}

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
                    style="padding: 0px 40px; background: green; color: #ffffff; height: 40px; margin: 10px 0px 10px 20px;">Add
                    orders</button>
                  </form>

                  @if (!empty(session('new_order')) and isset($total))
                    <a href="{{ route('reports.smartshippingOrder') }}"><button
                        style="padding: 0px 40px; background: orange; color: #ffffff; height: 40px; margin: 10px 0px 10px 20px;">Finish
                        orders</button></a>

                    <a href="{{ route('reports.newsmartshipping') }}"><button
                        style="padding: 0px 40px; background: silver; color: #212121; height: 40px; margin: 10px 0px 10px 20px;">Clear
                        orders</button></a>
                  @endif

                  @if (isset($total) and !empty($total))
                    <input class="form-control" id="total"
                      style="width: 100px; height: 30px; float: right; margin: 10px 20px 0px 0px;" type="text"
                      value="{{ number_format($total, 2, ',', '.') }}" name="total">
                  @else
                    <input class="form-control" id="total"
                      style="width: 100px; height: 30px; float: right; margin: 10px 20px 0px 0px;" type="text"
                      value="" name="total">
                  @endif

                  <h4 style="float: right; margin: 10px 20px 0px 0px;">Total:</h4>
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

  @if (isset($product_box) and !empty($product_box))
    <div class="modal_action">
      <div class="action_box">
        <div class="header_box">
          <div style="width: 100%; display: inline-block;">
            <div class="block1">
              <p style="font-weight: bold; font-size: 20px;">Detail Product</p>
            </div>
            <div class="block1"><a href="javascript:history.back()">
                <p style="float: right; font-size: 15px;">✕</p>
              </a></div>
          </div>

          <table style="width: 100%;">
            <tr>
              <td style="width: 40%;">
                <img src="/img/products/{{ $product_box->img_1 }}" style="width: 200px;">
              </td>
              <td style="width: 60%;">
                <div class="row g-3 px-2">
                  <div class="col-md-12">
                    <label for="inputname" class="form-label">Name</label>
                    <input type="text" class="form-control" disabled value="{{ $product_box->name }}">
                  </div>
                  <div class="col-md-12">
                    <label for="inputname" class="form-label">Price</label>
                    <input type="text" class="form-control" disabled value="{{ $product_box->price }}">
                  </div>
                </div>
              </td>
            </tr>
          </table>
          <table style="width: 100%;">
            <tr>
              <td style="width: 100%;">
                <div style="width: 100%; height: 150px; margin-top: 25px; overflow: scroll;">
                  {!! $product_box->description !!}
                </div>
              </td>
            </tr>
          </table>

        </div>
      </div>
    </div>
  @endif
@endsection
