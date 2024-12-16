@extends('layouts.header')
@section('content')
<style>
    .card-product-list {
        background: #ffffff;
        padding: 30px;
        border-radius: 5px;
        box-shadow: 2px 2px 20px #cdcdcd;
    }

    .new-title {
        font-size: 35px;
        margin-left: -20px;
        font-weight: bold;
        float: left;
        color: #000000;
    }

    .head-table-list {
        text-align: center;
    }

    .head-table-list thead {
        background: #d26075;
    }

    .card-title-new {
        font-size: 17px;
        font-weight: bold;
        color: #000000;
    }

    #button-buy {
        width: 100%;
        margin-top: 20px;
        height: 40px;
        font-size: 14px;
    }

    .price-value {
        font-size: 16px;
        font-weight: bold;
    }

    .bloco-footer {
        margin-left: -10px;
    }

    button.btn-amount {
        width: 20px;
        background: #d26075;
        color: #ffffff;
        border: 0px;
    }

    .endereco2 {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        padding: 1rem;
    }

    .endereco2 input,
    .endereco2 select {
        border-radius: 5px;
        padding: .2rem .5rem;
        border: #C0C0C0 solid 1px;
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 2;
    }

    /* CSS for the modal box */
    .modal-box {
        position: relative;
        margin: 0 auto;
        height: 720px;
        display: none;
        z-index: 2;
    }

    #close-modal-button {
        position: absolute;
        top: 10px;
        right: 10px;
        z-index: 3;
    }

    .ppl-parcelshop-map {
        height: 100%;
        max-height: 640px;
        display: relative;
        z-index: 2;
    }

    #name_ppl_selected {
        display: relative;
        z-index: 2;
    }
</style>

