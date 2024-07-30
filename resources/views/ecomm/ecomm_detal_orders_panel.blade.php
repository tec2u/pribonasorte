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
      background: #d26075;
      border: 0px;
      transition: 500ms;
    }

    .button-detal:hover {
      padding: 0px 30px;
      height: 40px;
      border-radius: 5px;
      color: #ffffff;
      background: #d26075;
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
        <p class="title-ecomm">Olá {{ $user->name }}!</p>
      </div>

      <div class="raw">
        <div class="box-panel">
          @include('ecomm.layouts.ecomm_navbar')

          <div class="raw">
            <div class="band-title">
              <center>
                <p class="title-order-panel">Seu pedido foi feito com sucesso!</p>
                <p class="title-number-panel">Seu pedido</p>
                <p class="number-order">{{ $order_number }}</p>
                <p class="title-number-panel">Total: €{{ $order[0]->total_price }}</p>
              </center>
              <div>
                <p class="title-number-panel">Pagamento: <strong style="font-size: 1rem"> {{ $order[0]->payment }}
                  </strong>
                </p>
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
                        class="button-detal" id="bt_submit">Recibo</a>
                    </div>
                  @endif
                  <div>
                    <a type="button" class="btn button button-detal"
                      style="width: auto; padding: 0 2rem; opacity: 1; display:flex; justify-content:center; align-items:center; width: 2rem;"
                      target="_blank" href="{{ route('tracking') . "?order=$order_number" }}">
                      Rastrear
                    </a>
                  </div>
                  <div style="display: flex;gap:2rem;">
                    @if (strtolower($order[0]->smartshipping) == 1)
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaaaa">Cancelar smartshipping</button>

                      <button type="button" class="btn button-detal" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaChoose">Escolher dia do pagamento</button>

                      <span>Melhor dia do pagamento {{ $day }}</span>
                    @endif
                  </div>
                </div>
              @endif
            </div>
          </div>

          <div class="modal fade" id="exampleModalaaaa" tabindex="-1" aria-labelledby="exampleModalaaaaLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalaaaaLabel">cancelar Smartshipping -
                    {{ $order[0]->number_order }}</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('cancel.smartshipping') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Pedido</span>
                      <input name="order" type="text" class="form-control"
                        placeholder=" {{ $order[0]->number_order }}" aria-label="Username" aria-describedby="basic-addon1"
                        value=" {{ $order[0]->number_order }}" readonly>
                    </div>

                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Motivo</span>
                      <textarea name="motivo" id="" cols="60" rows="10" required style="padding:1rem"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
              </div>
            </div>
          </div>

          <div class="modal fade" id="exampleModalaChoose" tabindex="-1" aria-labelledby="exampleModalaChooseLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
              <div class="modal-content">
                <div class="modal-header">
                  <h1 class="modal-title fs-5" id="exampleModalaChooseLabel">cancelar Smartshipping -
                    {{ $order[0]->number_order }}</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form action="{{ route('choose.day.smartshipping') }}" method="POST">
                    @csrf
                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Pedido</span>
                      <input name="order" type="text" class="form-control"
                        placeholder=" {{ $order[0]->number_order }}" aria-label="Username"
                        aria-describedby="basic-addon1" value=" {{ $order[0]->number_order }}" readonly>
                    </div>

                    <div class="input-group mb-3">
                      <span class="input-group-text" id="basic-addon1">Dia</span>
                      <input type="number" id="day" name="day" min="1" max="30"
                        placeholder="Day" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                  </form>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
              </div>
            </div>
          </div>

          <div class="raw" style="padding: 30px;">
            <table class="table" style="overflow-x: scroll">
              <thead>
                <tr>
                  <th scope="col">Produto</th>
                  <th scope="col">QV</th>
                  <th scope="col">Quantidade</th>
                  <th scope="col">Pagamento</th>
                  <th scope="col">Recibo</th>
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
                        <p>{{ $item->amount }} Unidades</p>
                      </td>
                      <td>
                        {{ $item->payment }}
                      </td>
                      <td>
                        @if (isset($invoiceFak))
                          Sim
                        @else
                          Não
                        @endif
                      </td>
                      @if (isset($invoiceFak))
                        <td> <a type="button" target="_blank" href="{{ route('invoicePDF', $order_number) }}"
                            style="width: auto; padding: 0 2rem; opacity: 1; display:flex; justify-content:center; align-items:center; width: fit-content;"
                            class="button-detal" id="bt_submit">Recibo</a></td>
                    </tr>
                  @endif
                @endforeach
                @endif
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.7.0.min.js"
    integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <script src="/js/script.js"></script>
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

@endsection
