@extends('layouts.header')
@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    .hiddenRow {
      padding: 0 !important;
    }

    .modal-backdrop,
    /* .fade, */
    .show {
      display: none;
      background-color: transparent;
      z-index: initial;
    }

    #exampleModalaaaa,
    #exampleModalaaaaedit,
    #exampleModalaChoose {
      z-index: 3 !important;
    }
  </style>

  <main id="main" class="main">
    <section id="supporttable" class="content">
      <div class="">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              @php
                if (isset($orderDetails[0]->number_order) && $orderDetails[0]->smartshipping == 1) {
                    $id_p = $orderDetails[0]->id_payment_order;
                    $order = $orderDetails[0]->number_order;
                    $pay = Illuminate\Support\Facades\DB::select(
                        "select updated_at from payments_order_ecomms where id=$id_p",
                    );
                    $dateTime = new DateTime($pay[0]->{'updated_at'});
                    $day = $dateTime->format('d');

                    $escolhido = Illuminate\Support\Facades\DB::select(
                        "select day from bestDatePaymentSmartshipping where number_order=$order",
                    );
                    if (isset($escolhido[0]->{'day'})) {
                        $day = $escolhido[0]->{'day'};
                    }
                }
              @endphp

              <h1>Pedido {{ $orderDetails[0]->number_order }} - {{ $orderDetails[0]->payment }}</h1>
              @if (isset($day))
                <br>
                <h6>Melhor dia de pagamento: {{ $day }}</h6>
              @endif

              {{-- @if (strtolower($orderDetails[0]->payment) === 'expired' || strtolower($orderDetails[0]->payment) === 'cancelled')
                <form action="#" method="post" id="formTryPay">
                  @csrf

                  <input type="text" name="id_payment" value="{{ $orderDetails[0]->id_payment_order }}"
                    style="display: none">

                  <input type="text" name="order" value="{{ $orderDetails[0]->number_order }}" style="display: none">

                  <input type="text" name="redirect_buy" value="admin" style="display: none">

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

              @if (strtolower($orderDetails[0]->payment) === 'paid')
                <div style="display: flex;gap:2rem;justify-content:space-between">
                  <a href="{{ route('invoicePDF', $orderDetails[0]->number_order) }}" target="_blank">
                    <button type="button" class="btn btn-primary">Recibo</button>
                  </a>

                  <div style="display: flex;gap:2rem;">
                    @if (strtolower($orderDetails[0]->smartshipping) == 1)
                      <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaaaa">Cancelar smartshipping</button>

                      <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaChoose">Escolher dia de pagamento</button>
                    @endif
                  </div>

                  @if (strtolower($orderDetails[0]->smartshipping) == 1 && strtolower($orderDetails[0]->payment) === 'paid')
                    <div style="display: flex;gap:2rem;">
                      <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                        data-bs-target="#exampleModalaaaaedit">Editar smartshipping</button>
                    </div>
                  @endif

                </div>
              @endif

              <!-- Modal -->
              <div class="modal fade" id="exampleModalaaaa" tabindex="-1" aria-labelledby="exampleModalaaaaLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalaaaaLabel">cancelar Smartshipping -
                        {{ $orderDetails[0]->number_order }}</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('cancel.smartshipping') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Pedido</span>
                          <input name="order" type="text" class="form-control"
                            placeholder=" {{ $orderDetails[0]->number_order }}" aria-label="Username"
                            aria-describedby="basic-addon1" value=" {{ $orderDetails[0]->number_order }}" readonly>
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

              <div class="modal fade" id="exampleModalaaaaedit" tabindex="-1" aria-labelledby="exampleModalaaaaeditLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalaaaaeditLabel">Editar Smartshipping -
                        {{ $orderDetails[0]->number_order }}</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      {{-- <a href="">Edit products</a> --}}
                      <p style="font-size: 14px;">*A mudança fará efeito na proxima cobrança</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                      <a href="{{ route('packages.ecommOrdersDetailEdit', $orderDetails[0]->number_order) }}">
                        <button type="button" class="btn btn-primary">Editar</button>
                      </a>
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
                        {{ $orderDetails[0]->number_order }}</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <form action="{{ route('choose.day.smartshipping') }}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                          <span class="input-group-text" id="basic-addon1">Motivo</span>
                          <input name="order" type="text" class="form-control"
                            placeholder=" {{ $orderDetails[0]->number_order }}" aria-label="Username"
                            aria-describedby="basic-addon1" value=" {{ $orderDetails[0]->number_order }}" readonly>
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


              @if ($orderDetails->count() > 0)
                <div class="card shadow my-3">
                  <table class="table text-nowrap">
                    <thead>
                      <tr
                        style="display: flex; align-items: center; width: 100%;justify-content: space-around; text-align: center">
                        <th>Imagem</th>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orderDetails as $item)
                        <tr
                          style="display: flex; align-items: center; width: 100%;justify-content: space-around; text-align: center">
                          <th>
                            <img src="/img/products/{{ $item->image_product }}" alt=""
                              style="width: 150px; height: 150px;">
                          </th>
                          <th>{{ $item->name_product }}</th>
                          <th>{{ $item->amount }}</th>
                          <th>
                            <a href="{{ route('packages.detail_products', $item->id_product) }}">
                              <button type="button" class="btn btn-primary">Produto</button>
                            </a>
                          </th>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <p>Não encontrado</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>


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

    $(document).ready(function() {
      $('#day').on('input', function() {
        var val = parseInt($(this).val(), 10);
        if (val < 1 || val > 31 || isNaN(val)) {
          $(this).val('');
        }
      });
    });
  </script>



@endsection
@section('css')
@stop
