@extends('layouts.header')
@section('content')
  <main id="main" class="main">
    <section id="withdrawhistory" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('network.new_recruits')</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('network.new_recruits')</h3>
                </div>
                <div class="card-header py-3">
                  <form action="{{ route('reports.newrecruitsDate') }}" method="POST">
                    @csrf
                    <table style="width: fit-content;">
                      <tr>
                        @if (isset($month) and isset($year))
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

                <div class="card-body table-responsive p-0">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>@lang('network.name')</th>
                        <th>Login</th>
                        <th>E-mail</th>
                        <th>@lang('network.phone')</th>
                        <th>@lang('network.country')</th>
                        <th>@lang('network.city')</th>
                        <th>@lang('network.registered')</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse ($array_user as $recruits)
                        <tr>
                          <th>{{ $recruits['id'] }}</th>
                          <th>{{ $recruits['name'] }} {{ $recruits['last_name'] }}</th>
                          <th>{{ $recruits['login'] }}</th>
                          <th>{{ $recruits['email'] }}</th>
                          <th>{{ $recruits['cell'] }}</th>
                          <th>{{ $recruits['country'] }}</th>
                          <th>{{ $recruits['city'] }}</th>
                          <th>{{ $recruits['created_at'] }}</th>
                        </tr>
                      @empty
                        <p class="m-4 fst-italic">@lang('network.no_records')no records for news recruits</p>
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
@endsection
