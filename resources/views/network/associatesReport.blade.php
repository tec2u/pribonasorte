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
              <div style="width: 100%; display: inline-block">
                <form action="{{ route('networks.associatesReport_filter') }}" method="POST">
                  @csrf
                  <table style="width: fit-content; float: right;">
                    <tr>
                      @if (isset($month) and isset($year))
                        <td>
                          <select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
                            required name="month">
                            @foreach (range(1, 12) as $monthNumber)
                              <?php
                              $monthName = date('F', mktime(0, 0, 0, $monthNumber, 1));
                              ?>
                              <option value="{{ $monthNumber }}" @if ($monthNumber == $month) selected @endif>
                                {{ $monthName }}</option>
                            @endforeach
                          </select>
                        </td>
                        <td>
                          <select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
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
                          </select>
                        </td>
                      @else
                        <td>
                          <select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
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
                          </select>
                        </td>
                        <td>
                          <select class="form-select" style="padding: 0.5 1.5rem" aria-label="Default select example"
                            required name="year">
                            @php
                              $currentYear = date('Y');
                              $startYear = 2023;
                              $years = range($currentYear, $startYear);
                            @endphp
                            @foreach ($years as $year)
                              <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                          </select>
                        </td>
                      @endif
                      <td><button
                          style="padding-right: 10px; width: 100%; height: 40px; padding-left: 10px; background-color: #d26075; color: #ffffff; border-radius: 5px; font-weight: bold;">@lang('home.search')</button>
                      </td>
                    </tr>
                  </table>

                </form>

              </div>
              <!-- <h1>@lang('support.support_tickets')</h1> -->
              <h1>@lang('network.associates')</h1>
              <div class="card shadow my-3">

                @if (!empty($networks))

                  <div class="table-responsive">

                    <table class="table accordion table-hover">
                      <thead>
                        <tr>
                          <th scope="col"></th>
                          <th scope="col">@lang('network.login')</th>
                          <th scope="col">@lang('network.name')</th>
                          <th scope="col">@lang('network.sponsor')</th>
                          <th scope="col">@lang('network.coutry')</th>
                          <th scope="col">@lang('network.email')</th>
                          <th scope="col">Volume</th>
                          <th scope="col">@lang('network.level')</th>
                        </tr>
                      </thead>
                      <tbody>
                        <div>
                          @forelse($networks as $network)
                            @php
                              $mstr_id = $id_user;
                              $iduser = $network->id;
                              $id_spns = $network->recommendation_user_id;

                              $idsExternal = Illuminate\Support\Facades\DB::select(
                                  "SELECT id FROM ecomm_registers where recommendation_user_id = $iduser",
                              );

                              $ids = [];

                              foreach ($idsExternal as $row) {
                                  $ids[] = $row->id;
                              }

                              $idsString = implode(',', $ids);

                              // dd($ids);

                              $qvExternal = 0;

                              if (count($ids) > 0) {
                                  if (isset($year) && $year != null && isset($month) && $month != null) {
                                      $qvExternal = Illuminate\Support\Facades\DB::select(
                                          "SELECT SUM(eo.qv) AS total FROM ecomm_orders eo JOIN payments_order_ecomms p ON eo.number_order = p.number_order WHERE eo.id_user IN ($idsString)
                                            AND YEAR(eo.created_at) = $year AND MONTH(eo.created_at) = $month AND LOWER(p.status) = 'paid'",
                                      );
                                  } else {
                                      $year = date('Y');
                                      $month = date('m');
                                      $qvExternal = Illuminate\Support\Facades\DB::select(
                                          "SELECT SUM(eo.qv) AS total FROM ecomm_orders eo JOIN payments_order_ecomms p ON eo.number_order = p.number_order WHERE eo.id_user IN ($idsString)
                                             AND YEAR(eo.created_at) = $year AND MONTH(eo.created_at) = $month AND LOWER(p.status) = 'paid'",
                                      );
                                  }
                              }

                              if (isset($year) && $year != null && isset($month) && $month != null) {
                                  $volume = Illuminate\Support\Facades\DB::select(
                                      "SELECT sum(score) as total FROM historic_score where user_id = $iduser AND YEAR(created_at) = $year AND MONTH(created_at) = $month",
                                  );
                              } else {
                                  $year = date('Y');
                                  $month = date('m');
                                  $volume = Illuminate\Support\Facades\DB::select(
                                      "SELECT sum(score) as total FROM historic_score where user_id = $iduser AND YEAR(created_at) = $year AND MONTH(created_at) = $month",
                                  );
                              }

                              $sponsor = Illuminate\Support\Facades\DB::select(
                                  "SELECT * FROM users WHERE id = $id_spns",
                              );
                              $volume = isset($volume[0]->{'total'}) ? $volume[0]->{'total'} : 0;
                              $qvExternal = isset($qvExternal[0]->{'total'}) ? $qvExternal[0]->{'total'} : 0;

                            @endphp

                            <tr scope="row" data-bs-toggle="collapse" data-bs-target="#id{{ $network->id }}"
                              value="{{ $network->id }}">
                              <td><button id="botao{{ $network->id }}" onclick="getdiretos({{ $network->id }})"
                                  class="btn btn-primary">+</button></td>
                              <td>{{ $network->login }}</td>
                              <td>{{ $network->name }}</td>
                              {{-- <td>{{ $network->getReferalName($network->recommendation_user_id) }}</td> --}}
                              <td>{{ $sponsor[0]->login }}</td>
                              <td>{{ $network->country }}</td>
                              <td>{{ $network->email }}</td>
                              @if (!empty($volume))
                                <td>{{ $volume }}</td>
                              @else
                                <td>0</td>
                              @endif
                              <td id="nivel{{ $network->id }}">1</td>
                            </tr>
                            <tr>
                              <td id="local{{ $network->id }}" style="display: none; padding: 0px" colspan="9">
                              </td>
                            </tr>
                          @empty
                            <p>@lang('network.any_referrals_registered')</p>
                          @endforelse
                        </div>
                      </tbody>
                    </table>
                  </div>
                @else
                  <p>@lang('network.any_referrals_registered')</p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div id="loading">
    <img id="loading-image" src="gif.gif" alt="Loading..." />
  </div>

  <script type="text/javascript">
    function getdiretos(id) {

      var nivel = $('#nivel' + id).html();
      if ($('#local' + id).css('display') === 'none') {
        nivel++;
        $('#botao' + id).html("-");
        var login = id;
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url: '{{ route('networks.pesquisa') }}',
          type: 'POST',
          data: {
            login: login,
            year: '{{ $year }}',
            month: '{{ $month }}'
          },
          dataType: 'json',
          beforeSend: function() {
            $('#loading').show();
          },
          success: function(data) {
            if (data.error) {
              var tabela =
                "<table class='table accordion table-hover'><tr><td colspan ='9'>No user under this person</td></tr></table>";
              $('#local' + login).html('');
              $('#local' + login).append(tabela);
              $('#local' + login).show('slow');
              return false;
            }

            console.log(data);

            var tabela = "<table class='table accordion table-hover'>"


              +
              "<thead>" +
              "<tr>" +
              "<th scope='col'></th>" +
              "<th scope='col'>@lang('network.login')</th>"
              // +"<th scope='col'>@lang('network.name')</th>"
              +
              "<th scope='col'>@lang('network.sponsor')</th>" +
              "<th scope='col'>@lang('network.coutry')</th>" +
              "<th scope='col'>@lang('network.email')</th>" +
              "<th scope='col'>Volume</th>" +
              "<th scope='col'>@lang('network.level')</th>" +
              "</tr>" +
              "</thead>";


            for (var i = 0; i < data.length; i++) {
              tabela += "<tr scope='row' data-bs-toggle='collapse' data-bs-target='#id" + data[i]['id'] +
                "' value='" + data[i]['id'] + "'>";
              tabela += "<td><button id='botao" + data[i]['id'] + "' onclick='getdiretos(" + data[i]['id'] +
                ")' class='btn btn-primary'>+</button></td>";
              tabela += "<td>" + data[i]['login'] + "</td>";
              // tabela += "<td>"+data[i]['name']+"</td>";
              tabela += "<td>" + data[i]['patrocinador'] + "</td>";
              tabela += "<td>" + data[i]['country'] + "</td>";
              tabela += "<td>" + data[i]['email'] + "</td>";
              tabela += "<td>" + data[i]['volume'] + "</td>";
              tabela += "<td id='nivel" + data[i]['id'] + "'>" + nivel + "</td>";
              tabela += "</tr>";
              tabela += "<tr>";
              tabela += "<td id='local" + data[i]['id'] + "' style='display:none; padding:0px;' colspan='9'>";
              tabela += "</td>";
              tabela += "</tr>";
            }
            tabela += "</tbody></table>";

            $('#local' + login).html('');
            $('#local' + login).append(tabela);
            $('#local' + login).show('slow');

          },
          complete: function() {

            $('#loading').hide();
          }
        });
      } else {
        nivel--;
        $('#local' + id).hide('slow');
        $('#local' + id).html("");
        $('#botao' + id).html("+");
      }
    };
    $(document).ready(function() {
      $('#loading').hide();
    });
  </script>


@endsection
@section('css')
@stop
