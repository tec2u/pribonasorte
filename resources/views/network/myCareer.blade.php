@extends('layouts.header')
@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <style>
    .hiddenRow {
      padding: 0 !important;
    }

    .career-container {
      width: 100%;
      display: flex;
      flex-wrap: wrap;

      gap: 1rem;
    }

    .career {
      min-width: 250px;
      max-width: 250px;
      height: 250px;

      display: flex;
      flex-direction: column;
      align-items: center;
      color: black;
      font-size: 1rem;

      border: 1px solid #e0e0e0;

    }

    .career img {
      border-radius: 50%;
      width: 150px;
      height: 150px;
    }

    .not-achieved {
      opacity: .5;
      gap: 1rem;
      -webkit-filter: grayscale(100%);
      /* Safari 6.0 - 9.0 */
      filter: grayscale(100%);
    }

    .img-career {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background-color: white;
    }

    .img-career img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
    }

    .box-career {
      display: flex;
      align-items: center;
      padding-left: 10px;
    }

    .box-career .info-box-text {
      font-size: 1rem;
      margin-bottom: -10px
    }

    .box-career .info-box-number {
      font-size: .7rem;
      color: #2b2b2b !important;
      font-weight: 900;
    }

    progress::-webkit-progress-value {
      background-color: #460C52;
      /* Cor desejada para o progresso */
    }

    /* Para Firefox */
    progress::-moz-progress-bar {
      background-color: #460C52;
      /* Cor desejada para o progresso */
    }
  </style>

  @php

    $user_id = ucwords(auth()->user()->id);

    $firstDayOfMonth = now()->firstOfMonth()->toDateString();
    $lastDayOfMonth = now()->lastOfMonth()->toDateString();

    $id_greatest_career = Illuminate\Support\Facades\DB::select(
        "SELECT career_id,created_at FROM career_users where user_id=$user_id order by career_id desc limit 1",
    );

    if (count($id_greatest_career) > 0) {
        $id_greatest_career = $id_greatest_career[0]->career_id;
        $greatest_career_user = Illuminate\Support\Facades\DB::select(
            "SELECT * FROM career WHERE id = '$id_greatest_career' ORDER BY id DESC LIMIT 1",
        )[0];

        // carreira atual

        $id_career_user = Illuminate\Support\Facades\DB::select(
            "SELECT career_id FROM career_users WHERE user_id = '$user_id' AND created_at >= '$firstDayOfMonth' AND created_at <= '$lastDayOfMonth' ORDER BY created_at DESC LIMIT 1",
        );
        if (count($id_career_user) > 0) {
            $id_career_user = $id_career_user[0]->career_id;
            $career_user = Illuminate\Support\Facades\DB::select(
                "SELECT * FROM career WHERE id = '$id_career_user' ORDER BY id DESC LIMIT 1",
            )[0];
        } else {
            $career_user = '';
        }
    } else {
        $greatest_career_user = '';
        $career_user = '';
    }

  @endphp


  <main id="main" class="main">
    <section id="supporttable" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('reports.career.my_career')</h1>
              <br>
              <br>
              <br>
              <div class="career-container">

                @foreach ($careers as $item)
                  @php
                    $id_user = ucwords(auth()->user()->id);

                    $careerCheck = DB::select(
                        "SELECT * FROM career_users WHERE user_id=$id_user and career_id=$item->id order by id asc",
                    );

                  @endphp

                  @if ($item->achieved)
                    <div class="career">
                      <p style="text-align: center;">
                        @lang('reports.career.achieved')
                        <br>
                        <strong>
                          @if (isset($item->dt_achieved))
                            {{ date('d/m/Y', strtotime($item->dt_achieved)) }}
                          @endif
                        </strong>
                      </p>
                      <img src="/images/Badges/{{ $item->id }}.png" alt="">
                      <span>{{ $item->name }}</span>
                    </div>
                  @else
                    <div class="career not-achieved">
                      <p style="text-align: center;">
                        @lang('reports.career.not_achieved')
                        <br>
                        <strong>

                        </strong>
                      </p>
                      <img src="/images/Badges/{{ $item->id }}.png" alt="">
                      <span>{{ $item->name }}</span>
                    </div>
                  @endif
                @endforeach
              </div>
            </div>
          </div>
          <div class="row" style='margin-top:30px'>
            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-4 shadow elevation c1 box-info box-career">
                <div class="img-career">
                  <img src="/images/Badges/{{ $greatest_career_user->id }}.png" alt="{{ $greatest_career_user->name }}">
                </div>
                <div class="info-box-content" style="color: #000;">
                  <span class="info-box-number">@lang('reports.career.greatest')</span>
                  @if (isset($greatest_career_user->name))
                    <span class="info-box-text">{{ $greatest_career_user->name }}</span>
                    @if ($greatest_career_user->created_at)
                      <span
                        class="info-box-number">{{ date('Y-m-d', strtotime($greatest_career_user->created_at)) }}</span>
                    @endif
                  @else
                    <span class="info-box-text">{{ $greatest_career_user }}</span>
                  @endif
                </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-6">
              <div class="info-box mb-4 shadow elevation c1 box-info box-career">
                <div class="img-career">
                  @if (isset($career_user->id))
                    <img src="/images/Badges/{{ $career_user->id }}.png" alt="{{ $career_user->name }}">
                </div>
                <div class="info-box-content" style="color: #000;">
                  <span class="info-box-number">@lang('reports.career.career_performance')</span>
                  @if (isset($career_user->name))
                    <span class="info-box-text">{{ $career_user->name }}</span>
                    @if ($career_user->created_at)
                      <span class="info-box-number">{{ date('Y-m-d', strtotime($career_user->created_at)) }}</span>
                    @endif
                  @else
                    <span class="info-box-text">{{ $greatest_career_user->name }}</span>
                  @endif
                </div>
              @else
                <img src="/images/nolimitslogo.png" alt="">
              </div>
              @endif


            </div>
          </div>
          </row>
        </div>
      </div>

      <section id="userpackageinfo" class="content">
        <div class="fade">
          <div class="container-fluid">
            <div class="row">
              <div class="col-12">
                <div class="card shadow my-3">
                  <div class="card-header bbcolorp">
                    <h3 class="card-title">@lang('reports.career.my_team')</h3>
                  </div>
                  <div class="card-header py-3">
                    <!-- <button type="button" class="btn btn-info btn-sm rounded-pill" style="width: 80px;">CSV</button>
                                                                                                                                                                                                                                                                                                                                                                                                          <button type="button" class="btn btn-success btn-sm rounded-pill" style="width: 80px;">Excel</button>
                                                                                                                                                                                                                                                                                                                                                                                                          <button type="button" class="btn btn-danger btn-sm rounded-pill" style="width: 80px;">PDF</button> -->
                    <div class="card-tools">
                    </div>
                  </div>
                  @if (isset($users_lista) && count($users_lista) > 0)
                    <div class="card-body table-responsive p-0">
                      <table class="table table-hover text-nowrap">
                        <thead>
                          <tr>
                            <th>@lang('reports.career.name')</th>
                            <th style="text-align: center">@lang('reports.career.total_qv')</th>
                            <th style="text-align: center">/</th>
                            <th style="text-align: center">@lang('reports.career.after_qv')</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach ($users_lista as $item)
                            <tr>
                              <td>{{ $item['name'] }} {{ $item['last_name'] }}</td>
                              <td style="text-align: center">{{ $item['total'] }}</td>
                              <td></td>
                              <td style="text-align: center">{{ $item['aproveitado'] }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  @endif
                  <div class="card-footer clearfix py-3 col-12 d-flex">
                    <div class="card-footer clearfix col-12 d-flex" style="flex-direction: column">
                      @if (isset($career_user->name))
                        <h5>@lang('reports.career.my_career') - {{ $career_user->name ?? '' }}</h5>
                        <div style="display: flex;align-items: top;width: 80%; align-items: center; gap:1rem;">
                          @lang('reports.career.progress')
                          <p style="width:fit-content; margin: 0" data-value="{{ $soma }}">
                          </p>
                          <progress max="{{ $proximaCarreira->volumeRequired }}" value="{{ $soma > 0 ? $soma : 1 }}"
                            class="html5" style="color: #470D53;width: 100%">
                            <div class="progress-bar">
                              <span style="width: 80%"></span>
                            </div>
                          </progress>
                        </div>
                      @else
                        <h5>@lang('reports.career.my_career')</h5>
                      @endif
                    </div>
                  </div>
                  <div class="card-footer clearfix py-3 col-12 d-flex" style="flex-wrap: wrap">
                    <div class="col-12 col-sm-6 col-md-4">
                      <div class="info-box mb-4 shadow c1 box-info">
                        <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                        <div class="info-box-content" style="color: #000;">
                          <span class="info-box-text">@lang('reports.career.all_points')</span>
                          <span class="info-box-number">{{ number_format($soma, 2, ',', '.') }}</span>
                        </div>

                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                      <div class="info-box mb-4 shadow c1 box-info">
                        <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                        <div class="info-box-content" style="color: #000;">
                          <span class="info-box-text">@lang('reports.career.my_directs')</span>
                          <span class="info-box-number">{{ $quantDiretos }} </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                      <div class="info-box mb-4 shadow c1 box-info">
                        <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                        <div class="info-box-content" style="color: #000;">
                          <span class="info-box-text">@lang('reports.career.personal_volume')</span>
                          <span class="info-box-number">{{ number_format($meus_pontos, 2, ',', '.') }} </span>
                        </div>

                      </div>
                    </div>
                  </div>

                  <div class="card-footer clearfix col-12 d-flex" style="flex-wrap: wrap">
                    <h5>@lang('reports.career.next_career') - {{ $proximaCarreira->name }} ( Required Volume:
                      {{ number_format($proximaCarreira->volumeRequired, 2, ',', '.') }} ) </h5>
                  </div>
                  <div class="card-footer clearfix py-3 col-12 d-flex" style="flex-wrap: wrap">
                    <div class="col-12 col-sm-6 col-md-4">
                      <div class="info-box mb-4 shadow c1 box-info">
                        <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                        <div class="info-box-content" style="color: #000;">
                          <span class="info-box-text">@lang('reports.career.points_left')</span>
                          @php
                            $leftPoints = $proximaCarreira->volumeRequired - $soma;
                          @endphp
                          @if ($leftPoints > 0)
                            <span class="info-box-number">{{ number_format($leftPoints, 2, ',', '.') }}</span>
                          @else
                            <span class="info-box-number" style="color: green">@lang('reports.career.completed')</span>
                          @endif
                        </div>

                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                      <div class="info-box mb-4 shadow c1 box-info">
                        <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                        <div class="info-box-content" style="color: #000;">
                          <span class="info-box-text">@lang('reports.career.directs_left')</span>
                          @php
                            $directsLeft = $proximaCarreira->directs - $quantDiretos;
                          @endphp
                          @if ($directsLeft > 0)
                            <span class="info-box-number">{{ $directsLeft }} </span>
                          @else
                            <span class="info-box-number" style="color: green">@lang('reports.career.completed')</span>
                          @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4">
                      <div class="info-box mb-4 shadow c1 box-info">
                        <span class="info-box-icon"><i class="bi bi-trophy-fill"></i></span>
                        <div class="info-box-content" style="color: #000;">
                          <span class="info-box-text">@lang('reports.career.pv_left')</span>
                          @php
                            $pvLeft = $proximaCarreira->personalVolume - $meus_pontos;
                          @endphp
                          @if ($pvLeft > 0)
                            <span class="info-box-number">{{ number_format($pvLeft, 2, ',', '.') }}
                            </span>
                          @else
                            <span class="info-box-number" style="color: green">@lang('reports.career.completed')</span>
                          @endif
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>

    </section>
  </main>

@endsection
@section('css')
@stop
