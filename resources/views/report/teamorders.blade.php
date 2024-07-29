@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1 style="padding: 1rem 0;">@lang('network.team_ordens')</h1>
              <div class="row">
                <div class="width: 100%; display: flex; flex-direction:row;align-items: flex-end">
                  <div style="width: 100%; display: flex; flex-direction:row;justify-content: space-between">
                    <table style="width: fit-content; display: inline-block;">
                      @if (isset($all_states) and !empty($all_states))
                        <form action="{{ route('reports.teamorders.filter') }}" method="GET">
                          @csrf
                          <tbody style="width: 100%;display:flex;">
                            <tr style="width: 100%;">
                              <td>
                                <input type="text" class="form-control" style="padding: 0.5 1.5rem;min-width:130px;"
                                  name="name" placeholder="Name">
                              </td>
                            </tr>
                            <tr style="width: 100%;">
                              <td>
                                <input type="text" class="form-control" style="padding: 0.5 1.5rem;min-width:130px;"
                                  name="lastname" placeholder="Last name">
                              </td>
                            </tr>
                            <tr style="width: 100%;">
                              <td>
                                <select name="states" class="form-select" style="padding: 0.5 1.5rem;min-width:170px">
                                  <option value="">@lang('network.filter_for_country')</option>
                                  @foreach ($all_states as $no_stts)
                                    <option value="{{ $no_stts }}">{{ $no_stts }}</option>
                                  @endforeach
                                </select>
                              </td>
                            <tr style="width: 100%;">
                              <td>
                                <button type="submit" class="btn "
                                  style="background-color: #d26075; color: #ffffff">@lang('admin.btn.search')</button>
                              </td>
                            </tr>
                            </tr>
                          </tbody>
                        </form>
                      @endif
                      <form action="{{ route('reports.teamorders.filter.date') }}" method="POST">
                        @csrf
                        <table style="width: fit-content;">
                          <tr>
                            @if (isset($month) and isset($year))
                              <td><select class="form-select" style="padding: 0.5 1.5rem"
                                  aria-label="Default select example" required name="month">
                                  @foreach (range(1, 12) as $monthNumber)
                                    <?php
                                    $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
                                    ?>
                                    <option value="{{ $monthNumber }}" @if ($monthNumber == $month) selected @endif>
                                      {{ $monthName }}</option>
                                  @endforeach
                                </select></td>
                              <td><select class="form-select" style="padding: 0.5 1.5rem"
                                  aria-label="Default select example" required name="year">
                                  @php
                                    $currentYear = date('Y');
                                    $startYear = 2023;
                                    $years = range($currentYear, $startYear);
                                  @endphp
                                  @foreach ($years as $yearr)
                                    <option style="padding: 0.5 1.5rem" value="{{ $yearr }}"
                                      @if ($yearr == $year) selected @endif>
                                      {{ $yearr }}</option>
                                  @endforeach
                                </select></td>
                            @else
                              <td><select class="form-select" style="padding: 0.5 1.5rem"
                                  aria-label="Default select example" required name="month">
                                  @php
                                    $monthsYear = [
                                        1 => 'January',
                                        2 => 'February',
                                        3 => 'March',
                                        4 => 'April',
                                        5 => 'May',
                                        6 => 'June',
                                        7 => 'July',
                                        8 => 'August',
                                        9 => 'September',
                                        10 => 'October',
                                        11 => 'November',
                                        12 => 'December',
                                    ];

                                    $dataNow = date('d-m-Y');
                                    $InfoDataNow = getdate(strtotime($dataNow));
                                  @endphp
                                  @for ($i = 1; $i < 13; $i++)
                                    @if ($InfoDataNow['mon'] == $i)
                                      <option value="{{ $i }}" selected>{{ $monthsYear[$i] }}</option>
                                    @else
                                      <option value="{{ $i }}">{{ $monthsYear[$i] }}</option>
                                    @endif
                                  @endfor
                                </select></td>
                              <td><select class="form-select" style="padding: 0.5 1.5rem"
                                  aria-label="Default select example" required name="year">
                                  @php
                                    $currentYear = date('Y');
                                    $startYear = 2023;
                                    $years = range($currentYear, $startYear);
                                  @endphp
                                  @foreach ($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                  @endforeach
                                </select></td>
                            @endif
                            <td><button
                                style="padding-right: 10px; width: 100%; height: 40px; padding-left: 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; font-size: 16px;">@lang('admin.btn.search')</button>
                            </td>
                          </tr>
                        </table>
                      </form>
                    </table>

                  </div>
                </div>

                <div class="card shadow my-3">
                  <div class="card-header bbcolorp">
                    <h2 class="card-title">@lang('network.team_ordens')</h2>
                  </div>
                  <div class="card-header py-3">

                    {{-- <div class="card-tools">
                    <div class="input-group input-group-sm my-1" style="width: 250px;">
                      <input type="text" name="table_search" class="form-control float-right rounded-pill pl-3"
                        placeholder="@lang('withdraw.search')">
                      <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                          <i class="bi bi-search"></i>
                        </button>
                      </div>
                    </div>
                  </div> --}}
                  </div>

                  <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                      <thead>
                        <tr>
                          <th>Id</th>
                          <th>@lang('network.name')</th>
                          <th>Username</th>
                          <th>E-mail</th>
                          <th>@lang('network.country')</th>
                          <!-- <th>@lang('network.state')</th> -->
                          <th>@lang('network.city')</th>
                          <th>@lang('network.total')</th>
                          <th>QV</th>
                          <th>CV</th>
                          <th>Date</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($all_resgister as $team)
                          @php
                            $team_id = $team->id;
                            if (isset($month) && isset($year)) {
                                $orders_total = Illuminate\Support\Facades\DB::select(
                                    "SELECT * FROM payments_order_ecomms WHERE id_user = '$team_id' AND status = 'paid' AND MONTH(created_at) = $month AND YEAR(created_at) = $year",
                                );
                            } else {
                                $orders_total = Illuminate\Support\Facades\DB::select(
                                    "SELECT * FROM payments_order_ecomms WHERE id_user = '$team_id' AND status = 'paid'",
                                );
                            }
                            $total_values = 0;
                            $qv = 0;
                            $cv = 0;
                          @endphp

                          @foreach ($orders_total as $values_price)
                            @php
                              $total_values = $total_values + $values_price->total_price;
                              $qv += DB::table('ecomm_orders')
                                  ->where('number_order', $values_price->number_order)
                                  ->sum('qv');

                              $cv += DB::table('ecomm_orders')
                                  ->where('number_order', $values_price->number_order)
                                  ->sum('cv');
                            @endphp
                          @endforeach

                          <tr>
                            <th>{{ $team->id }}</th>
                            <th>
                              {{ $team->name }} @if (!empty($team->last_name))
                                {{ $team->last_name }}
                              @endif
                            </th>
                            <th>
                              @if (!empty($team->login))
                                {{ $team->login }}
                              @endif
                            </th>
                            <th>{{ $team->email }}</th>
                            <th>{{ $team->country }}</th>
                            <!-- <th>{{ $team->state }}</th> -->
                            <th>{{ $team->city }}</th>
                            <th>€{{ number_format($total_values, 2, ',', '.') }}</th>
                            <th>{{ number_format($qv, 2, ',', '.') }}</th>
                            <th>{{ number_format($cv, 2, ',', '.') }}</th>
                            @if (isset($team->date))
                              <th>{{ date('d/m/Y', strtotime($team->date)) }}</th>
                            @else
                              <th>{{ date('d/m/Y', strtotime($team->created_at)) }}</th>
                            @endif
                            @if (isset($month) && isset($year))
                              <th><a
                                  href="{{ route('reports.DetailOrdersTeam', ['id' => $team->id, 'month' => $month ?? null, 'year' => $year ?? null]) }}"><button>view
                                    order</button></a></th>
                            @else
                              <th><a href="{{ route('reports.DetailOrdersTeam', ['id' => $team->id]) }}"><button>view
                                    order</button></a></th>
                            @endif
                          </tr>
                        @empty
                          <p class="m-4 fst-italic">@lang('network.no_recruits_news')no records for news recruits</p>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                  <div class="card-footer clearfix py-3">
                    <ul class="pagination pagination-sm m-0 float-right">
                      {{-- {{ $all_resgister->links() }} --}}
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </section>
  </main>
@endsection
