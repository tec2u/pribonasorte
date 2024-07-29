@extends('adminlte::page')

@section('title', 'Create Post')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Create Question</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">@lang('admin.editProduct.subtitle')</a></li>
                    <li class="breadcrumb-item active">Create Question</li>
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
                    <h3 class="card-title">Question Create</h3>
                </div>
                <form action="{{ route('admin.faq.CreateFaq') }}" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        @csrf
                        <div class="form-group">
                            <label for="name">Question</label>
                            <input type="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                id="name" name="question" placeholder="Enter Question" value="{{ old('name') }}">
                            @error('name')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="category">Category</label>
                            <input type="category" class="form-control form-control-lg @error('category') is-invalid @enderror"
                                id="category" name="category" placeholder="Enter Category" value="{{ old('categorye') }}">
                            @error('category')
                                <span class="error invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col md 12">
                            <div class="form-group">
                                <label for="long_description">Response</label>
                                <textarea id="long_description" name="response">

                                </textarea>
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
