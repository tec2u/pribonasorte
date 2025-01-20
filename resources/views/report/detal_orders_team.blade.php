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
              <h1>Detal Order Team</h1>
              <div class="card shadow my-3" style="padding: 30px;">

                <div class="row" style="margin-bottom: 20px;">
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">Name<span style="color: brown">*</span></label>
                    <input type="name" class="form-control" id="name" readonly required name="name"
                      value="@if (old('name') !== null) {{ old('name') }}@else{{ $user->name }} @endif">
                  </div>

                  <div class="col-md-6">
                    <label for="inputname" class="form-label">E-mail<span style="color: brown">*</span></label>
                    <input type="name" class="form-control" id="last_name" readonly required name="last_name"
                      value="@if (old('last_name') !== null) {{ old('last_name') }}@else{{ $user->email }} @endif">
                  </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">Product<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="city" required name="city"
                      value="{{ $product->name }}">
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">Price Order<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="city" required name="city"
                      value="@if (old('city') !== null) {{ old('city') }}@else{{ $orders->total }} @endif">
                  </div>
                </div>
                <div class="row" style="margin-bottom: 20px;">
                    <div class="col-md-6">
                      <label for="inputname" class="form-label">Total VAT<span style="color: brown">*</span></label>
                      <input type="text" class="form-control" id="city" required name="city"
                        value="{{ $orders->total_vat }}">
                    </div>
                  </div>

                <div class="row" style="margin-top: 20px;">
                    <a href="{{ route('reports.teamorders') }}"><button style="width: 200px; height: 40px; border-radius: 10px;">Retunr Reports</button></a>
                  </div>
                </div>
                  {{-- <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.address1')<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="address1" required name="address1"
                      value="@if (old('address1') !== null) {{ old('address1') }}@else{{ $user->address1 }} @endif">
                  </div>
                  <div class="col-md-6">
                    <label for="inputname" class="form-label">@lang('user.address2')<span style="color: brown">*</span></label>
                    <input type="text" class="form-control" id="address2" required name="address2"
                      value="@if (old('address2') !== null) {{ old('address2') }}@else{{ $user->address2 }} @endif">
                  </div> --}}
                </form>
              </div>
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
  </script>
@endsection
