@extends('layouts.header_newsite')
@section('title', 'Pribonasorte - Pagamento')
@section('content')

<style>
    .text-title {
        font-size: 50px;
        font-weight: bold;
        color: #ffffff;
        text-transform: uppercase;
    }

    .line-content {
        margin-bottom: 30px;
    }

    .container-ecomm {
        width: 100%;
        padding: 5% 9%;
        background: #f1f1f1;
    }

    .raw {
        width: 100%;
        display: inline-block;
    }

    .card-ecomm {
        width: 23%;
        height: fit-content;
        display: inline-block;
        margin: 0px 1%;
        background: #ffffff;
        border-radius: 5px;
        border: 1px solid #eeeeee;
        float: left;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 2px 2px 5px 1px rgba(0, 0, 0, 0.1);
        transition: 200ms linear;
    }

    .card-ecomm:hover {
        width: 23%;
        height: fit-content;
        display: inline-block;
        margin: 0px 1%;
        background: #ffffff;
        border-radius: 5px;
        border: 1px solid #eeeeee;
        float: left;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 2px 2px 20px 3px rgba(0, 0, 0, 0.1);
        transition: 200ms linear;
    }

    .moldure {
        width: 100%;
        height: 250px;
        background-size: 100%;
        overflow: hidden;
        cursor: pointer;
    }

    img.imagem_zoon {
        max-width: 100%;
        -moz-transition: all 0.3s;
        -webkit-transition: all 0.3s;
        transition: all 0.3s;
    }

    img.imagem_zoon:hover {
        -moz-transform: scale(1.1);
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    h5.tittle-name {
        font-weight: bold;
    }

    button.btn-ecomm {
        width: 50%;
        display: inline-block;
        height: 35px;
        border-radius: 100px;
        background: #d26075;
        color: #ffffff;
        border: 1px #d26075 solid;
        margin-top: 30px;
        font-size: 14px;
        transition: 200ms linear;
    }

    button.btn-ecomm:hover {
        width: 50%;
        display: inline-block;
        height: 35px;
        border-radius: 100px;
        background: #eeeeee;
        color: #d26075;
        border: 1px #d26075 solid;
        margin-top: 30px;
        font-size: 14px;
        transition: 200ms linear;
    }

    .band-title {
        width: 80%;
        float: left;
        display: inline-block;
    }

    .band-quantify {
        width: 20%;
        float: left;
        display: inline-block;
    }

    p.title-ecomm {
        font-size: 40px;
        margin-left: 11px;
        font-weight: bold;
        color: #212121;
    }

    p.count-ecomm {
        float: right;
        font-size: 16px;
        margin-right: 11px;
        margin-top: 20px;
    }

    .text-price {
        font-size: 1rem;
    }

    .text-price-bottom {
        font-size: 12px;
    }

    @media all and (min-width:2px) and (max-width:820px) {
        .card-ecomm {
            width: 100%;
            display: inline-block;
            background: #ffffff;
            margin: 0px 0px;
            border-radius: 5px;
            border: 1px solid #eeeeee;
            float: left;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 2px 2px 5px 1px rgba(0, 0, 0, 0.1);
            transition: 200ms linear;
        }

        p.title-ecomm {
            font-size: 30px;
            margin-left: 0px;
            font-weight: bold;
            color: #212121;
        }

        .band-title {
            width: 60%;
            float: left;
            display: inline-block;
        }

        .band-quantify {
            width: 40%;
            float: left;
            display: inline-block;
        }

        p.count-ecomm {
            float: right;
            font-size: 16px;
            margin-right: 0px;
            margin-top: 10px;
        }
    }

    #background-primary {
        height: 100%;
    }

    .container-ecomm {
        height: 100%;
    }

    button.button-cart {
        width: fit-content;
        height: 45px;
        border-radius: 5px;
        background-color: #d26075;
        color: #ffffff;
        font-size: 14px;

        font-weight: bold;
        border: 1px #d26075 solid;
        text-transform: uppercase;
        transition: 200ms linear;
    }

    button.button-cart:hover {
        width: fit-content;
        height: 45px;
        border-radius: 5px;
        background-color: #eeeeee;
        color: #d26075;
        font-size: 14px;
        /* margin-top: 30px; */
        font-weight: bold;
        border: 1px #d26075 solid;
        text-transform: uppercase;
        transition: 200ms linear;
    }

    .quantify-buttom {
        width: 100px;
        height: 40px;
        border-radius: 100px;
        padding: 10px;
        border: 1px solid #919191;
        margin-top: 20px;
    }

    .container-products {
        display: flex;
        width: 100% !important;
        justify-content: center;
        align-items: flex-start;
        gap: 1rem;
    }

    .filter {
        width: 30%;
        display: flex;
        flex-direction: column;
        gap: .5rem;
        background-color: #fff;
        padding: 1rem;
    }

    .filter h2 {
        border-bottom: 1px solid #eee;
    }

    .products {
        border-radius: 10px;
        width: 70%;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .categories {
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .categories>a {
        padding: .5rem;
        border-bottom: 1px solid #eee;
        background-color: #fdfdfd;
    }

    .categories>a:hover {
        width: 100%;
        background-color: #f1f1f1;
    }

    .product img {
        max-width: 100%;
        width: 200px;
    }

    .product {
        background-color: #ffffff;
        display: flex;
        gap: 1rem;
        border-bottom: #cecece 1px solid;
        cursor: pointer;
        border-radius: 10px;
    }

    .product:hover {
        transform: scale(1.05);
        transition: 200ms;
    }

    .text-product {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        width: 100%
    }

    .text-product a {
        color: #d26075;
        font-weight: 800;
    }


    .text-product>div {
        display: flex;
        justify-content: space-between;
        width: 60%;
    }

    .prices {
        color: #d26075;
    }

    @media all and (max-width:820px) {
        .container-products {
            flex-direction: column;
        }

        .filter {
            width: 100% !important;
            padding: 1rem;
        }

        .categories {
            flex-direction: row;
            overflow-x: auto;
            gap: .5rem;
            width: 100% !important;
        }

        .products {
            width: 100%
        }

        .text-product>div {
            width: 90%;
            flex-wrap: wrap
        }

    }
</style>

<main id="main" style="display: none;" class="main p-0">
    <section style="backdrop-filter: blur(0px);filter: brightness(120%) grayscale(0%) saturate(120%);" id="herosection">
        <div data-bss-scroll-zoom="true" data-bss-scroll-zoom-speed="0.5"
            style="width: 100%; height: 10vh; background: #d26075;">
            <div class="container h-100">
                <div class="row justify-content-center align-items-center h-100">
                    {{-- <center><p class="text-title">Vitamin and Minerals</p></center> --}}
                </div>
            </div>
        </div>
    </section>
</main>

<main id="background-primary" style="margin-top: 30px;">

    <section class="container-ecomm">
        <div class="h-100 container-fluid">
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


    </section>
</main>
@endsection