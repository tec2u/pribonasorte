@extends('adminlte::page')

@section('title', 'Smartshipping edit history')

@section('content_header')
  <div class="alignHeader">
    <h4>Smartshipping edit history</h4>
  </div>
  <i class="fa fa-home ml-3"></i> - @lang('admin.transaction.title2')
@stop

@section('content')

  <div class="card">
    <div class="card-header">

        <div class="row">
            <div class="col-12 mb-2">
                <form class="row g-3" method="GET" action="{{ route('admin.reports.smartshipping_history') }}">
                    @csrf
                    <div class="col-12 d-flex">
                        <div class="col">
                            <label style="margin-top: 10px;">Name:</label>
                            <input type="text" class="form-control" name="name" placeholder="Name" value="{{isset($name) ? $name : ''}}">
                        </div>
                        <div class="col">
                            <label style="margin-top: 10px;">Last Name:</label>
                            <input type="text" class="form-control" name="lastname" placeholder="Last name" value="{{isset($lastname) ? $lastname : ''}}">
                        </div>
                    </div>
                    <div class="col-12 d-flex mt-2">
                        <div class="col d-flex align-items-end justify-content-end">
                            <input type="submit" value="@lang('admin.btn.search')" class="btn btn-info" style="padding: 6px 28px;">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body table-responsive">
        <span class="counter float-right"></span>
        <table class="table table-hover table-bordered results" id="tabela-career">
            <thead>
            <tr>
                <th>Login</th>
                <th>Order number</th>
                <th>Product</th>
                <th>Amount</th>
                <th>Total</th>
                <th>Updated at</th>
            </tr>
            </thead>
            <tbody>
                @forelse($smartshipping as $smartship)
                <tr>
                    <td>{{$smartship->user->login}}</td>
                    <td>{{$smartship->number_order}}</td>
                    <td>{{$smartship->product->name}}</td>
                    <td>{{$smartship->amount}}</td>
                    <td>{{$smartship->total}}</td>
                    <td>{{$smartship->updated_at}}</td>
                </tr>
                @empty
                    <p class="m-4 fst-italic">You don't have any smartshipping edit history registered!</p>
                @endforelse
            </tbody>

        </table>
    </div>
  </div>
@stop
