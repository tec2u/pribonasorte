@extends('adminlte::page')

@section('title', 'Tax')

@section('content_header')
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6" style="display: flex; gap:1rem;">
        <h1>Tax</h1>
        <div class="text-left">
          <a href="{{ route('admin.packages.edit_tax') }}" class="btn btn-lg bg-success">
            <i class="fas fa-plus-circle"></i> Edit
          </a>
        </div>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('admin.editProduct.subtitle')</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.packages.index_tax') }}">Tax</a></li>
        </ol>
      </div>
    </div>
  </div>
  @if (session()->has('message'))
    <div class="alert alert-warning" role="alert">
      {{ session('message') }}
    </div>
  @endif
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
    <div class="col-lg-12">
      <br>
      <br>

      <div class="card card-primary">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Product</th>
              <th scope="col">Country</th>
              <th scope="col">Tax</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            @if (count($products) > 0)
              @foreach ($products as $product)
                @if (isset($data) && $product->id == $data['product_id'])
                  <tr>
                    <form action="{{ route('admin.packages.getByCountry_tax') }}" method="get"
                      id="getByCountry_tax{{ $product->id }}">
                      <th scope="row">{{ $product->name }}</th>
                      <td>
                        @csrf
                        <input type="number" value="{{ $product->id }}" name="product_id" style="display: none;">
                        <select onchange="getByCountry(event)" class="form-input-finalize" name=country required>
                          {{-- <option value="" selected>{{ $data['country'] }}</option> --}}
                          @foreach ($allCountry as $item)
                            <option value="{{ $item->country }}">{{ $item->country }}</option>
                          @endforeach

                        </select>
                      </td>
                      <td>{{ $data['tax'] }}%</td>

                      <td>
                        <button type="submit" class="btn btn-sm bg-primary">Filter</button>
                      </td>
                    </form>
                  </tr>
                @else
                  <tr>
                    <form action="{{ route('admin.packages.getByCountry_tax') }}" method="get"
                      id="getByCountry_tax{{ $product->id }}">
                      <th scope="row">{{ $product->name }}</th>
                      <td>
                        @csrf
                        <input type="number" value="{{ $product->id }}" name="product_id" style="display: none;">
                        <select onchange="getByCountry(event)" class="form-input-finalize" name=country required>
                          @foreach ($allCountry as $item)
                            <option value="{{ $item->country }}">{{ $item->country }}</option>
                          @endforeach
                      </td>
                      <td>Select country</td>

                      <td>
                        <button type="submit" class="btn btn-sm bg-primary">Filter</button>
                      </td>
                    </form>
                  </tr>
                @endif
              @endforeach
            @else
              <p>0 Products</p>
            @endif
          </tbody>
        </table>
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
