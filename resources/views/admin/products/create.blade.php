@extends('adminlte::page')

@section('title', 'Create Products')

@section('content_header')
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>@lang('admin.editProduct.titlecreate')</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('admin.editProduct.subtitle')</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">@lang('admin.editPackage.subtitle2')</a></li>
          <li class="breadcrumb-item active">@lang('admin.editProduct.titlecreate')</li>
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
          <h3 class="card-title">@lang('admin.editProduct.data')</h3>
        </div>
        <form action="{{ route('admin.packages.store_admin') }}" method="POST" enctype="multipart/form-data">
          <div class="card-body">
            @csrf
            <div class="form-group">
              <label for="name">@lang('admin.editProduct.edit.name')</label>
              <input type="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                id="name" name="name" placeholder="@lang('admin.editProduct.edit.entername')" value="{{ old('name') }}">
              @error('name')
                <span class="error invalid-feedback">{{ $message }}</span>
              @enderror
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="price">Retail @lang('admin.editProduct.edit.price')</label>
                  <input type="number" class="form-control form-control-lg @error('price') is-invalid @enderror"
                    id="price" name="price" step=".01" placeholder="9.99" value="{{ old('price') }}">
                  @error('price')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="premium_price">Premium @lang('admin.editProduct.edit.price')</label>
                  <input type="number" class="form-control form-control-lg @error('premium_price') is-invalid @enderror"
                    id="premium_price" name="premium_price" step=".01" placeholder="9.99"
                    value="{{ old('premium_price') }}">
                  @error('premium_price')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="row">

              <div class="col-md-4">
                <div class="form-group">
                  <label for="backoffice_price">Backoffice price</label>
                  <input type="number"
                    class="form-control form-control-lg @error('backoffice_price') is-invalid @enderror"
                    id="backoffice_price" name="backoffice_price" step=".01" placeholder="9.99" required>
                  @error('backoffice_price')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="type">Stock</label>
                  <input type="number" class="form-control form-control-lg @error('stock') is-invalid @enderror"
                    id="stock" name="stock" step="1" placeholder="0" value="{{ old('stock') }}">
                  @error('rule')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>

              </div>
            </div>


            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="commission">@lang('admin.editProduct.edit.comission')</label>
                  <input type="number" class="form-control form-control-lg @error('commission') is-invalid @enderror"
                    id="commission" name="commission" step=".01" placeholder="9.99" value="{{ old('commission') }}"
                    disabled>
                  @error('commission')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>

              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label for="type">@lang('admin.editProduct.edit.type')</label>
                  <select class="form-control form-control-lg @error('type') is-invalid @enderror" name="type"
                    id="type" onchange="changeAdditionalArchives(this)">
                    <option value="fisico">Físico</option>
                    <option value="virtual">Virtual</option>
                    <option value="curso">Curso</option>
                  </select>
                  @error('rule')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
              <div class="col-md-4" style="display: none;" id="documento">
                <div class="form-group">
                  <label for="id_additional_archive">Arquivo complementar</label>
                  <select class="form-control form-control-lg @error('id_additional_archive') is-invalid @enderror" name="id_additional_archive">
                    <option value="">(selecione)</option>
                    @foreach($documents as $document)
                        <option value="{{ $document->id }}">{{ $document->title }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-4" style="display: none;" id="video">
                <div class="form-group">
                  <label for="id_additional_archive">Arquivo complementar</label>
                  <select class="form-control form-control-lg @error('id_additional_archive') is-invalid @enderror" name="id_additional_archive">
                    <option value="">(selecione)</option>
                    @foreach($videos as $video)
                        <option value="{{ $video->id }}">{{ $video->title }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

            </div>

            <div class="row">
              <div class="col-md-3">
                <div class="form-group">
                  <label for="price">Width(cm)</label>
                  <input type="number" class="form-control form-control-lg @error('width') is-invalid @enderror"
                    id="width" name="width" placeholder="0" value="{{ old('width') }}" required>
                  @error('width')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="price">Height(cm)</label>
                  <input type="number" class="form-control form-control-lg @error('height') is-invalid @enderror"
                    id="height" name="height" placeholder="0" value="{{ old('height') }}" required>
                  @error('height')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="price">Depth(cm)</label>
                  <input type="number" class="form-control form-control-lg @error('depth') is-invalid @enderror"
                    id="depth" name="depth" placeholder="0" value="{{ old('depth') }}" required>
                  @error('depth')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <label for="price">Weight(kg)</label>
                  <input type="number" class="form-control form-control-lg @error('weight') is-invalid @enderror"
                    id="weight" name="weight" step="0.001" placeholder="0" value="{{ old('weight') }}"
                    required>
                  @error('weight')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>

            </div>

            <div class="row" style="display: flex;">
              <label for="price">Type:</label>
              <div class="form-check ml-4" onclick="esconder()">
                <input class="form-check-input" type="radio" name="kit" id="exampleRadios1" value="0"
                  checked>
                <label class="form-check-label" for="exampleRadios1">
                  Normal product
                </label>
              </div>
              <div class="form-check ml-4" onclick="aparecer()">
                <input class="form-check-input" type="radio" name="kit" id="exampleRadios2" value="1">
                <label class="form-check-label" for="exampleRadios2">
                  Add in cart
                </label>
              </div>
              <div class="form-check ml-4" onclick="aparecer()">
                <input class="form-check-input" type="radio" name="kit" id="exampleRadios3" value="2">
                <label class="form-check-label" for="exampleRadios3">
                  Kit products
                </label>
              </div>
            </div>

            <div class="row" id="produtos-escolha" style="display: none;">
              @foreach ($allProducts as $item)
                {{-- @foreach ($separado as $key) --}}
                <div class="col-md-6">
                  <div class="form-group row">
                    <label for="price">{{ $item->name }}</label>
                    <input type="number" class="form-control form-control-lg" id="product-{{ $item->id }}"
                      name="product-{{ $item->id }}" step="1" placeholder="0" value="0">
                  </div>
                </div>
                {{-- @endforeach --}}
              @endforeach
            </div>

            <div class="row" style="display: flex;">
              <label for="price">Availability:</label>
              <div class="form-check ml-4">
                <input class="form-check-input" type="radio" name="availability" id="availability3" value="both"
                checked>
                <label class="form-check-label" for="availability3">
                  Both
                </label>
              </div>
              <div class="form-check ml-4">
                <input class="form-check-input" type="radio" name="availability" id="availability1" value="internal">
                <label class="form-check-label" for="availability1">
                  Internal
                </label>
              </div>
              <div class="form-check ml-4">
                <input class="form-check-input" type="radio" name="availability" id="availability2" value="external">
                <label class="form-check-label" for="availability2">
                  External
                </label>
              </div>
            </div>

            <div class="col md 12">
              <div class="form-group">
                <label for="long_description">@lang('admin.editProduct.edit.long')</label>
                <textarea id="long_description" name="description">

                        </textarea>
              </div>
            </div>

            <div class="col md 12">
              <div class="form-group">
                <label for="description_fees">@lang('admin.editProduct.edit.longfees')</label>
                <textarea id="description_fees" name="resume">

                        </textarea>
              </div>
            </div>

            <div class="row">
              <div class="col md 12">
                <div class="form-group">
                  <label for="activated">@lang('admin.editProduct.edit.active')</label>
                  <select class="form-control form-control-lg @error('password') is-invalid @enderror" name="activated"
                    id="activated">
                    <option value="1">@lang('admin.editProduct.edit.activetype.activated')</option>
                    <option value="0">@lang('admin.editProduct.edit.activetype.desactivated')</option>
                  </select>
                  @error('rule')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>

            <div class="col md 12">
              <div class="col">
                <div class="col md 6">
                  <div class="form-group">
                    <label for="image">@lang('admin.editProduct.edit.image1')</label>
                    <input type="file" name="img_1" id="image"
                      class="form-control form-control-lg @error('image.*') is-invalid @enderror" required>
                    @error('image.*')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="col md 6">
                  <div class="form-group">
                    <label for="image2">@lang('admin.editProduct.edit.image2')</label>
                    <input type="file" name="img_2" id="image2"
                      class="form-control form-control-lg @error('image2.*') is-invalid @enderror">
                    @error('image2.*')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="col md 6">
                  <div class="form-group">
                    <label for="image3">@lang('admin.editProduct.edit.image3')</label>
                    <input type="file" name="img_3" id="image3"
                      class="form-control form-control-lg @error('image3.*') is-invalid @enderror">
                    @error('image3.*')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="col md 6">
                  <div class="form-group">
                    <label for="image4">@lang('admin.editProduct.edit.image3')</label>
                    <input type="file" name="video" id="image4"
                      class="form-control form-control-lg @error('image4.*') is-invalid @enderror">
                    @error('image4.*')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col md 12">
                <div class="form-group">
                  <label style="display: none;" for="plan_id">Plan ID</label>
                  <input type="hidden" class="form-control form-control-lg @error('plan_id') is-invalid @enderror"
                    id="plan_id" name="plan_id">
                  @error('plan_id')
                    <span class="error invalid-feedback">{{ $message }}</span>
                  @enderror
                </div>
              </div>
            </div>
          </div>
          <div class="card-footer text-right">
            <button type="submit" class="btn brn-lager btn-success">@lang('admin.editProduct.edit.register')</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  {{-- @if (isset($errors) && count($errors))

<ul>
    @foreach ($errors->all() as $error)
        <li>{{ $error }} </li>
    @endforeach
</ul>

@endif --}}

@stop

@section('css')
  <link rel="stylesheet" href="/css/admin_custom.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@stop

@section('js')

  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    function changeAdditionalArchives(element) {
        switch (element.value) {
            case 'curso':
                $('#video').css('display', 'block')
                $('#documento').css('display', 'none')
                break;
            case 'virtual':
                $('#video').css('display', 'none')
                $('#documento').css('display', 'block')
                break;
            default:
                $('#video').css('display', 'none')
                $('#documento').css('display', 'none')
                break;
        }
    }
    $(document).ready(function() {
      $('.select2').select2({
        theme: "classic"
      });
    });

    function aparecer() {
      let prods = document.querySelector('#produtos-escolha').style.display = 'flex';
    }

    function esconder() {
      let prods = document.querySelector('#produtos-escolha').style.display = 'none';
    }
  </script>
  <script>
    $('#flash-overlay-modal').modal();
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
  </script>
@stop
