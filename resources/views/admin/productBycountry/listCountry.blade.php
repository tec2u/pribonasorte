@extends('adminlte::page')
@section('title', 'Country')

@section('content_header')
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Select Country</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('admin.product.subtitle')</a></li>
          <li class="breadcrumb-item active">Country</li>
        </ol>
      </div>
    </div>
  </div>
@stop

@section('content')
  @include('flash::message')
  <div class="card card-solid">

    <style>
      .availability {
        background-color: green;
        color: white;
        width: 60%;
        height: 27px;
        text-align: center;
        z-index: 1;
        border-radius: 8px;
        font-size: 18px;
      }
    </style>
    <div class="card-body pb-0" style="display: flex; flex-direction: column; gap:.2rem">
      @foreach ($country as $item)
        <a href="{{ route('admin.packages.filterBycountry.country.select', $item->id) }}" class="btn btn-lg bg-light"
          style="color: black !important">
          <i class="fas fa-plus-circle"></i> {{ $item->country }} -
          @if (isset($item->quant))
            {{ $item->quant }}
          @else
            Not choosed
          @endif
        </a>
      @endforeach
    </div>
    <div class="row d-flex justify-content-center ">

    </div>
  </div>
@stop

@section('js')
  <script>
    $('#flash-overlay-modal').modal();
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
  </script>
@stop
