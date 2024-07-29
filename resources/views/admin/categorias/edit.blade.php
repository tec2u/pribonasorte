@extends('adminlte::page')

@section('title', 'edit')

@section('content_header')

  <style>
    .nopad {
      padding-left: 0 !important;
      padding-right: 0 !important;
    }

    /*image gallery*/
    .image-checkbox {
      cursor: pointer;
      box-sizing: border-box;
      -moz-box-sizing: border-box;
      -webkit-box-sizing: border-box;
      border: 4px solid transparent;
      margin-bottom: 0;
      outline: 0;
    }

    .image-checkbox input[type="checkbox"] {
      display: none;
    }

    .image-checkbox-checked {
      border-color: #4783B0;
    }

    .image-checkbox .fa {
      position: absolute;
      color: #4A79A3;
      background-color: #fff;
      padding: 10px;
      top: 0;
      right: 0;
    }

    .image-checkbox-checked .fa {
      display: block !important;
    }
  </style>

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>edit categories <a href="{{ route('admin.packages.categorias') }}" class="btn btn-md bg-warning">
            List categories
          </a></h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('admin.editProduct.subtitle')</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.packages.index_admin') }}">Products</a></li>
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
    <div class="col-lg-12">
      <div class="card card">
        <div class="card-header">
          <h3 class="card-title">Tax</h3>
        </div>
        <form action="{{ route('admin.packages.categorias.update') }}" method="POST"
          style="display: flex; flex-direction:column; gap:.5rem; justify-content:center;">
          @csrf
          <br />
          <div class="row">
            <div class="col-md-12" style="display: flex; justify-content:start;padding-left:1rem;gap:1rem">
              <label for="commission">Name</label>
              <input type="text" class="form-control form-control-lg" id="tax" name="nome"
                style="width: 30%;height: 2rem;" value="{{ $categoria->nome }}" required>

              <input type="hidden" name="id_categoria" value="{{ $categoria->id }}">
            </div>
          </div>

          <span style="padding-left: 1rem">Products:</span>

          <div class="row" style="gap: 1rem;flex-wrap: wrap;padding:1rem;">
            @foreach ($products as $item)
              {{-- <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault{{ $item->id }}">
                <label class="form-check-label" for="flexCheckDefault{{ $item->id }}">
                  {{ $item->name }}
                </label>
              </div> --}}

              <div class="col-xs-4 col-sm-3 col-md-2 nopad text-center">
                <label class="image-checkbox">
                  <img src="/img/products/{{ $item->img_1 }}" alt="..." class="img-fluid">
                  <input type="checkbox" name="image[]" value="{{ $item->id }}"
                    @if (isset($item->estaNaCategoria) && $item->estaNaCategoria) checked @endif />
                  {{-- <i class="fa fa-check hidden"></i> --}}
                </label>
              </div>
            @endforeach

          </div>

          <button type="submit" class="btn btn-warning">Edit</button>
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

    $(".image-checkbox").each(function() {
      if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
        $(this).addClass('image-checkbox-checked');
      } else {
        $(this).removeClass('image-checkbox-checked');
      }
    });

    // sync the state to the input
    $(".image-checkbox").on("click", function(e) {
      $(this).toggleClass('image-checkbox-checked');
      var $checkbox = $(this).find('input[type="checkbox"]');
      $checkbox.prop("checked", !$checkbox.prop("checked"))

      e.preventDefault();
    });
  </script>
@stop
