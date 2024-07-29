PageTutorials
@extends('layouts.header')
@section('content')

    <main id="main" class="main">
        <section id="supporttickets" class="content">
            <div class="fade">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <h1>@lang('network.tutorials')Tutorials</h1>
                            <div class="card shadow my-3">
                                <div class="card-header bbcolorp">
                                    <h3 class="card-title" style="margin-bottom: 30px;">Videos</h3>

                                </div>

                                <div class="card-body table-responsive p-0">
                                    <div class="card-body table-responsive">
                                        <div style="width: 100%; display: inline-block;">
                                            @forelse($tutorials as $tutorial)
                                            <div class="row_video">
                                                <div class="blocks_videos1">
                                                    <video style="width: 100%;" src="/videos/tutorials/{{ $tutorial->video }}" controls="controls">
                                                        {{-- <source src="/videos/tutorials/{{ $tutorial->video }}" type="video/mp4"> --}}
                                                    </video>
                                                </div>
                                                <div class="blocks_videos2">
                                                    <p class="text_video">{{ $tutorial->title }}</p>
                                                </div>
                                            </div>
                                            @empty
                                                <p>@lang('network.no_support')No support videos</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer clearfix py-3">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script>
        $('tr.header-table').click(function() {
            $(this).nextUntil('tr.header-table').css('display', function(i, v) {
                return this.style.display === 'table-row' ? 'none' : 'table-row';
            });
        });
    </script>

    <style>
        .row_video{
            width: 100%;
            margin-bottom: 20px;
            display: inline-block;
        }
        .blocks_videos1{
            width: 60%;
            float: left;
            display: inline-block;
        }
        .blocks_videos2{
            width: 40%;
            float: left;
            display: inline-block;
        }
        p.text_video{
            font-size: 25px;
            line-height: 25px;
            margin-top: 10%;
            font-weight: bold;
            margin-left: 30px;
        }

    </style>

@endsection

@section('css')
    <style>
        tr.header-table {
            display: table-row;
        }
    </style>
@stop
