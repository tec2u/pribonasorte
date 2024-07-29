@extends('layouts.header_newsite')
@section('title', 'Lifeprosper | Finalize Shop')
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

    .container-ecomm {
      width: 100%;
      padding: 5% 9%;
      background: #f1f1f1;
    }

    .raw {
      width: 100%;
      display: inline-block;
    }

    p.title-ecomm {
      font-size: 35px;
      font-weight: bold;
      color: #212121;
    }

    .box-panel {
      width: 100%;
      padding: 30px;
      background: #ffffff;
      box-shadow: 2px 2px 20px 2px rgba(0, 0, 0, 0.1);
      border-radius: 20px;
    }

    .band-title {
      margin-top: 100px;
    }

    p.title-order-panel {
      font-size: 25px;
      font-weight: bold;
    }

    p.title-number-panel {
      font-size: 20px;
    }

    p.number-order {
      font-size: 40px;
      margin-top: -20px;
      font-weight: bold;
    }

    img.img-order {
      width: 100px;
    }

    .text-list-product {
      font-size: 15px;
      font-weight: bold;
    }

    .button-detal {
      padding: 0px 30px;
      height: 40px;
      border-radius: 5px;
      color: #ffffff;
      background: #27032f;
      border: 0px;
      transition: 500ms;
    }

    .button-detal:hover {
      padding: 0px 30px;
      height: 40px;
      border-radius: 5px;
      color: #ffffff;
      background: #490d55;
      border: 0px;
      transition: 500ms;
    }

    .modal-backdrop {
      display: none !important;
    }

    #navbarNav {
      display: flex;
      justify-content: space-between;
      width: 100% !important;
    }

    #navbarNav ul {
      display: flex;
      flex-wrap: wrap;
      flex-direction: row;
      justify-content: space-between;
      width: 100% !important;
      gap: 1rem;
    }

    #navbarNav li {
      width: fit-content !important;
    }

    @media all and (min-width:2px) and (max-width:820px) {}
  </style>

  <main id="background-primary">
    <section class="container-ecomm" style="margin-top: 50px;">

      <div class="raw">
        <p class="title-ecomm">Hello {{ $user->name }}!</p>
      </div>

      <div class="raw">
        <div class="box-panel">
          @include('ecomm.layouts.ecomm_navbar')

          <div class="raw">
            @if (isset($order))
              <div class="band-title">
                <center>
                  <p class="title-order-panel">Your most recent order!</p>
                  <p class="title-number-panel">Your order number</p>
                  <p class="title-number-panel">Total: €{{ $order[0]->total }}</p>
                  <p class="number-order">{{ $order_number }}</p>

                </center>
                <div>
                  <p class="title-number-panel">Payment: <strong style="font-size: 1rem"> {{ $order[0]->payment }}
                    </strong>
                  </p>
                  {{-- @if (strtolower($order[0]->payment) === 'expired' || strtolower($order[0]->payment) === 'cancelled')
                    <form action="#" method="post" id="formTryPay">
                      @csrf

                      <input type="text" name="id_payment" value="{{ $order[0]->id_payment_order }}"
                        style="display: none">

                      <input type="text" name="redirect_buy" value="ecomm" style="display: none">

                      <input type="text" name="order" value="{{ $order_number }}" style="display: none">

                      <span>Pay with:</span>
                      <div class="d-flex" style="width: 50%">
                        <select name="payment" style="width: 25%; font-size: 1rem" id="selectPay" style="font-size: 1rem"
                          class="form-control form-control-lg @error('payment') is-invalid @enderror" required>
                          <option value=""> Choose</option>
                          @foreach ($metodos as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                          @endforeach
                          <option value="BTC">Bitcoin (BTC) </option>

                        </select>

                        <button type="submit" style="width: auto; padding: 0 2rem; opacity: .5" class="button-detal"
                          id="bt_submit" disabled onclick="submitTryPay()">Try to pay again</button>

                      </div>
                    </form>
                  @endif --}}
                </div>
              </div>
              @php
                if (isset($order_number) && $order[0]->smartshipping == 1) {
                    $id_p = $order[0]->id_payment_order;
                    $pay = Illuminate\Support\Facades\DB::select(
                        "select updated_at from payments_order_ecomms where id=$id_p",
                    );
                    $dateTime = new DateTime($pay[0]->{'updated_at'});
                    $day = $dateTime->format('d');

                    $escolhido = Illuminate\Support\Facades\DB::select(
                        "select day from bestDatePaymentSmartshipping where number_order=$order_number",
                    );
                    if (isset($escolhido[0]->{'day'})) {
                        $day = $escolhido[0]->{'day'};
                    }
                }
              @endphp
              @if (strtolower($order[0]->payment) === 'paid')
                <div style="display: flex;gap:2rem;">
                  @if (isset($invoiceFak))
                    <div>
                      <a type="button" target="_blank" href="{{ route('invoicePDF', $order_number) }}"
                        style="width: auto; padding: 0 2rem; opacity: 1; display:flex; justify-content:center; align-items:center; width: 2rem;"
                        class="button-detal" id="bt_submit">Invoice</a>
                    </div>
                  @endif
                  <div>
                    <a type="button" class="btn button button-detal"
                      style="width: auto; padding: 0 2rem; opacity: 1; display:flex; justify-content:center; align-items:center; width: 2rem;"
                      target="_blank" href="{{ route('tracking') . "?order=$order_number" }}">
                      Track
                    </a>
                  </div>
                  <div style="display: flex;gap:2rem;">
                    @if (strtolower($order[0]->smartshipping) == 1)
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaaaa">Cancel smartshipping</button>

                      <button type="button" class="btn button-detal" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaChoose">Choose day payment</button>

                      <span>Best day for payment {{ $day }}</span>
                    @endif
                  </div>
                </div>
              @endif
            @else
              <div class="band-title">
                <center>
                  <p class="title-order-panel">You haven't placed any orders yet!</p>
                  <br><br>
                  <a type="button" href="{{ route('ecomm') }}"
                    style="display:flex; justify-content:center; align-items:center;padding: 0 2rem; opacity: 1; max-width: 10vw;"
                    class="button-detal" id="bt_submit">Shop Now</a>
                </center>
                <div>
            @endif
          </div>

          @if (isset($order))
            <div class="modal fade" id="exampleModalaaaa" tabindex="-1" aria-labelledby="exampleModalaaaaLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalaaaaLabel">cancel Smartshipping -
                      {{ $order[0]->number_order }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="{{ route('cancel.smartshipping') }}" method="POST">
                      @csrf
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Order</span>
                        <input name="order" type="text" class="form-control"
                          placeholder=" {{ $order[0]->number_order }}" aria-label="Username"
                          aria-describedby="basic-addon1" value=" {{ $order[0]->number_order }}" disabled>
                      </div>

                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Reason</span>
                        <textarea name="motivo" id="" cols="60" rows="10" required style="padding:1rem"></textarea>
                      </div>
                      <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>

            <div class="modal fade" id="exampleModalaChoose" tabindex="-1" aria-labelledby="exampleModalaChooseLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalaChooseLabel">cancel Smartshipping -
                      {{ $order[0]->number_order }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="{{ route('choose.day.smartshipping') }}" method="POST">
                      @csrf
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Order</span>
                        <input name="order" type="text" class="form-control"
                          placeholder=" {{ $order[0]->number_order }}" aria-label="Username"
                          aria-describedby="basic-addon1" value=" {{ $order[0]->number_order }}" readonly>
                      </div>

                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Day</span>
                        <input type="number" id="day" name="day" min="1" max="30"
                          placeholder="Day" required>
                      </div>
                      <button type="submit" class="btn btn-primary">Confirm</button>
                    </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
          @endif



          <div class="raw" style="padding: 30px;overflow-x: scroll">
            <div style="width: 100%;overflow-x: scroll">

              <table class="table" style="overflow-x: scroll">
                <thead>
                  <tr>
                    <th scope="col">Product</th>
                    <th scope="col">QV</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Payment</th>
                    <th scope="col">Invoice</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                  @if (isset($order))
                    @foreach ($order as $item)
                      @php
                        $id_product = $item->id_product;
                        $product = Illuminate\Support\Facades\DB::select(
                            "SELECT * FROM products WHERE id = '$id_product'",
                        );
                      @endphp

                      <tr>
                        <td><img src="/img/products/{{ $product[0]->img_1 }}" class="img-order"></td>
                        <td>
                          <p>{{ $item->qv ? $item->qv : '0' }}</p>
                        </td>
                        <td>
                          <p>{{ $item->amount }} units</p>
                        </td>
                        <td>
                          {{ $item->payment }}
                        </td>
                        <td>
                          @if (isset($invoiceFak))
                            Yes
                          @else
                            No
                          @endif
                        </td>
                        @if (isset($invoiceFak))
                          <td> <a type="button" target="_blank" href="{{ route('invoicePDF', $order_number) }}"
                              style="width: auto; padding: 0 2rem; opacity: 1; display:flex; justify-content:center; align-items:center; width: fit-content;"
                              class="button-detal" id="bt_submit">Invoice order</a></td>
                      </tr>
                    @endif
                  @endforeach
                  @endif
                </tbody>
              </table>

              {{-- @if (isset($order))
                @foreach ($order as $item)
                  @php
                    $id_product = $item->id_product;
                    $product = Illuminate\Support\Facades\DB::select("SELECT * FROM products WHERE id = '$id_product'");
                  @endphp

                  <div
                    style="display: flex; gap:2rem; overflow-x: auto; justify-content: space-between;align-items: center">

                    <div width="40%">
                      <table style="width: 100%;">
                        <div width="20%"><img src="/img/products/{{ $product[0]->img_1 }}" class="img-order"></div>
                        <div width="80%">
                          <p>{{ $product[0]->name }}</p>
                        </div>
                      </table>
                    </div>
                    <div width="10%">
                      <p>{{ $item->qv ? $item->qv : '0' }}</p>
                    </div>
                    <div width="15%">
                      <p>{{ $item->amount }} units</p>
                    </div>
                    <div width="10%">
                      <p>{{ $item->payment }}</p>
                    </div>
                    <div width="10%"><a href="{{ route('detals', ['id' => $item->id_product]) }}"
                        target="_blanck"><button class="button-detal">Detail</button></a></div>

                  </div>
                  <hr>
                @endforeach
              @endif --}}
            </div>

          </div>
        </div>
    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>
  <script>
    let selectPay = document.getElementById("selectPay");
    let bt_submit = document.getElementById("bt_submit");

    selectPay.addEventListener("change", () => {
      if (selectPay.value !== '') {
        bt_submit.disabled = false;
        bt_submit.style.opacity = 1;
      } else {
        bt_submit.style.opacity = .5;
        bt_submit.disabled = true;
      }
    })

    function submitTryPay() {
      if (selectPay.value === 'BTC') {
        let url = "{!! route('finalize.register.order.retry.crypt') !!}"
        document.getElementById('formTryPay').action = url;
      } else {
        let url = "{!! route('finalize.register.order.retry.comgate') !!}"
        document.getElementById('formTryPay').action = url;
      }
    }
  </script>
  <script>
    $(document).ready(function() {
      $("#btn-actv1").click(function(event) {
        event.preventDefault();
        $("#cpf_persona").fadeIn();
        $("#legal_person").hide();
        $("#cnpj_legal").hide();
      });
    });
  </script>

@endsection
