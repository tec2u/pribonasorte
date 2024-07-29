@extends('layouts.header')
@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js"
    integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    .hiddenRow {
      padding: 0 !important;
    }
  </style>

  <main id="main" class="main">
    <section id="supporttable" class="content">
      <div class="fade">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <h1>@lang('network.library')</h1>
              @if ($library->count() > 0)
                <div class="card shadow my-3">
                  <table class="table table-hover text-nowrap">
                    <thead>
                      <tr>
                        <th>@lang('network.title')</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($library as $item)
                        <tr>
                          <th>{{ $item->title }}</th>
                          <th>
                            <a href="{{ route('download.library', $item->id) }}">
                              <button type="button" class="btn btn-primary">Download</button>
                            </a>
                          </th>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  <div class="card-footer clearfix py-3">
                    {{ $library->links() }}
                  </div>
                </div>
              @else
                <p>no pdf</p>
              @endif
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection
@section('css')
@stop
