@extends('layouts.header')
@section('content')

  <main id="main" class="main">
    <section id="myreferrals" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <h1 class="mb-3">@lang('network.enrolled')ENROLLED DISTRIBUTORS</h1>

            @if (!empty($networks))
              @foreach ($networks as $network)
                @php
                  $id_user = $network->user_id;
                  $user_info = Illuminate\Support\Facades\DB::select("SELECT * FROM users WHERE id = '$id_user'");

                  $directVolume = Illuminate\Support\Facades\DB::select("SELECT sum(score) as total FROM historic_score where user_id=$id_user and level_from=1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
                  $directVolume = isset($directVolume[0]->{'total'}) ? $directVolume[0]->{'total'} : 0;

                  $indirectVolume = Illuminate\Support\Facades\DB::select("SELECT sum(score) as total FROM historic_score where user_id=$id_user and level_from>1 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
                  $indirectVolume = isset($indirectVolume[0]->{'total'}) ? $indirectVolume[0]->{'total'} : 0;

                  $totalVolume = $indirectVolume + $directVolume;

                  $personalVolume = Illuminate\Support\Facades\DB::select("SELECT sum(score) as total FROM historic_score where user_id=$id_user and level_from=0 and DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m')");
                  $personalVolume = isset($personalVolume[0]->{'total'}) ? $personalVolume[0]->{'total'} : 0;

                  $directCustomers = Illuminate\Support\Facades\DB::select("SELECT id FROM ecomm_registers WHERE recommendation_user_id = $id_user");
                  $personalVolumeEcomm = 0;

                  if (count($directCustomers) > 0) {
                      foreach ($directCustomers as $value) {
                          $idEcomm = $value->id;
                          $qvEcomm = Illuminate\Support\Facades\DB::select("SELECT SUM(qv) AS total FROM ecomm_orders WHERE id_user=$idEcomm AND client_backoffice = 0 AND DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURRENT_DATE(), '%Y-%m') AND number_order IN (SELECT number_order FROM payments_order_ecomms WHERE status = 'paid')");
                          $personalVolumeEcomm += isset($qvEcomm[0]->{'total'}) ? $qvEcomm[0]->{'total'} : 0;
                      }
                  }

                  $personalVolume = $personalVolume + $personalVolumeEcomm;
                @endphp

                <div class="col-lg-4 col-md-6 col-sm-12">
                  <div class=" card card-widget widget-user-2 shadow">
                    <div class="widget-user-header bbcolorp">
                      <div class="widget-user-image">
                        @if (!empty($network->user->image_path))
                          <img class="img-circle elevation-2 mx-2"
                            src="{{ asset('storage/' . $network->user->image_path) }}" alt="@lang('network.user_avatars')"
                            class="rounded-circle">
                        @else
                          <img class="img-circle elevation-2 mx-2" src="../../../assetsWelcome/images/favicon.jpeg"
                            alt="@lang('network.user_avatars')" class="rounded-circle">
                        @endif
                      </div>
                      <h3 class="widget-user-username px-3" style="font-size: 18px;"><b>{{ $network->user->name }}
                          {{ $network->user->last_name }}</b></h3>
                      <h3 class="widget-user-username px-3" style="font-size: 16px;">{{ $network->user->login }}</h3>
                      <h3 class="widget-user-username px-3" style="font-size: 15px;">{{ $network->user->email }}</h3>
                      <h3 class="widget-user-username px-3" style="font-size: 15px;">{{ $network->user->cell }}</h3>
                    </div>
                    <div class="card-footer p-0">
                      <ul class="nav flex-column">
                        <li class="nav-item p-4">
                          @if (isset($totalVolume))
                            <h6>
                              Volume <span
                                class="float-right">{{ number_format($totalVolume, 2, ',', '.') ?? '0' }}</span>
                            </h6>
                          @else
                            <h6>
                              Volume <span class="float-right">0</span>
                            </h6>
                          @endif
                        </li>
                        <li class="nav-item p-4">
                          <h6>
                            Personal Volume <span
                              class="float-right">{{ number_format($personalVolume, 2, ',', '.') }}</span>
                          </h6>
                        </li>
                        <li class="nav-item p-4">
                          <span>
                            <h6>
                              @lang('network.status')
                              @if ($network->getTypeActivated($network->id) == 'AllCards')
                                <span class="float-right badge rounded-pill bg-success px-3 py-2">@lang('network.active')</span>
                              @elseif ($network->getTypeActivated($network->id) == 'PreRegistration')
                                <span class="float-right badge rounded-pill bg-primary px-3 py-2">@lang('network.PreRegistration')</span>
                              @else
                                <span class="float-right badge rounded-pill bg-danger px-3 py-2">@lang('network.inactive')</span>
                              @endif

                            </h6>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                {{-- <p>@lang('network.any_referrals_registered')</p> --}}
              @endforeach
            @else
              <p>@lang('network.any_referrals_registered')</p>
            @endif
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection
