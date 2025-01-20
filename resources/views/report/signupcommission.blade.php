@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    <section id="signupcommission" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('network.volume_report')</h1>
              <div style="width: 100%; display: inline-block">
                <form action="{{ route('reports.signupcommission_filter') }}" method="POST">
                  @csrf
                  <table style="width: fit-content; float: right;">
                    <tr>
                      @if (isset($filter) and !empty($filter))
                        <td><select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
                            required name="month">
                            @foreach (range(1, 12) as $monthNumber)
                              <?php
                              $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
                              ?>
                              <option value="{{ $monthNumber }}" @if ($monthNumber == $month) selected @endif>
                                {{ $monthName }}</option>
                            @endforeach
                          </select></td>
                        <td><select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
                            required name="year">
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
                        <td><select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
                            required name="month">
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
                        <td><select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
                            required name="year">
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
                          style="padding-right: 10px; width: 100%; height: 40px; padding-left: 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; font-weight: bold;">Search</button>
                      </td>
                    </tr>
                  </table>

                </form>
              </div>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('network.volume_report_text')</h3>
                </div>
                <div class="card-header py-3">
                  <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                                    <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                                    <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                  <div class="card-tools">
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
                        <th>Id</th>
                        <th>@lang('reports.referral_commission.from')</th>
                        <th>@lang('reports.package')</th>
                        <th>@lang('reports.package_value')</th>
                        <th>@lang('reports.level')</th>
                        <th>@lang('reports.referral_commission.id_order')</th>
                        <th>@lang('reports.referral_commission.date')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($scores as $score)
                        @php
                          $user_id = $score->id_user;
                          $info_user = Illuminate\Support\Facades\DB::select(
                              "SELECT * FROM users WHERE id = '$user_id'",
                          );
                        @endphp

                        <tr>
                          <th>{{ $score->id }}</th>
                          @if (isset($info_user) and !empty($info_user))
                            <td>{{ $info_user[0]->login }}</td>
                          @else
                            <td>Not identified</td>
                          @endif

                          <td><span class="rounded-pill bg-warning px-4 py-1">Product Purchase</span>
                          </td>

                          <td><span
                              class="rounded-pill bg-primary px-4 py-1">R$ {{ number_format($score->total, 2, ',', '.') }}</span>

                          <td><span class="rounded-pill bg-primary px-4 py-1">{{ $score->level_from }}</span>

                          <td><span class="rounded-pill bg-primary px-4 py-1">{{ $score->number_order }}</span>

                          <td>{{ date('Y-m-d', strtotime($score->created_at)) }}</td>
                        </tr>
                      @empty
                        <p class="m-4 fst-italic">@lang('reports.referral_commission.empyt')</p>
                      @endforelse
                    </tbody>
                  </table>
                </div>
                @if (!isset($filter) and empty($filter))
                  <div class="card-footer clearfix py-3">
                    {{ $scores->links() }}
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
    </section>
  </main>
@endsection
