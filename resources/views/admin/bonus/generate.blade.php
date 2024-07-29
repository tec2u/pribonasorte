@extends('adminlte::page')

@section('title', 'Bonus')
<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content_header')
  <div class="alignHeader">
    <h4>Bonus</h4>
  </div>
  <i class="fa fa-home ml-3"></i> - generate
@stop

@section('content')
  <style>
    #loading {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      z-index: 9999;
    }
  </style>

  <div class="card">
    <div class="card-body">
      <div class="container">
        {{-- <div class="row justify-content-center"> --}}
        {{-- <div class="col-12 col-lg-10 col-xl-8 mx-auto"> --}}

        <div style="display: flex; flex-direction: row;gap:1rem; flex-wrap:wrap;">
          @foreach ($methods as $id => $item)
            @if ($id == 0)
              <button class="btn btn-primary bonus-btn" id="function-{{ $id }}"
                style="width: fit-content;background-color: #490d55;"
                onclick="executeBonus({{ $id }}, '{{ $item }}')">{{ ++$id }}° - Run
                {{ $item }}</button>
            @else
              <button class="btn btn-primary bonus-btn" id="function-{{ $id }}"
                style="width: fit-content;background-color: #490d55;"
                onclick="executeBonus({{ $id }}, '{{ $item }}')" disabled>{{ ++$id }}° - Run
                {{ $item }}</button>
            @endif
          @endforeach
        </div>

        <span>Log:</span>
        <br>
        <div id="log"
          style="width: 100%; height: 250px; background-color: aliceblue;padding:1rem; border-radius:15px; overflow-y: scroll">

        </div>

        <div id="loading" style="display: none;">
          <img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="Loading...">
          <!-- Coloque aqui o caminho da sua imagem de loading -->
        </div>
      </div>
    </div>

  </div>
  </div>
  </div>
@stop
@section('css')
  <link rel="stylesheet" href="{{ asset('/css/admin_custom.css') }}">
@stop
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
@section('js')

  <script>
    function executeBonus(id, bonus) {
      // console.log(id, bonus);

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#loading').show();

      $.ajax({
        type: 'POST',
        url: "{{ route('admin.bonus.generateAjax') }}", // Substitua pela URL do seu endpoint
        data: {
          method: bonus
        }, // Serializa os dados do formulário
        success: function(response) {
          // console.log(response);
          document.getElementById('log').innerText = response;
          // Faça algo com a resposta (ex: exibir uma mensagem de sucesso)

          $('#loading').hide();

          $('#function-' + id).prop('disabled', true);
          $('#function-' + id).css('backgroundColor', "green");


          $('#function-' + (id + 1)).prop('disabled', false);

        },
        error: function(error) {
          console.log(error);
          $('#function-' + id).css('backgroundColor', "#eb4444");
          $('#loading').hide();
        }
      });

    }
  </script>
  <script>
    $(document).ready(function() {
      $(".search").keyup(function() {
        var searchTerm = $(".search").val();
        var listItem = $('.results tbody').children('tr');
        var searchSplit = searchTerm.replace(/ /g, "'):containsi('")

        $.extend($.expr[':'], {
          'containsi': function(elem, i, match, array) {
            return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "")
              .toLowerCase()) >= 0;
          }
        });

        $(".results tbody tr").not(":containsi('" + searchSplit + "')").each(function(e) {
          $(this).attr('visible', 'false');
        });

        $(".results tbody tr:containsi('" + searchSplit + "')").each(function(e) {
          $(this).attr('visible', 'true');
        });

        var jobCount = $('.results tbody tr[visible="true"]').length;
        $('.counter').text(jobCount + ' item');

        if (jobCount == '0') {
          $('.no-result').show();
        } else {
          $('.no-result').hide();
        }
      });
    });
  </script>
@stop
