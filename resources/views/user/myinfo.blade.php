@extends('layouts.header')
@section('content')
  <link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}" />
  <script src="{{ asset('js/intlTelInput.min.js') }}"></script>
  <main id="main" class="main">
    @include('flash::message')
    <section id="myinfo" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('user.my_information')</h1>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">@lang('user.info')</h3>
                </div>

                @if ($errors->has('error'))
                  <div class="alert alert-danger">
                    {{ $errors->first('error') }}
                  </div>
                @endif
                <form class="row g-3 p-5" action="{{ route('users.update', ['id' => $user->id]) }}" method="POST"
                  enctype="multipart/form-data">
                  @csrf
                  @method('PUT')
                  {{-- @php
                    dd($user->name);
                  @endphp --}}

                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.name')<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="name" required name="name" readonly
                      value="{{ $user->name }}">
                    @error('name')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.last_name')<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="last_name" required name="last_name" readonly
                      value="{{ $user->last_name }}">
                    @error('last_name')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>


                  {{-- <div class="row g-3 px-2" style="display: ;">
                    <div class="col-lg-6">
                      <input id="individual" type="radio" @if (!isset($user->corporate_nome) && !isset($user->id_corporate)) checked @endif
                        value="1" name="type_account" readonly>
                      <p> Personal Registration</p>
                    </div>

                    <div class="col-lg-6">
                      <input id="corporate" type="radio" @if (isset($user->corporate_nome) && isset($user->id_corporate)) checked @endif value="2"
                        name="type_account" readonly>
                      <p> Company Registration</p>
                    </div>
                  </div> --}}


                  @if (isset($user->corporate_nome) && isset($user->id_corporate))
                    <div id="box_corporate" style="margin-bottom: 40px;" class="row g-3 px-2">
                      <div class="col-lg-6">
                        <label for="id_corp" class="form-label teste1234">ID Corporate<span
                            style="color: brown">*</span></label>
                        <input type="text" name="campo_nao_usado" style="display:none;">
                        <input id="id_corp" type="text" class="form-control form-register" placeholder="ID Corporate"
                          name="id_corporate" value="{{ $user->id_corporate }}" autocomplete="off" readonly>
                        <span for="id_corp" class="focus-input100"></span>

                      </div>

                      <div class="col-lg-6">
                        <label for="name_corp" class="form-label teste1234">Name Company<span
                            style="color: brown">*</span></label>
                        <input type="text" name="campo_nao_usado" style="display:none;">
                        <input id="name_corp" type="text" class="form-control form-register"
                          placeholder="Name corporate" name="corporate_nome" value="{{ $user->corporate_nome }}"
                          autocomplete="off" readonly>
                        <span for="name_corp" class="focus-input100"></span>

                      </div>

                      <div class="col-lg-6">
                        <label for="tax_id" class="form-label teste1234">Tax ID<span
                            style="color: brown">*</span></label>
                        <input type="text" name="campo_nao_usado" style="display:none;">
                        <input id="tax_id" type="text" class="form-control form-register" placeholder="Tax ID"
                          name="tax_id" value="{{ $user->tax_id }}" autocomplete="off" readonly>
                        <span for="tax_id" class="focus-input100"></span>

                      </div>

                      <div class="col-lg-6">
                        <label for="vat_reg_no" class="form-label teste1234">Vat reg. no.<span
                            style="color: brown">*</span></label>
                        <input type="text" name="campo_nao_usado" style="display:none;">
                        <input id="vat_reg_no" type="text" class="form-control form-register"
                          placeholder="vat reg. no." name="vat_reg_no" value="{{ $user->vat_reg_no }}"
                          autocomplete="off" readonly>
                        <span for="vat_reg_no" class="focus-input100"></span>

                      </div>
                    </div>
                  @endif


                  @php
                    $formattedBirthday = date('Y-m-d', strtotime($user->birthday));
                  @endphp
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.birthday')<span
                        style="color: brown">*</span></label>
                    <input type="date" value="{{ $formattedBirthday }}" class="form-control" id="birthday"
                      name="birthday" readonly>
                    @error('birthday')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.city')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="city" required name="city"
                      value="@if (old('city') !== null) {{ old('city') }}@else{{ $user->city }} @endif">
                    @error('city')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.area_residence')</label>
                    <input type="text" class="form-control" id="area_residence" name="area_residence"
                      value="@if (old('area_residence') !== null) {{ old('area_residence') }}@else{{ $user->area_residence }} @endif">
                    @error('area_residence')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.complement')</label>
                    <input type="text" class="form-control" id="complement" name="complement"
                      value="{{ $user->complement }}">
                    @error('complement')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">number_residence<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="number_residence" required name="number_residence"
                      value="@if (old('number_residence') !== null) {{ old('number_residence') }}@else{{ $user->number_residence }} @endif">
                    @error('number_residence')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  {{-- <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.state')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="state" required name="state"
                      value="{{ $user->state ?? '' }}">
                    @error('state')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div> --}}
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.address1')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="address1" required name="address1"
                      value="@if (old('address1') !== null) {{ old('address1') }}@else{{ $user->address1 }} @endif">
                    @error('address1')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  {{-- <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.address2')
                      <input type="text" class="form-control" id="address2" name="address2"
                        value="@if (old('address2') !== null) {{ old('address2') }}@else{{ $user->address2 }} @endif">
                      @error('address2')
                        <span class="invalid-feedback " style="display:block" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                  </div> --}}
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.postcode')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="postcode" required name="postcode"
                      value="@if (old('postcode') !== null) {{ old('postcode') }}@else{{ $user->postcode }} @endif">
                    @error('postcode')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-6" id="USAStates" style="display: none">
                    <label for="inputname" class="form-label">@lang('user.state')<span
                        style="color: brown">*</span></label>
                    <select class="form-control" id="state" required name="">
                      <option @if ($user->state == 'none') selected="selected" @endif value="none">---</option>
                      <option @if ($user->state == 'Alabama') selected="selected" @endif value="Alabama">Alabama
                      </option>
                      <option @if ($user->state == 'Alaska') selected="selected" @endif value="Alaska">Alaska
                      </option>
                      <option @if ($user->state == 'Arizona') selected="selected" @endif value="Arizona">Arizona
                      </option>
                      <option @if ($user->state == 'Arkansas') selected="selected" @endif value="Arkansas">Arkansas
                      </option>
                      <option @if ($user->state == 'California') selected="selected" @endif value="California">
                        California</option>
                      <option @if ($user->state == 'Colorado') selected="selected" @endif value="Colorado">Colorado
                      </option>
                      <option @if ($user->state == 'Connecticut') selected="selected" @endif value="Connecticut">
                        Connecticut</option>
                      <option @if ($user->state == 'Delaware') selected="selected" @endif value="Delaware">Delaware
                      </option>
                      <option @if ($user->state == 'District of Columbia') selected="selected" @endif
                        value="District of Columbia">District of Columbia</option>
                      <option @if ($user->state == 'Florida') selected="selected" @endif value="Florida">Florida
                      </option>
                      <option @if ($user->state == 'Georgia') selected="selected" @endif value="Georgia">Georgia
                      </option>
                      <option @if ($user->state == 'Guam') selected="selected" @endif value="Guam">Guam</option>
                      <option @if ($user->state == 'Hawaii') selected="selected" @endif value="Hawaii">Hawaii
                      </option>
                      <option @if ($user->state == 'Idaho') selected="selected" @endif value="Idaho">Idaho
                      </option>
                      <option @if ($user->state == 'Illinois') selected="selected" @endif value="Illinois">Illinois
                      </option>
                      <option @if ($user->state == 'Indiana') selected="selected" @endif value="Indiana">Indiana
                      </option>
                      <option @if ($user->state == 'Iowa') selected="selected" @endif value="Iowa">Iowa</option>
                      <option @if ($user->state == 'Kansas') selected="selected" @endif value="Kansas">Kansas
                      </option>
                      <option @if ($user->state == 'Kentucky') selected="selected" @endif value="Kentucky">Kentucky
                      </option>
                      <option @if ($user->state == 'Louisiana') selected="selected" @endif value="Louisiana">Louisiana
                      </option>
                      <option @if ($user->state == 'Maine') selected="selected" @endif value="Maine">Maine
                      </option>
                      <option @if ($user->state == 'Maryland') selected="selected" @endif value="Maryland">Maryland
                      </option>
                      <option @if ($user->state == 'Massachusetts') selected="selected" @endif value="Massachusetts">
                        Massachusetts</option>
                      <option @if ($user->state == 'Michigan') selected="selected" @endif value="Michigan">Michigan
                      </option>
                      <option @if ($user->state == 'Minnesota') selected="selected" @endif value="Minnesota">Minnesota
                      </option>
                      <option @if ($user->state == 'Mississippi') selected="selected" @endif value="Mississippi">
                        Mississippi</option>
                      <option @if ($user->state == 'Missouri') selected="selected" @endif value="Missouri">Missouri
                      </option>
                      <option @if ($user->state == 'Montana') selected="selected" @endif value="Montana">Montana
                      </option>
                      <option @if ($user->state == 'Nebraska') selected="selected" @endif value="Nebraska">Nebraska
                      </option>
                      <option @if ($user->state == 'Nevada') selected="selected" @endif value="Nevada">Nevada
                      </option>
                      <option @if ($user->state == 'New Hampshire') selected="selected" @endif value="New Hampshire">New
                        Hampshire</option>
                      <option @if ($user->state == 'New Jersey') selected="selected" @endif value="New Jersey">New
                        Jersey</option>
                      <option @if ($user->state == 'New Mexico') selected="selected" @endif value="New Mexico">New
                        Mexico</option>
                      <option @if ($user->state == 'New York') selected="selected" @endif value="New York">New York
                      </option>
                      <option @if ($user->state == 'North Carolina') selected="selected" @endif value="North Carolina">North
                        Carolina</option>
                      <option @if ($user->state == 'North Dakota') selected="selected" @endif value="North Dakota">North
                        Dakota</option>
                      <option @if ($user->state == 'Northern Marianas Islands') selected="selected" @endif
                        value="Northern Marianas Islands">Northern Marianas Islands</option>
                      <option @if ($user->state == 'Ohio') selected="selected" @endif value="Ohio">Ohio</option>
                      <option @if ($user->state == 'Oklahoma') selected="selected" @endif value="Oklahoma">Oklahoma
                      </option>
                      <option @if ($user->state == 'Oregon') selected="selected" @endif value="Oregon">Oregon
                      </option>
                      <option @if ($user->state == 'Pennsylvania') selected="selected" @endif value="Pennsylvania">
                        Pennsylvania</option>
                      <option @if ($user->state == 'Puerto Rico') selected="selected" @endif value="Puerto Rico">Puerto
                        Rico</option>
                      <option @if ($user->state == 'Rhode Island') selected="selected" @endif value="Rhode Island">Rhode
                        Island</option>
                      <option @if ($user->state == 'South Carolina') selected="selected" @endif value="South Carolina">South
                        Carolina</option>
                      <option @if ($user->state == 'South Dakota') selected="selected" @endif value="South Dakota">South
                        Dakota</option>
                      <option @if ($user->state == 'Tennessee') selected="selected" @endif value="Tennessee">Tennessee
                      </option>
                      <option @if ($user->state == 'Texas') selected="selected" @endif value="Texas">Texas
                      </option>
                      <option @if ($user->state == 'Utah') selected="selected" @endif value="Utah">Utah</option>
                      <option @if ($user->state == 'Vermont') selected="selected" @endif value="Vermont">Vermont
                      </option>
                      <option @if ($user->state == 'Virginia') selected="selected" @endif value="Virginia">Virginia
                      </option>
                      <option @if ($user->state == 'Virgin Islands') selected="selected" @endif value="Virgin Islands">
                        Virgin Islands</option>
                      <option @if ($user->state == 'Washington') selected="selected" @endif value="Washington">
                        Washington</option>
                      <option @if ($user->state == 'West Virginia') selected="selected" @endif value="West Virginia">West
                        Virginia</option>
                      <option @if ($user->state == 'Wisconsin') selected="selected" @endif value="Wisconsin">Wisconsin
                      </option>
                      <option @if ($user->state == 'Wyoming') selected="selected" @endif value="Wyoming">Wyoming
                      </option>
                    </select>
                    @error('state')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-12">
                    <label for="inputAddress" class="form-label">@lang('user.country')<span
                        style="color: brown">*</span></label>
                    <select class="form-control" id="country" required name="country" tabindex="7">
                      @foreach ($allCountry as $item)
                        <option value="{{ $item->country }}"
                          @if ($user->country == $item->country) selected="selected" @endif>
                          {{ $item->country }}</option>
                      @endforeach


                    </select>
                    @error('country')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-12">
                    <label for="gender" class="form-label">@lang('user.gender')<span
                        style="color: brown">*</span></label>
                    <select id="gender" required name="gender" class="form-control">
                      <option value="F" @if ($user->gender == 'F') selected @endif>
                        @lang('admin.myinfo.female')</option>
                      <option value="M" @if ($user->gender == 'M') selected @endif>
                        @lang('admin.myinfo.male')</option>
                    </select>
                    @error('gender')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-12">
                    <label for="inputemail" class="form-label">@lang('user.email')<span
                        style="color: brown">*</span></label>
                    <input type="email" class="form-control" id="email" readonly required name="email"
                      value="@if (old('email') !== null) {{ old('email') }}@else{{ $user->email }} @endif">
                    @error('email')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                  <div class="col-md-6">
                    <label for="telephone" class="form-label">@lang('user.telephone')</label>
                    <br>
                    <input onkeypress="allowNumeric(event)" type="text" class="form-control" id="telephone"
                      name="telephone" placeholder=""
                      value="+@if (old('telephone') !== null) {{ old('telephone') }}@else{{ $user->telephone }} @endif">
                    @error('telephone')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <input type="hidden" name="countryCodePhone" id="countryCodePhone"
                      value="@if (old('countryCodePhone') !== null) {{ old('countryCodePhone') }}@else{{ $user->country_code_fone }} @endif ">
                  </div>
                  <div class="col-md-6">
                    <label for="cell" class="form-label">@lang('user.cell')<span
                        style="color: brown">*</span></label>
                    <br>
                    <input onkeypress="allowNumeric(event)" type="text" class="form-control" id="cell"
                      required name="cell" placeholder=""
                      value="+@if (old('cell') !== null) {{ old('cell') }}@else{{ $user->cell }} @endif">
                    @error('cell')
                      <span class="invalid-feedback " style="display:block" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    <input type="hidden" name="countryCodeCell" id="countryCodeCell"
                      value="@if (old('countryCodeCell') !== null) {{ old('countryCodeCell') }}@else{{ $user->country_code_cel }} @endif">
                  </div>

                  {{-- @if ($user->wallet()->first() == null)
                    <div class="col-md-12">
                      <label for="wallet" class="form-label">@lang('user.wallet')</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text">USDT TRC20</span>
                        </div>
                        <input type="text" class="form-control" id="wallet" name="wallet"
                          value="{{ empty($user->wallet()->orderBy('id', 'desc')->first())? '': $user->wallet()->orderBy('id', 'desc')->first()->wallet }}">
                        @error('wallet')
                          <span class="invalid-feedback " style="display:block" role="alert">
                            <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                      </div>
                    </div>
                  @else
                    <div class="col-md-12">
                      <h4>Need to change your wallet?</h4>
                      <input type="radio" id="wallet" name="wallet" value="change_wallet" />
                      <label for="wallet" class="form-label">Yes</label>
                    </div>
                  @endif --}}


                  <div class="col-md-6">
                    <label for="image" class="form-label">@lang('user.profile_picture')</label>
                    <input type="file" name="image" id="image" class="form-control">
                  </div>

                  <div class="col-md-4">
                    @if ($user->country != 'US')
                      <span onclick="authChange()" class="btn btn-primary rounded-pill mb-3"
                        id="change_bt">@lang('user.change')</span>
                    @endif
                    @if ($user->country == 'US')
                      For US residents, our legal team is working very hard in order to comply with
                      the recent law changes is the US.
                      We will keep you informed of this process.
                    @endif

                    <div class="col-md-12" id="password" style="display: none">
                      <label for="password" class="form-label">@lang('user.confirm_password')</label>
                      <input type="password" class="form-control" required name="password" placeholder="">
                    </div>
                    <br>
                    <div class="col-md-12">

                      <div class="input-group-append" style="width: 900px">
                        <div class="btn-group me-2">
                          <button type="submit" class="btn btn-primary rounded-pill" name="btn_user"
                            id="confirm_password" style="display: none"; onclick="setValueUpdate(this)">Update Personal
                            Data</button>
                        </div>

                        {{-- <div class="btn-group me-2">
                          <button type="submit" class="btn btn-primary rounded-pill" name="btn_user"
                            id="confirm_password_api" style="display: none" onclick="setValueUpdateApi(this)"
                            @if ($user->contact_id) @disabled(true) @endif>Update
                            Personal
                            Data For APIs
                          </button>
                        </div> --}}

                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="card shadow my-3">
                <div class="card-header bbcolorp">
                  <h3 class="card-title">2° address</h3>
                </div>

                <form class="row g-3 p-5" action="{{ route('users.update.address2', ['id' => $user->id]) }}"
                  method="POST" enctype="multipart/form-data">
                  @csrf
                  @method('PUT')

                  <input type="hidden" value="{{ $address2->id ?? '' }}" name="id_address">

                  <div class="col-md-6">
                    <label for="inputname" class="form-label">First Name<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="first_name" required name="first_name"
                      value="{{ $address2->first_name ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">Last Name<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="last_name" required name="last_name"
                      value="{{ $address2->last_name ?? '' }}">
                  </div>

                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.city')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="city" required name="city"
                      value="{{ $address2->city ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.area_residence')</label>
                    <input type="text" class="form-control" id="area_residence" name="neighborhood"
                      value="{{ $address2->neighborhood ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.complement')
                      <input type="text" class="form-control" id="complement" name="complement"
                        value="{{ $address2->complement ?? '' }}">
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.number_resident')<span
                        style="color: brown">*</span></label>
                    <input type="number" class="form-control" id="number_residence" required name="number"
                      value="{{ $address2->number ?? '' }}">
                  </div>

                  {{-- <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.state')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="state" required name="state"
                      value="{{ $address2->state ?? '' }}">
                  </div> --}}
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('network.address')<span
                        style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="address1" required name="address1"
                      value="{{ $address2->address ?? '' }}">
                  </div>

                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.postcode')<span
                        style="color: brown">*</span></label>
                    <input type="number" class="form-control" id="postcode" required name="postcode"
                      value="{{ $address2->zip ?? '' }}">
                  </div>

                  <div class="col-md-6">
                    <label for="cell" class="form-label">@lang('user.cell')<span
                        style="color: brown">*</span></label>
                    <br>
                    <input onkeypress="allowNumeric(event)" type="number" class="form-control" id="cell"
                      required name="cell" placeholder="" value="{{ $address2->phone ?? '' }}">
                  </div>

                  <div class="col-md-12">
                    <label for="inputAddress" class="form-label">@lang('user.country')<span
                        style="color: brown">*</span></label>
                    <select class="form-control" id="country" required name="country" tabindex="7">
                      {{-- <option>@lang('netword.select_country')</option> --}}
                      @php
                        $country2 = $address2->country ?? '';
                      @endphp
                      @foreach ($allCountry as $item)
                        <option value="{{ $item->country }}"
                          @if ($country2 == $item->country) selected="selected" @endif>
                          {{ $item->country }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="btn-group mb-2" style="max-width: 250px">
                    <button type="submit" class="btn btn-primary rounded-pill"
                      name="btn_user">@lang('netword.update')</button>
                  </div>

                </form>
              </div>

              @if (isset($user->corporate_nome) && isset($user->id_corporate))
                <div class="card shadow my-3">
                  <div class="card-header bbcolorp">
                    <h3 class="card-title">Billing address</h3>
                  </div>

                  <form class="row g-3 p-5" action="{{ route('users.update.billing', ['id' => $user->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" value="{{ $billing->id ?? '' }}" name="id_address">

                    <div class="col-md-6">
                      <label for="inputname" class="form-label">City<span style="color: brown">*</span></label>
                      <input type="text" class="form-control" id="city" required name="city"
                        value="{{ $billing->city ?? '' }}">
                    </div>

                    <div class="col-md-6">
                      <label for="inputname" class="form-label">number_residence<span
                          style="color: brown">*</span></label>
                      <input type="text" class="form-control" id="number_residence" required name="number"
                        value="{{ $billing->number_residence ?? '' }}">
                    </div>

                    {{-- <div class="col-md-6">
                      <label for="inputname" class="form-label">state<span style="color: brown">*</span></label>
                      <input type="text" class="form-control" id="state" required name="state"
                        value="{{ $billing->state ?? '' }}">
                    </div> --}}
                    <div class="col-md-6">
                      <label for="inputname" class="form-label">Address<span style="color: brown">*</span></label>
                      <input type="text" class="form-control" id="address1" required name="address1"
                        value="{{ $billing->address ?? '' }}">
                    </div>

                    <div class="col-md-6">
                      <label for="inputname" class="form-label">@lang('user.postcode')<span
                          style="color: brown">*</span></label>
                      <input type="text" class="form-control" id="postcode" required name="postcode"
                        value="{{ $billing->zip ?? '' }}">
                    </div>

                    <div class="col-md-6">
                      <label for="inputAddress" class="form-label">@lang('user.country')<span
                          style="color: brown">*</span></label>
                      <select class="form-control" id="country" required name="country" tabindex="7">
                        {{-- <option>Select Country</option> --}}
                        @php
                          $country2 = $billing->country ?? '';
                        @endphp
                        @foreach ($allCountry as $item)
                          <option value="{{ $item->country }}"
                            @if ($country2 == $item->country) selected="selected" @endif>
                            {{ $item->country }}</option>
                        @endforeach
                      </select>
                    </div>

                    <div class="btn-group mb-2" style="max-width: 250px">
                      <button type="submit" class="btn btn-primary rounded-pill" name="btn_user">Update Adress
                        Billing</button>
                    </div>

                  </form>
                </div>
              @endif

            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script>
    function authChange() {
      document.querySelector('#password').style.display = 'block';
      document.querySelector('#confirm_password').style.display = 'block';
      document.querySelector('#confirm_password_api').style.display = 'block';
      document.querySelector('#change_bt').style.display = 'none';
    }

    var input = document.querySelector("#telephone");
    let codePhone = window.intlTelInput(input, {
      separateDialCode: true
    });
    input.addEventListener("countrychange", function() {
      console.log(codePhone.getSelectedCountryData());
      document.querySelector("#countryCodePhone").value = codePhone.getSelectedCountryData().dialCode;
    });

    var inputCell = document.querySelector("#cell");
    let codeCell = window.intlTelInput(inputCell, {
      separateDialCode: true
    });
    inputCell.addEventListener("countrychange", function() {
      console.log(codeCell.getSelectedCountryData());
      document.querySelector("#countryCodeCell").value = codeCell.getSelectedCountryData().dialCode;
    });

    var inputStates = document.querySelector("#USAStates");
    document.querySelector("#country").addEventListener("change", function(event) {
      if (event.target.value == 'US') {
        inputStates.style.display = "block";
      } else {
        inputStates.style.display = "none";
        document.querySelector("#state").value = "none";
      }
    });

    if (document.querySelector("#country").value == "US") {
      inputStates.style.display = "block";
    }

    function setValueUpdate(e) {
      e.value = "update_user"
    }

    function setValueUpdateApi(e) {
      e.value = "update_user_api"
    }
  </script>
  <script>
    function allowNumeric(e) {
      var code = ('charCode' in e) ? e.charCode : e.keyCode;
      if (!(code > 47 && code < 58)) { // numeric (0-9)
        e.preventDefault();
      }
    }
  </script>
  <script>
    function allowAlphaNumeric(e) {
      var code = ('charCode' in e) ? e.charCode : e.keyCode;
      if (!(code > 47 && code < 58) && // numeric (0-9)
        !(code > 64 && code < 91) && // upper alpha (A-Z)
        !(code > 96 && code < 123)) { // lower alpha (a-z)
        e.preventDefault();
      }
    }

    // $(document).ready(function() {
    //   $("#individual").click(function() {
    //     if ($("#box_corporate").is(':visible')) {
    //       $("#box_corporate").fadeOut();
    //     }
    //   });
    // });

    // $(document).ready(function() {
    //   $("#corporate").click(function() {
    //     if (!$("#box_corporate").is(':visible')) {
    //       $("#box_corporate").fadeIn();
    //     }
    //   });
    // });

    // $(document).ready(function() {
    //   $("#individual").click(function() {
    //     if ($("#box_corporate").is(':visible')) {
    //       $("#box_corporate").fadeOut(function() {
    //         $(this).find('input').prop('required', false);
    //       });
    //     }
    //   });
    // });

    // $(document).ready(function() {
    //   $("#corporate").click(function() {
    //     if (!$("#box_corporate").is(':visible')) {
    //       $("#box_corporate").fadeIn(function() {
    //         $(this).find('input').prop('required', true);
    //       });
    //     }
    //   });
    // });
  </script>
@endsection