<main id="main" class="main">.
    @php
    $user_id = Auth::user()->id;
    use App\Models\User;
    use App\Models\ShippingPrice;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Carbon;
    use Illuminate\Support\Facades\DB;

    $allCountry = ShippingPrice::get();
    $user = User::find(Auth::user()->id);

    $currentDate = Carbon::now();
    $dayThreshold = 15;
    $cardAvailable = false;
    $subMonth = $currentDate->subMonth()->month;
    $currentYear = $currentDate->year;
    $currentMonth = $currentDate->month;

    if ($subMonth >= 2) {
    $subMonth = $subMonth - 1;
    $currentYear = $currentYear;
    } else {
    $subMonth = 12;
    $currentYear = $currentYear - 1;
    }

    if ($subMonth < 9) {
        $subMonth='0' . $subMonth;
        } else {
        $subMonth=$subMonth;
        }

        $farst_day=date('t', mktime(0, 0, 0, $subMonth, '01' , $currentYear));

        $dateComplete=$currentYear . '-' . $subMonth . '-' . $farst_day . ' 23:59:59' ;

        $dateComplete2=$currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

        if ($currentDate->day >= $dayThreshold) {
        $farst_day = Carbon::create($currentYear, $currentMonth)->endOfMonth()->day;
        $dateComplete2 = $currentDate->year . '-' . $currentMonth . '-' . $farst_day . ' 23:59:59';

        $cardAvailable = true;
        }

        @endphp
        <section id="produto" class="content">

            <div class="fade">
                <div class="container-fluid">
                    <div class="row justify-content-evenly" style="margin-bottom: 10px;">
                        <p class="new-title">Carrinho</p>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('packages.index_products') }}"><button class="btn btn-primary btn-lg"
                                style="width: 100%; font-size: 13px;" type="button">Voltar aos produtos</button></a>
                    </div>
                    <div class="justify-content-evenly card-product-list" style="margin-bottom: 20px;">
                        <div class="head-table-list">
                            <table style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th style="color: #ffffff; font-size: 13px;">Produto</th>
                                        <th style="color: #ffffff; font-size: 13px;">Preço</th>
                                        <th style="color: #ffffff; font-size: 13px;">VAT</th>
                                        <th style="color: #ffffff; font-size: 13px;">Quantidade</th>
                                        <th style="color: #ffffff; font-size: 13px;">Total</th>
                                        <th style="color: #ffffff; font-size: 13px;"></th>
                                        <th style="color: #ffffff; font-size: 13px;"></th>
                                    </tr>
                                </thead>

                                @if ($count_itens > 0)
                                <tbody>
                                    @foreach ($user_cart as $item)
                                    @php
                                    $new_price = number_format($item->price, 2, ',', '.');
                                    $new_total = number_format($item->total, 2, ',', '.');
                                    $priceTax = number_format($item->priceTax, 2, ',', '.');
                                    $new_sub_total = number_format($subtotal, 2, ',', '.');

                                    @endphp

                                    <tr>
                                        <th style="border: 1px solid silver; ">
                                            <a href=""><img style="width: 40px;" src="/img/products/{{ $item->img }}"
                                                    alt="img_products"></a>
                                            {{ $item->name }}
                                        </th>


                                        <th style="border: 1px solid silver; ">€{{ $new_price }}</th>
                                        <th style="border: 1px solid silver; ">€{{ $priceTax }}</th>
                                        <th style="border: 1px solid silver; ">
                                            <ul style="margin-top: 10px;">
                                                <li style="display: inline-block;"><a id="diminuir_quant"
                                                        href="{{ route('packages.edit_down_amount_buy', ['id' => $item->id]) }}"><button
                                                            class="btn-amount">-</button></a></li>
                                                <li style="display: inline-block;"><input class="text-center" style="width: 50px;" disabled
                                                        type="text" value="{{ $item->amount }}"></li>
                                                <li style="display: inline-block;"><a id="aumentar_quant"
                                                        href="{{ route('packages.edit_up_amount_buy', ['id' => $item->id]) }}"><button
                                                            class="btn-amount">+</button></a></li>
                                            </ul>
                                        </th>
                                        <th style="border: 1px solid silver; ">
                                            €{{ number_format($item->total + $item->priceTax, 2, ',', '.') }}</th>
                                        <th style="border: 1px solid silver; ">
                                            <a id="limpa_carrinho" href="{{ route('packages.delete_cart_buy', ['id' => $item->id]) }}"><i
                                                    style="font-size: 20px;" class="bi bi-trash3-fill"></i></a>
                                        </th>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @else
                                <tbody>
                                    <tr></tr>
                                    <th style="border: 1px solid silver; padding: 10px;" width="100%">
                                        <p class="text-center mt-2">Carrinho vazio!</p>
                                    </th>
                                    </tr>
                                </tbody>
                                @endif
                            </table>
                        </div>


                        @if ($count_itens > 0)
                        <table style="margin-top: 10px;">
                            <thead>
                                <th style="border: 1px solid silver; padding: 10px;" width="75%">Frete</th>
                                <th style="border: 1px solid silver; padding: 10px;" width="25%">€{{ $priceShippingHome }}</th>
                            </thead>
                        </table>
                        @endif
                    </div>
                    @if ($count_itens > 0)
                    <form action="{{ route('packages.cartFinalize') }}" method="POST" onsubmit="submitCheckout()"
                        id="form-checkout-backoffice">
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" value="{{ $user_id }}">
                        <input type="hidden" name="name_ppl" id="name_ppl">
                        <input type="hidden" name="address_ppl" id="address_ppl">
                        <input type="hidden" name="city_ppl" id="city_ppl">
                        <input type="hidden" name="zip_code_ppl" id="zip_code_ppl">
                        <input type="hidden" name="country_ppl" id="country_ppl">
                        <input type="hidden" name="number_ppl" id="number_ppl">
                        <input type="hidden" name="state_ppl" id="state_ppl">
                        <input type="hidden" name="neighborhood_ppl" id="neighborhood_ppl">
                        <input type="hidden" name="cell_ppl" id="cell_ppl">

                        <div style="margin-top: 20px;" class="row justify-content-evenly">



                            <!-- Modal overlay -->

                            <div class="modal-overlay" id="modal-overlay"></div>
                            <div class="modal-box" style="background-color: #f0f0f0;">
                                <div id="ppl-parcelshop-map"></div>
                                <h5 id="name_ppl_selected"
                                    style="display: none; background-color: #f0f0f0; font-size:2rem; color:green;">Selecionado
                                </h5>
                                <button id="close-modal-button" class="btn btn-primary btn_address">fechar</button>
                            </div>

                            <div style="display: flex;flex-direction: row;flex-wrap:wrap; padding:0;">
                                <div style="padding: 0rem;" class="col-sm-12 col-md-6">
                                    <div class="card shadow p-md-8 address-content" id="toOtherAddress"
                                        style="width: 100%; padding:1rem;height: auto; display: flex; flex-wrap: wrap; display: none">

                                        <p class="card-title-new text-start">Outro endereço:</p>

                                        <div class="row-form endereco2">

                                            <div class="col-sm-12">
                                                <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                                                    placeholder="Nome" type="text" name="first_name" id="first_name_toOtherAddress" class="form-control form-register">
                                            </div>

                                            <div class="col-sm-12">
                                                <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                                                    class="form-control form-register" placeholder="Sobrenome" type="text"
                                                    name="last_name" id="last_name_toOtherAddress">
                                            </div>



                                            <div class="col-sm-12">
                                                <input id="cell_toOtherAddress" onkeypress="allowNumeric(event)" type="cell"
                                                    class="form-control form-register" placeholder="Celular" name="phone"
                                                    value="{{ old('phone') }}" tabindex="10">
                                                <span for="cell" class="focus-input50"></span>
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Estado - exemplo: SP"
                                                    id="state_toOtherAddress" type="text" name="state">
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Cidade"
                                                    id="city_toOtherAddress" type="text" name="city">
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Bairro"
                                                    id="neighborhood_toOtherAddress" type="text" name="neighborhood">
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Endereço"
                                                    id="address_toOtherAddress" type="text" name="address">
                                            </div>
                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="N° da residencia"
                                                    type="number" name="number" id="number_toOtherAddress">
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register cep" placeholder="Cep"
                                                    aria-label="CEP" id="zip_code_toOtherAddress" type="number" name="zip">
                                            </div>

                                            <div class="col-sm-12">
                                                <select class="form-input-finalize form-control form-register" name="country"
                                                    id="country_toOtherAddress">
                                                    <option value="">Escolher</option>
                                                    @foreach ($countryes as $item)
                                                    <option class="country-{{ $item->country_code }}" value="{{ $item->country }}">
                                                        {{ $item->country }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-12">
                                                <button class="btn btn-outline-primary" type="button" onclick="calculateShippiment('toOtherAddress')">Calcular</button>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="card shadow p-md-8 address-content" id="toAddress"
                                        style="width: 100%; padding:1rem;height: auto; display: flex; flex-wrap: wrap; display: none">

                                        <p class="card-title-new text-start">Seu endereço:</p>

                                        <div class="row-form endereco2">

                                            <div class="col-sm-12">
                                                <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                                                    placeholder="Nome" type="text" readonly value="{{ $user->name }}"
                                                    class="form-control form-register" id="first_name_toAddress">
                                            </div>

                                            <div class="col-sm-12">
                                                <input style="width: 100%; height: 30px; border-radius: 5px; border: solid 1px #cdcdcd;"
                                                    class="form-control form-register" placeholder="Sobrenome" type="text"
                                                    value="{{ $user->last_name }}" readonly id="last_name_toAddress">
                                            </div>

                                            <div class="col-sm-12">
                                                <input id="cell_toAddress" onkeypress="allowNumeric(event)" type="cell"
                                                    class="form-control form-register" placeholder="Celular" value="{{ $user->cell }}"
                                                    autocomplete="cell" tabindex="10" readonly>
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Estado - exemplo: SP"
                                                    id="state_toAddress" type="text" value="{{ $user->state }}" required oninput="changeField(this, 'state_ppl')">
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Cidade"
                                                    id="city_toAddress" type="text" value="{{ $user->city }}" readonly>
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Bairro"
                                                    id="neighborhood_toAddress" type="text" value="{{ $user->area_residence }}" oninput="changeField(this, 'neighborhood_ppl')">
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="Endereço"
                                                    id="address_toAddress" type="text" value="{{ $user->address1 }}" readonly>
                                            </div>
                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register" placeholder="N° da residencia"
                                                    type="number" id="number_toAddress" value="{{ $user->number_residence }}" readonly>
                                            </div>

                                            <div class="col-sm-12">
                                                <input class="form-input-finalize form-control form-register cep" placeholder="Cep"
                                                    aria-label="CEP" id="zip_code_toAddress" type="number" value="{{ $user->postcode }}" readonly>
                                            </div>

                                            <div class="col-sm-12">
                                                <select class="form-input-finalize form-control form-register" id="country_toAddress">
                                                    <option value="{{ $user->country }}">
                                                        {{ $user->country }}
                                                    </option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <div style="padding: 0rem;" class="col-sm-12 col-md-6">
                                    @if (session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                    @endif

                                    <div class="card shadow p-md-2" style="width: 100%; height: fit-content;">

                                        <p class="card-title-new text-start">Escolher metodo de entrega</p>
                                        <div class="form-check" style="font-size: 1rem;">
                                            <input class="form-check-input loading-shipping" type="radio" value="toAddress" name="method_shipping" id="to_address"
                                                onclick="changeShippingMethod('toAddress')" required>
                                            <label class="form-check-label" for="to_address">
                                                Entregar em seu endereço
                                            </label>
                                        </div>

                                        <div class="form-check" style="font-size: 1rem;">
                                            <input class="form-check-input loading-shipping" type="radio" value="toOtherAddress" name="method_shipping"
                                                id="to_other_address" onclick="changeShippingMethod('toOtherAddress')" required>
                                            <label class="form-check-label" for="to_other_address">
                                                Outro endereço
                                            </label>
                                        </div>

                                        <div id="allowed-shipping-methods"></div>
                                        <input type="hidden" id="send_method" name="send_method">
                                        <table style="margin-top: 10px;">
                                            <thead>
                                                <tr>
                                                    <th style="border: 1px solid silver; padding: 10px;" width="75%">Subtotal</th>
                                                    <th style="border: 1px solid silver; padding: 10px;" width="25%">€{{ $withoutVAT }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="border: 1px solid silver; padding: 10px;" width="75%">Frete</th>
                                                    <th style="border: 1px solid silver; padding: 10px;" id="frete" width="25%">
                                                        0
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th style="border: 1px solid silver; padding: 10px;" width="75%">Total</th>
                                                    <th style="border: 1px solid silver; padding: 10px;" id="value_order" width="25%">
                                                        {{ $new_sub_total }}
                                                    </th>
                                                </tr>
                                            </thead>
                                        </table>

                                        <input type="hidden" style="display: none" id="request_price" name="price"
                                            value="{{ $new_sub_total }}">

                                        <div class="form-check" style="display:flex;justify-content:start; gap:.5rem; align-items:center">
                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                                            <label class="form-check-label" for="flexCheckDefault" style="color: #212121;font-size:.8rem;">
                                                Eu concordo com os
                                                <a href="{{ route('general_terms_conditions') }}" style="color: #212121;">
                                                    termos</a>
                                            </label>

                                        </div>

                                        <button @if (isset($btnSmart) && $btnSmart=='smart' ) disabled @endif class="btn btn-primary btn-lg" id="button-buy">Fazer pedido</button>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>

                <div class="container-fluid bloco-footer">
                    <div class="row">
                        <div class="col-4">
                            <a href="{{ route('packages.index_products') }}"><button class="btn btn-primary btn-lg"
                                    style="width: 100%; font-size: 13px;" type="button">Continuar comprando</button></a>
                        </div>
                        @if ($count_itens > 0)
                        <div class="col-4">
                            <a href="{{ route('packages.clear_cart_buy') }}"><button class="btn btn-primary btn-lg"
                                    style="width: 100%; font-size: 13px;" type="button">Limpar carrinho</button></a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

</main>

<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<link rel="stylesheet" href="{{ asset('css/intlTelInput.min.css') }}" />
<script>

    function changeField(iThis, pplID) {
        $(`#${pplID}`).val($(iThis).val())
    }
    function changeShippingMethod(method) {
        $('#allowed-shipping-methods').html('')
        $('.address-content').css('display', 'none');
        if ($(`#${method}`).length > 0) {
            $(`#${method}`).css('display', 'block')
            if (method === 'toAddress') {
                calculateShippiment(method)
            }
        }
    }

    function changeSendMethod(methodID, methodPrice) {
        $('#send_method').val(methodID);
        $('#frete').text(parseFloat(methodPrice));
        $('#value_order').text(parseFloat(methodPrice) + parseFloat($('#request_price').val()));

    }

    function calculateShippiment(method) {
        $('.loading-shipping').each((index, element) => {
            element.disabled = true;
        })
        $('#button-buy').attr("disabled", true);
        const formData = {
            name: $(`#first_name_${method}`).val() + " " + $(`#cell_${method}`).val(),
            cell: $(`#cell_${method}`).val(),
            city: $(`#city_${method}`).val(),
            address: $(`#address_${method}`).val(),
            number: $(`#number_${method}`).val(),
            zip_code: $(`#zip_code_${method}`).val(),
            country: $(`#country_${method}`).val(),
        }

        $(`#name_ppl`).val($(`#first_name_${method}`).val() + " " + $(`#cell_${method}`).val());
        $(`#address_ppl`).val($(`#address_${method}`).val());
        $(`#city_ppl`).val($(`#city_${method}`).val());
        $(`#zip_code_ppl`).val($(`#zip_code_${method}`).val());
        $(`#country_ppl`).val($(`#country_${method}`).val());
        $(`#number_ppl`).val($(`#number_${method}`).val());
        $(`#state_ppl`).val($(`#state_${method}`).val());
        $(`#neighborhood_ppl`).val($(`#neighborhood_${method}`).val());
        $(`#cell_ppl`).val($(`#cell_${method}`).val());

        axios.post(`/api/calculate/${$('#user_id').val()}`, formData).then((response) => {
            const shippingMethods = response.data
            $('#allowed-shipping-methods').html('<p class="mt-4 mb-2 card-title-new text-start">Entrega feita por:</p>')

            shippingMethods.forEach((method) => {
                $('#allowed-shipping-methods').append(`
                    <div class="form-check d-flex align-items-center"><input type="radio" name="send_value" value="${method.price}" id="send_by_${method.id}" class="mr-2 form-check-input" onchange="changeSendMethod(${method.id}, ${method.price})" required/> <label class="mb-0 form-check-label" for="send_by_${method.id}">${method.name} R$ ${parseFloat(method.price)}</label></div>
                `)
            })

            $('.loading-shipping').each((index, element) => {
                element.disabled = false;
            })
            $("#button-buy").attr("disabled", false);


        })
    }
</script>



@endsection
