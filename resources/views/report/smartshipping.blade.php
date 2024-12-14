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
      width: 500px;
      height: 200px;
      border-radius: 20px;
      background: #ffffff;
      margin: 20% auto;
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
  </style>

  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>SMARTSHIPPING</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">Pedidos smartishipping</h3>
                </div>
                <div class="card-header py-3">

                  <div class="input-group input-group-sm my-1" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                      placeholder="@lang('withdraw.search')">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="bi bi-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th><b>ID Pedido</b></th>
                      <th><b>Total</b></th>
                      <th><b>Total QV</b></th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($smarts as $recruits)
                      <tr>
                        <th>{{ $recruits['order'] }}</th>
                        <th>€ {{ number_format($recruits['total'], 2, ',', '.') }}</th>
                        <th>€ {{ number_format($recruits['qv'], 2, ',', '.') }}</th>
                        <th><a href="{{ route('packages.ecommOrdersDetail', ['id' => $recruits['order']]) }}"><button
                              type="button" style="float: right;" class="btn btn-primary">Detalhe</button></a></th>
                        <th><a
                            href="{{ route('reports.smartshipping.check.cancel', ['id' => $recruits['order']]) }}"><button
                              type="button" style="float: right;" class="btn btn-primary">Cancelar</button></a></th>
                      </tr>
                    @empty
                      <p class="m-4 fst-italic">@lang('network.no_record_smart')</p>
                    @endforelse
                  </tbody>
                </table>
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

  @if (isset($action_box) and !empty($action_box))
    <div class="modal_action">
      <div class="action_box">
        <center>
          <p class="text-smart">Tem certeza de que deseja remover seu smartshipping?</p>
          <br>
          <ul class="list_button">
            <li><a href="{{ route('reports.smartshipping_report') }}"><button type="button"
                  class="btn btn-primary">Voltar</button></a></li>
            <li><a href="{{ route('reports.smartshipping.cancel', ['id' => $action_box]) }}"><button type="button"
                  class="btn btn-primary">Sim, cancelar</button></a></li>
          </ul>
        </center>
      </div>
    </div>
  @endif
@endsection
