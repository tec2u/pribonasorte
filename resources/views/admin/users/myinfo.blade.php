@extends('adminlte::page')

@section('title', 'Settings')

@section('content_header')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

  <div class="alignHeader">
    <h4>@lang('admin.myinfo.title')</h4>
  </div>
@stop

@section('content')
  @include('flash::message')
  @if ($errors->has('error'))
    <div class="alert alert-danger">
      <p>{{ $errors->first('error') }}</p>
    </div>
  @endif
  <div class="card relative">
    <div id="msg_popup"></div>
    <div class="card-body">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-12 col-lg-10 col-xl-8 mx-auto">
            <form action="{{ route('admin.users.update', ['id' => $user->id]) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="row g-3 px-2" style="display: ;">
                <div class="col-lg-6">
                  <input id="individual" type="radio" @if (!isset($user->corporate_nome) && !isset($user->id_corporate)) checked @endif value="1"
                    name="type_account">
                  <p> Personal Registration</p>
                </div>

                <div class="col-lg-6">
                  <input id="corporate" type="radio" @if (isset($user->corporate_nome) && isset($user->id_corporate)) checked @endif value="2"
                    name="type_account">
                  <p> Company Registration</p>
                </div>
              </div>
              <div id="box_corporate"
                @if (isset($user->corporate_nome) && isset($user->id_corporate)) style="margin-bottom: 40px;"
              @else
              style="display: none; margin-bottom: 40px;" @endif
                class="row g-3 px-2">
                <div class="form-group col-md-12 ">
                  <label for="activated">Status Active</label>
                  <select id="activated" name="activated" class="form-control">
                    <option value="1" @if ($user->activated == 1) selected @endif>
                      Active
                    </option>
                    <option value="0" @if ($user->activated == 0) selected @endif>
                      Inactive
                    </option>
                  </select>
                </div>
                <div class="col-lg-6">
                  <div>
                    <label for="id_corp" class="form-label teste1234">ID Corporate<span
                        style="color: brown">*</span></label>
                    <input type="text" name="campo_nao_usado" style="display:none;">
                    <input id="id_corp" type="text" class="form-control form-register" placeholder="ID Corporate"
                      name="id_corporate" value="{{ $user->id_corporate }}" autocomplete="off"
                      onblur="verifyIDCorporate()">
                    <span for="id_corp" class="focus-input100"></span>
                  </div>
                  <div id="content_activ_corporate" {{-- style="display: {{ $user->activated_corporate == 1 ? 'block' : 'none' }}" --}} class="mt-1">
                    <label for="activated_corporate">Activate company account?</label>
                    <span>
                      <span>Yes:</span> <input type="radio" name="activated_corporate"
                        {{ $user->activated_corporate == 1 ? 'checked' : '' }} value="1">

                      <span class="ml-2">No:</span> <input type="radio" name="activated_corporate"
                        {{ $user->activated_corporate == 0 ? 'checked' : '' }} value="0">
                    </span>
                  </div>
                </div>

                <div class="col-lg-6">
                  <label for="name_corp" class="form-label teste1234">Name Company<span
                      style="color: brown">*</span></label>
                  <input type="text" name="campo_nao_usado" style="display:none;">
                  <input id="name_corp" type="text" class="form-control form-register" placeholder="Name corporate"
                    name="corporate_nome" value="{{ $user->corporate_nome }}" autocomplete="off">
                  <span for="name_corp" class="focus-input100"></span>

                </div>

                <div class="col-lg-6">
                  <label for="tax_id" class="form-label teste1234">Tax ID<span style="color: brown">*</span></label>
                  <input type="text" name="campo_nao_usado" style="display:none;">
                  <input id="tax_id" type="text" class="form-control form-register" placeholder="tax id"
                    name="tax_id" value="{{ $user->tax_id }}" autocomplete="off">
                  <span for="tax_id" class="focus-input100"></span>

                </div>

                <div class="col-lg-6">
                  <label for="vat_reg_no" class="form-label teste1234">Vat reg. no.<span
                      style="color: brown">*</span></label>
                  <input type="text" name="campo_nao_usado" style="display:none;">
                  <input id="vat_reg_no" type="text" class="form-control form-register" placeholder="Vat reg. no."
                    name="vat_reg_no" value="{{ $user->vat_reg_no }}" autocomplete="off">
                  <span for="vat_reg_no" class="focus-input100"></span>

                </div>

                <h5 class="form-label teste1234">Address Billing</h5>

                <div class="col-md-6">
                  <label for="inputname" class="form-label">City<span style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="city" name="billing_city"
                    value="{{ $billing->city ?? '' }}">
                </div>

                <div class="col-md-6">
                  <label for="inputname" class="form-label">number_residence<span style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="number_residence" name="billing_number"
                    value="{{ $billing->number_residence ?? '' }}">
                </div>

                <div class="col-md-6">
                  <label for="inputname" class="form-label">state<span style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="state" name="billing_state"
                    value="{{ $billing->state ?? '' }}">
                </div>
                <div class="col-md-6">
                  <label for="inputname" class="form-label">Address<span style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="address1" name="billing_address"
                    value="{{ $billing->address ?? '' }}">
                </div>

                <div class="col-md-6">
                  <label for="inputname" class="form-label">@lang('user.postcode')<span
                      style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="postcode" name="billing_postcode"
                    value="{{ $billing->zip ?? '' }}">
                </div>

                <div class="col-md-6">
                  <label for="inputAddress" class="form-label">@lang('user.country')<span
                      style="color: brown">*</span></label>
                  <select class="form-control" id="country" name="billing_country" tabindex="7">
                    <option>Select Country</option>
                    @php
                      $country2 = $billing->country ?? '';
                    @endphp
                    @foreach ($countryes as $item)
                      <option value="{{ $item->country }}" @if ($country2 == $item->country) selected="selected" @endif>
                        {{ $item->country }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              @if (isset($user->corporate_nome) && isset($user->id_corporate))
                <label for="PayVat">Pay VAT</label>
                <select id="PayVat" name="pay_vat" class="form-select" aria-label="Default select example">
                  {{-- <option selected>Open this select menu</option> --}}
                  <option value="1" @if ($user->pay_vat == 1) selected @endif>Yes</option>
                  <option value="0" @if ($user->pay_vat == 0) selected @endif>No</option>
                </select>
              @endif

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="firstname">@lang('admin.myinfo.firstname')</label>
                  <input type="text" id="name" name="name" class="form-control" placeholder="Unwritten"
                    value="{{ $user->name }}" />
                </div>
                <div class="form-group col-md-6">
                  <label for="firstname">@lang('admin.myinfo.lastname')</label>
                  <input type="text" id="last_name" name="last_name" class="form-control" placeholder="Unwritten"
                    value="{{ $user->last_name }}" />
                </div>
                <div class="form-group col-md-4">
                  <label for="firstname">@lang('admin.myinfo.address') 1</label>
                  <input type="text" id="address1" name="address1" class="form-control" placeholder="Unwritten"
                    value="{{ $user->address1 }}" />
                </div>
                <div class="form-group col-md-4">
                  <label for="firstname">@lang('admin.myinfo.address') 2</label>
                  <input type="text" id="address2" name="address2" class="form-control" placeholder="Unwritten"
                    value="{{ $user->address2 }}" />
                </div>
                <div class="form-group col-md-4">
                  <label for="firstname">@lang('admin.myinfo.postcode')</label>
                  <input type="text" id="postcode" name="postcode" class="form-control" placeholder="Unwritten"
                    value="{{ $user->postcode }}" />
                </div>
                <div class="form-group col-md-4">
                  <label for="firstname">@lang('admin.myinfo.state')</label>
                  <input type="text" id="state" name="state" class="form-control" placeholder="Unwritten"
                    value="{{ $user->state }}" />
                </div>
                <div class="form-group col-md-4">
                  <label for="firstname">@lang('city')</label>
                  <input type="text" id="state" name="city" class="form-control" placeholder="Unwritten"
                    value="{{ $user->city }}" />
                </div>
                <div class="col-md-6">
                  <label for="inputname" class="form-label">@lang('network.area_residence')<span
                      style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="area_residence" required name="area_residence"
                    value="@if (old('area_residence') !== null) {{ old('area_residence') }}@else{{ $user->area_residence }} @endif">
                  @error('area_residence')
                    <span class="invalid-feedback " style="display:block" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="inputname" class="form-label">@lang('network.complement')<span
                      style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="complement" name="complement"
                    value="@if (old('complement') !== null) {{ old('complement') }}@else{{ $user->complement }} @endif">
                  @error('complement')
                    <span class="invalid-feedback " style="display:block" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-4">
                  <label for="country">@lang('admin.myinfo.country')</label>
                  <select id="country" name="country" class="form-control">
                    @foreach ($countryes as $item)
                      <option value="{{ $item->country }}"
                        @if ($user->country == $item->country) selected="selected" @endif>
                        {{ $item->country }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="gender">@lang('admin.myinfo.gender')</label>
                  <select id="gender" name="gender" class="form-control">
                    <option value="F" @if ($user->gender == 'F') selected @endif>@lang('admin.myinfo.female')
                    </option>
                    <option value="M" @if ($user->gender == 'M') selected @endif>@lang('admin.myinfo.male')
                    </option>
                  </select>
                </div>
                <div class="form-group col-md-6">
                  <label for="lastname">@lang('admin.myinfo.email')</label>
                  <input type="text" id="email" name="email" class="form-control"
                    placeholder="user@celebrity" value="{{ $user->email }}" />
                </div>
                <div class="col-md-6">
                  <label for="inputname" class="form-label">number_residence<span style="color: brown">*</span></label>
                  <input type="text" class="form-control" id="number_residence" required name="number_residence"
                    value="@if (old('number_residence') !== null) {{ old('number_residence') }}@else{{ $user->number_residence }} @endif">
                  @error('number_residence')
                    <span class="invalid-feedback " style="display:block" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group col-md-6">
                  <label for="inputAddress5">@lang('admin.myinfo.cell')</label>
                  <input type="number" class="form-control" id="cell" name="cell" placeholder="E.U.S"
                    value="{{ $user->cell }}" />
                </div>
                <div class="form-group col-md-6">
                  <label for="lastname">@lang('admin.myinfo.telephone')</label>
                  <input type="number" id="telephone" name="telephone" class="form-control"
                    placeholder="Your Number" value="{{ $user->telephone }}" />
                </div>
                <div class="form-group col-md-6">
                  <label for="lastname">@lang('admin.myinfo.date')</label>
                  <input type="date" id="birthday" name="birthday" class="form-control" placeholder="Your Number"
                    value="{{ $user->birthday }}" />
                </div>
              </div>



              <div class="form-group col-md-6">
                <label for="rule">@lang('admin.myinfo.rule')</label>
                <select id="rule" name="rule" class="form-control">
                  <option value="RULE_ADMIN" @if ($user->rule == 'RULE_ADMIN') selected @endif>@lang('admin.myinfo.admin')
                  </option>
                  <option value="RULE_USER" @if ($user->rule == 'RULE_USER') selected @endif>@lang('admin.myinfo.user')
                  </option>
                  <option value="RULE_SUPPORT" @if ($user->rule == 'RULE_SUPPORT') selected @endif>@lang('admin.myinfo.support')
                  </option>
                </select>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <label for="inputPassword1" class="col-sm-10 col-form-label">@lang('admin.myinfo.password')</label>
                  <div class="col-sm-12">
                    <input type="password" class="form-control" name="password" id="password">
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="inputPassword2" class="col-sm-10 col-form-label">@lang('admin.myinfo.confirmation')</label>
                  <div class="col-sm-12">
                    <input type="password" class="form-control" name="password_confirmation"
                      id="password_confirmation">
                  </div>
                </div>
              </div>
              <hr class="my-4" />
              <button type="submit" class="btn btn-warning">@lang('admin.myinfo.save')</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card shadow my-3">
    <div class="card-header bbcolorp">
      <h3 class="card-title">2° address</h3>
    </div>

    <form class="row g-3 p-5" action="{{ route('admin.users.update.address2', ['id' => $user->id]) }}" method="POST"
      enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <input type="hidden" value="{{ $address2->id ?? '' }}" name="id_address">
      <input type="hidden" value="{{ $user->id ?? '' }}" name="user_id">


      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('network.city')<span style="color: brown">*</span></label>
        <input type="text" class="form-control" id="city" required name="city"
          value="{{ $address2->city ?? '' }}">
      </div>
      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('network.area_residence')<span style="color: brown">*</span></label>
        <input type="text" class="form-control" id="area_residence" required name="neighborhood"
          value="{{ $address2->neighborhood ?? '' }}">
      </div>
      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('network.complement')
          <input type="text" class="form-control" id="complement" name="complement"
            value="{{ $address2->complement ?? '' }}">
      </div>
      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('network.number_resident')<span style="color: brown">*</span></label>
        <input type="text" class="form-control" id="number_residence" required name="number"
          value="{{ $address2->number ?? '' }}">
      </div>

      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('network.state')<span style="color: brown">*</span></label>
        <input type="text" class="form-control" id="state" required name="state"
          value="{{ $address2->state ?? '' }}">
      </div>
      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('network.address')<span style="color: brown">*</span></label>
        <input type="text" class="form-control" id="address1" required name="address1"
          value="{{ $address2->address ?? '' }}">
      </div>

      <div class="col-md-6">
        <label for="inputname" class="form-label">@lang('user.postcode')<span style="color: brown">*</span></label>
        <input type="text" class="form-control" id="postcode" required name="postcode"
          value="{{ $address2->zip ?? '' }}">
      </div>

      <div class="col-md-6">
        <label for="cell" class="form-label">@lang('user.cell')<span style="color: brown">*</span></label>
        <br>
        <input onkeypress="allowNumeric(event)" type="text" class="form-control" id="cell" required
          name="cell" placeholder="" value="{{ $address2->phone ?? '' }}">
      </div>

      <div class="col-md-12">
        <label for="inputAddress" class="form-label">country<span style="color: brown">*</span></label>
        <select class="form-control" id="country" required name="country" tabindex="7">
          <option></option>
          @php
            $country2 = $address2->country ?? '';
          @endphp
          @foreach ($countryes as $item)
            <option value="{{ $item->country }}" @if ($country2 == $item->country) selected="selected" @endif>
              {{ $item->country }}</option>
          @endforeach
        </select>
      </div>

      <div class="btn-group mb-2" style="max-width: 250px">
        <button type="submit" class="btn btn-primary rounded-pill" name="btn_user">update</button>
      </div>

    </form>
  </div>


  <div class="row pb-5">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <!-- <h3 class="card-title">All Requests</h3> -->
          <div class="card-tools">
            <div class="input-group input-group-sm" style="width: 150px;">
              <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
              <div class="input-group-append">
                <button type="submit" class="btn btn-default">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body table-responsive p-0" style="height: 300px;">
          <table class="table table-hover table-head-fixed text-nowrap">
            <thead>
              <tr>
              <tr>
                <th>#</th>
                <th>@lang('admin.orders.order.col1')</th>
                <th>@lang('admin.orders.order.col2')</th>
                <th>@lang('admin.orders.order.col3')</th>
                <th>@lang('admin.orders.order.col7')</th>
                <th>@lang('admin.orders.order.col8')</th>
                <th>@lang('admin.orders.order.col4')</th>
                <th>@lang('admin.orders.order.col5')</th>
              </tr>
              </tr>
            </thead>
            <tbody>
              @forelse ($ordersPackages as $orderpackage)
                <tr>
                  <th>{{ $orderpackage->id }}</th>
                  <th>{{ isset($orderpackage->user) ? $orderpackage->user->login : $orderpackage->login }}</th>
                  <td>{{ isset($orderpackage->package) ? $orderpackage->package->name : $orderpackage->name }}</td>
                  <td>{{ number_format($orderpackage->price, 2, ',', '.') }}</td>
                  <th>{{ $orderpackage->transaction_wallet }}</th>
                  <th>{{ $orderpackage->wallet }}</th>
                  <td>{{ date('d/m/Y h:i:s', strtotime($orderpackage->created_at)) }}</td>
                  <td>
                    @if ($orderpackage->status == 2)
                      <button class="btn btn-success btn-sm m-0">@lang('admin.btn.canceled')</button>
                    @elseif($orderpackage->status == 1)
                      <button class="btn btn-warning btn-sm m-0">@lang('admin.btn.paid')</button>
                    @else
                      <button class="btn btn-primary btn-sm m-0">@lang('admin.btn.pending')</button>
                    @endif
                  </td>
                </tr>
              @empty
                <p>@lang('admin.orders.order.empty')</p>
              @endforelse

            </tbody>
          </table>
        </div>
        <div class="card-footer clearfix">
        </div>
      </div>
      <a class="btn btn-warning"
        href="{{ route('admin.users.transactions', ['id' => $user->id]) }}">@lang('')Bonus Report</a>
    </div>

  </div>



@stop
@section('css')
  <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
  <style>
    @keyframes transitionView {
      0% {
        opacity: 0;
      }

      100% {
        opacity: 1;
      }
    }

    .msg_popup {
      position: fixed;
      animation: transitionView .2s ease-in-out;
      ;
      right: 25px;
      top: 105px;
      padding: 10px;
      border-radius: 8px;
      z-index: 9999;
      min-width: 150px;
    }

    .msg_success {
      color: #326a50;
      background-color: var(--bs-success-bg-subtle);
    }

    .msg_failed {
      color: #ab2e2e;
      background-color: #e4cccc;
    }

    .content_info {
      position: relative;
    }

    .close-btn {
      font-size: 27px;
      position: absolute;
      right: -8px;
      top: -40px;
      cursor: pointer;
    }

    .close-btn:hover {
      color: #414141;
    }
  </style>
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')
  <script>
    $(document).ready(function() {
      $(".search").keyup(function() {
        var searchTerm = $(".search").val();
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

        $.extend($.expr[':'], {
          'containsi': function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "")
              .toLowerCase()) >= 0;
          }
        });

        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e) {
          $(this).attr('visible', 'false');
        });

        $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e) {
          $(this).attr('visible', 'true');
        });

        var jobCount = $('.results tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

        if (jobCount == '0') {
          $('.no-result').show();
        } else {
          $('.no-result').hide();
        }
      });
    });

    $(document).ready(function() {
      $("#individual").click(function() {
        if ($("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeOut();
        }
      });
    });

    $(document).ready(function() {
      $("#corporate").click(function() {
        if (!$("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeIn();
        }
      });
    });

    $(document).ready(function() {
      $("#individual").click(function() {
        if ($("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeOut(function() {
            $(this).find('input').prop('required', false);
          });
        }
      });
    });

    $(document).ready(function() {
      $("#corporate").click(function() {
        if (!$("#box_corporate").is(':visible')) {
          $("#box_corporate").fadeIn(function() {
            $(this).find('input').prop('required', true);
          });
        }
      });
    });

    function verifyIDCorporate(clean = false) {
      const idCorporate = $('#id_corp').val()
      $('#msg_popup').html('')
      if (!clean) {
        if (idCorporate) {
          $.get(`{{ url('api/verify-corporation-authenticity/') }}/${idCorporate}`, function(response) {
            console.log(response)
            let data = response.success;
            if (data) {
              $('#content_activ_corporate').css('display', 'block')
              $('#msg_popup').html(`
                            <div class="msg_popup msg_success">
                                <div class="content_info">
                                    <span class="close-btn" onclick="verifyIDCorporate(true)"><i class="fa fa-window-close"></i></span>
                                    <div><strong>Name:</strong> ${data.trader_name}</div>
                                    <div><strong>Address:</strong> ${data.trader_address}</div>
                                    <div><strong>Country code:</strong> ${data.country_code}</div>
                                    <div><strong>Company Type:</strong> ${data.trader_company_type}</div>
                                </div>
                            </div>
                        `)
            } else {
              $('#content_activ_corporate').css('display', 'block')
              $('#msg_popup').html(`
                            <div class="msg_popup msg_failed">
                                <div class="content_info">
                                    <span class="close-btn" onclick="verifyIDCorporate(true)"><i class="fa fa-window-close"></i></span>
                                    <div>${response.error}</div>
                                </div>
                            </div>
                        `)
            }
          })
        }
      } else {
        $('#msg_popup').html('')
      }
    }
  </script>
@stop
