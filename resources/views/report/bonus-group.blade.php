@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    @include('flash::message')
    <section id="transaction" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>Bonus List</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">All Bonus List</h3>
                </div>
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 mb-2">
                            <form class="row g-3" method="GET" action="{{ route('reports.bonus_group') }}">
                                @csrf
                                <div class="col-12 d-flex">
                                    <div class="col-5 d-flex">
                                        <label style="margin-top: 10px;white-space:nowrap;">@lang('admin.btn.firstdate'): </label>
                                        <input type="date" class="form-control" name="fdate" value="{{isset($data_ini) ? $data_ini : ''}}">
                                    </div>
                                    <div class="col-5 d-flex">
                                        <label style="margin-top: 10px;white-space:nowrap;">@lang('admin.btn.seconddate'): </label>
                                        <input type="date" class="form-control" name="sdate" value="{{isset($data_fim) ? $data_fim : ''}}">
                                    </div>
                                    <div class="col d-flex align-items-end justify-content-end">
                                        <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info" style="padding: 6px 28px;">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                <table class="table table-hover table-bordered results" id="tabela-career">
                    <thead>
                    <tr>
                        <th>Bonus List</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse($configBonus as $config)
                        <tr>
                        <td>
                        <details class="details_bonus">
                            <summary><strong>{{ $config->description }}:</strong> <span class="italic">total - </span> {{ isset($bonusTotal[$config->id]['total']) ? $bonusTotal[$config->id]['total'] : 0}} R$ | <span class="italic">amount -</span> {{ isset($bonusTotal[$config->id]) ? count($bonusTotal[$config->id]['bonus_list']) : 0}}</summary>
                            <table class="w-100">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Name</th>
                                        <th>Lastname</th>
                                        <th>Price</th>
                                        <th>Level</th>
                                        <th>Created at</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($bonusTotal[$config->id]))
                                        @foreach($bonusTotal[$config->id]['bonus_list'] as $bonus)
                                            <tr>
                                                <td>{{isset($bonus->order_id) ? $bonus->order_id : ''}}</td>
                                                <td>
                                                    @if(isset($bonus->ecommOrder->user->name))
                                                        {{ $bonus->ecommOrder->user->name }}
                                                    @elseif(isset($users_11[$bonus->order_id]) && $bonus->description == 11)
                                                        {{ $users_11[$bonus->order_id][0] }}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($bonus->ecommOrder->user->last_name))
                                                        {{ $bonus->ecommOrder->user->last_name }}
                                                    @elseif(isset($users_11[$bonus->order_id]) && $bonus->description == 11)
                                                        {{ $users_11[$bonus->order_id][1] }}
                                                    @endif
                                                </td>
                                                <td>{{isset($bonus->price) ? $bonus->price.' R$' : ''}}</td>
                                                <td>{{isset($bonus->level_from) ? $bonus->level_from : ''}}</td>
                                                <td>{{isset($bonus->created_at) ? $bonus->created_at : ''}}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </details>
                        </td>
                        </tr>
                        @empty
                        <p class="m-4 fst-italic">You don't have any bonus registered!</p>
                        </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection
