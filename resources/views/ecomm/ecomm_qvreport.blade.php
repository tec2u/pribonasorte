@extends('layouts.header_newsite')
@section('title', 'Pribonasorte')
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

    .text-list-product {
      font-size: 15px;
      font-weight: bold;
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

          <div class="band-title" style="overflow-x: scroll">
            @if (count($order) < 1)
              <center>
                <p class="title-order-panel">Você não tem nenhum pedido!</p>
                <br /><br />
                <a type="button" href="{{ route('ecomm') }}"
                  style="display:flex; justify-content:center; align-items:center;padding: 0 2rem; opacity: 1; max-width: 10vw;"
                  class="button-detal" id="bt_submit">Comprar</a>
              </center>
            @else
              <table class="table" style="overflow-x: scroll">
                <thead>
                  <tr>
                    <th scope="col">Pedido</th>
                    <th scope="col">Data</th>
                    <th scope="col">Pagamento</th>
                    <th scope="col">Preço total</th>
                    <th scope="col">QV</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody style="overflow-x: scroll">
                  @foreach ($order as $item)
                    <tr style="overflow-x: scroll">
                      <td scope="col">{{ $item->number_order }}</td>
                      <td scope="col">{{ \Carbon\Carbon::parse($item->updated_at)->format('m/d/Y h:i A') }}</td>
                      <td scope="col">{{ $item->payment }}</td>
                      <td scope="col">R${{ $item->total }}</td>
                      <td scope="col">{{ $item->qvtt }}</td>
                      <td scope="col"><a href="{{ route('orders_detal.panel.ecomm', ['id' => $item->id]) }}"><button
                            class="button-detal">Detalhe</button></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
              {{ $order->links() }}
            @endif

            <div class="modal fade" id="exampleModalaaaa" tabindex="-1" aria-labelledby="exampleModalaaaaLabel"
              aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalaaaaLabel">cancelar Smartshipping -
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form action="{{ route('cancel.smartshipping') }}" method="POST">
                      @csrf
                      <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Pedido</span>
                        <input name="order" type="text" class="form-control" aria-describedby="basic-addon1"
                          id="inputmodalorder" value="" readonly>
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
          </div>
        </div>
    </section>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="/js/script.js"></script>

  <script>
    $('#exampleModalaaaa').on('show.bs.modal', function(event) {
      var button = $(event.relatedTarget); // Botão que acionou o modal
      var order = button.data('order'); // Extrai informação do data-* atributo
      // Atualiza o conteúdo do modal.
      // console.log(order);
      var modal = $(this);
      modal.find('#exampleModalaaaaLabel').text('Cancelar smartshipping - ' + order);
      modal.find('#inputmodalorder').val(order);
    });
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
