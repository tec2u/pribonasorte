@extends('layouts.header')
@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    .hiddenRow {
      padding: 0 !important;
    }
  </style>

  <main id="main" class="main">
    <section id="supporttable" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <div style="width: 100%; display: inline-block;">
                <div style="width: 50%; display: inline-block; float: left;">
                  <h1>@lang('network.enrolled_customers')</h1>
                </div>
                <div style="width: 50%; display: inline-block; float: left;">
                  <div style="width: 60%; margin-left: 40%; display: inline-block;">
                    <div class="width: 100%; display: inline-block;">
                      <form action="{{ route('networks.IndicationEcommFilter') }}" method="POST">
                        @csrf
                        <input type="date" name="date_in"
                          style="width: 33%; padding: 0px 10px; height: 40px; border-radius: 5px;">
                        <input type="date" name="date_out"
                          style="width: 33%; padding: 0px 10px; height: 40px; border-radius: 5px;">
                        <button type="date"
                          style="width: 30%; height: 40px; background: #d26075; color: #ffffff; border-radius: 5px;">@lang('network.search')</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>

              @if ($indication->count() > 0)
                <div class="card shadow my-3" style="margin-top: 20px; padding: 20px;">
                  <div style="width: 100%; display: inline-block;">
                    <a href="{{ route('networks.IndicationEcommFilterMonth') }}"><button
                        style="padding: 0px 60px; margin-bottom: 20px; height: 40px; background: #d26075; color: #ffffff; border-radius: 5px; float: right;">@lang('network.search_text')</button></a>
                  </div>
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th><b>Id</b></th>
                        <th><b>@lang('network.name')Name</b></th>
                        <th><b>Email</b></th>
                        <th><b>@lang('network.phone')Phone</b></th>
                        <th><b>@lang('network.registered')Registered</b></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($array_user as $item)
                        <tr>
                          <th>{{ $item['id'] }}</th>
                          <th>
                            {{ $item['name'] }}
                            @if (isset($item['last_name']) and !empty($item['last_name']))
                              {{ $item['last_name'] }}
                            @endif
                          </th>
                          <th>{{ $item['email'] }}</th>
                          @if (isset($item['phone']) and !empty($item['phone']))
                            <th>{{ $item['phone'] }}</th>
                          @else
                            <th>-</th>
                          @endif
                          <th>{{ $item['register'] }}</th>
                          <th>
                            <a href="{{ route('networks.IndicationEcommOrders', $item['id']) }}">
                              <button type="button" class="btn btn-primary">@lang('network.ordens')</button>
                            </a>
                          </th>

                        </tr>
                      @endforeach
                    </tbody>
                  </table>

                </div>
              @else
                <p>@lang('network.yout_dont')</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  </BR></BR></BR></BR></BR></BR></BR></BR></BR></BR>
@endsection
@section('css')
@stop
