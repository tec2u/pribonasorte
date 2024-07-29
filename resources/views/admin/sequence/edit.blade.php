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
      /* display: block !important; */
    }

    .ghost {
      opacity: 0.4;
    }

    .list-group {
      display: flex;
      flex-direction: row;
      gap: 1rem;
      flex-wrap: wrap;
    }
  </style>

  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>edit sequence <a href="{{ route('admin.packages.index_admin') }}" class="btn btn-md bg-warning">
            Products
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
          <h3 class="card-title">Sequence</h3>
        </div>
        <form action="{{ route('admin.packages.sequence.update') }}" method="POST" id="orderForm"
          style="display: flex; flex-direction:column; gap:.5rem; justify-content:center;">
          @csrf
          <br />
          <input type="hidden" id="itemOrder" name="itemOrder">
          <span style="padding-left: 1rem">Products:</span>


          <div class="row" style="gap: 1rem;flex-wrap: wrap;padding:1rem;">
            <div id="demo" class="row">

              <div id="items-1" class="list-group col">
                @foreach ($products as $item)
                  <div id="item-{{ $item->id }}" data-id="{{ $item->id }}"
                    class="list-group-item nested-1 col-xs-4 col-sm-3 col-md-2 nopad text-center">
                    <label class="image-checkbox">
                      <img src="/img/products/{{ $item->img_1 }}" alt="..." class="img-fluid">
                    </label>
                  </div>
                @endforeach
              </div>
            </div>
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

  <script src="https://unpkg.com/sortablejs-make/Sortable.min.js"></script>
  <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script>


  <script src="https://raw.githack.com/SortableJS/Sortable/master/Sortable.js"></script>
  <script>
    // List 1
    $('#items-1').sortable({
      group: 'list',
      animation: 200,
      ghostClass: 'ghost',
      onSort: function() {
        var itemIDs = $('#items-1').sortable('toArray', {
          attribute: 'data-id'
        });
        $('#itemOrder').val(itemIDs.join(','));
        reportActivity();
      }
    });

    // Arrays of "data-id"
    $('#get-order').click(function() {
      var sort1 = $('#items-1').sortable('toArray');

    });

    $('#orderForm').submit(function(event) {
      event.preventDefault(); // Previne a submissão padrão do formulário
      var order = $('#itemOrder').val();
      if (!order) {
        alert("No changes")
        return
      }
      $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: {
          itemOrder: order,
          _token: $('input[name="_token"]').val() // Envia também o token CSRF
        },
        success: function(response) {
          alert('Success')
        },
        error: function(xhr) {
          alert('Failed')
          // console.log('Erro ao salvar a ordem:', xhr.responseText);

        }
      });
    });

    // Report when the sort order has changed
    function reportActivity() {
      // console.log('The sort order has changed');
    };
  </script>

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
