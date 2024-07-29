@extends('adminlte::page')

@section('title', 'Create Tax')

@section('content_header')
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Tax</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('admin.editProduct.subtitle')</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.packages.index_tax') }}">Tax</a></li>
        </ol>
      </div>
    </div>
  </div>
@stop

@section('content')
  @include('flash::message')
  <script src="{{ asset('js/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
  <script>
    tinymce.init({
      selector: 'textarea#long_description', // Replace this CSS selector to match the placeholder element for TinyMCE
      plugins: 'code table lists',
      toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
    });
  </script>
  <script>
    tinymce.init({
      selector: 'textarea#description_fees', // Replace this CSS selector to match the placeholder element for TinyMCE
      plugins: 'code table lists',
      toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table'
    });
  </script>

  <div class="row d-flex justify-content-center ">
    <div class="col-lg-6">
      <div class="card card-primary">
        <div class="card-header">
          <h3 class="card-title">Tax</h3>
        </div>
        <form action="{{ route('admin.packages.update_tax') }}" method="POST"
          style="display: flex; flex-direction:column; gap:.5rem; justify-content:center;">
          @csrf
          <br />
          <div class="row">
            <div class="col-md-12" style="display: flex; justify-content:space-around">
              <label for="commission">Select country</label>
              <select style="width: 30%;height: 2rem;" class="form-input-finalize" name=country required>
                @foreach ($allCountry as $item)
                  <option value="{{ $item->country }}">{{ $item->country }} | {{ $item->country_code }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" style="display: flex; justify-content:space-around">
              <label for="commission">Select product</label>
              <select style="width: 30%;height: 2rem;" class="form-select" aria-label="Default select example" required
                name="product_id">
                <option selected value="">Choose</option>
                @foreach ($products as $product)
                  <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12" style="display: flex; justify-content:space-around">
              <label for="commission">Select Tax %</label>
              <input type="number" class="form-control form-control-lg" id="tax" name="value" step=".01"
                style="width: 30%;height: 2rem;" min="0" placeholder="0" value="{{ old('value') }}" required>
            </div>
          </div>
          <button type="submit" class="btn btn-success">Confirm</button>
        </form>
      </div>
    </div>
  </div>

  @if (isset($errors) && count($errors))

    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }} </li>
      @endforeach
    </ul>

  @endif

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@stop

@section('js')
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2({
        theme: "classic"
      });
    });
  </script>
  <script>
    $('#flash-overlay-modal').modal();
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
  </script>
@stop
