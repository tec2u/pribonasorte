@extends('layouts.header')
@section('content')
<main id="main" class="main">
    @include('flash::message')
    <section id="withdrawrequest" class="content">

        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
            @endforeach
        </ul>
        @endif


        <div class="fade">
            <div class="container-fluid">
                <div class="row h-100">
                    <div class="col-12">
                        <h1>Pagamento</h1>
                        <div class="card shadow my-3 h-100">
                            <div class="card-header bbcolorp">
                                <h3 class="card-title">Pagamento ordem N {{ $order->number_order }}</h3>
                            </div>
                            <div class="card-body">
                                <iframe src="{{ $order->payment_link }}" frameborder="0" class="h-100 w-100"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
