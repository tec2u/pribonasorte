<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShippingPrice;
use App\Models\Tax;
use App\Models\ValidateVatViesapi;
use Illuminate\Http\Request;

require_once public_path('viesapi-php-client/VIESAPI/VIESAPIClient.php');


class TaxController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        $allCountry = ShippingPrice::all();

        return view('admin.tax.index', compact('products', 'allCountry'));
    }

    public function edit()
    {
        $products = Product::orderBy('id', 'DESC')->get();
        $allCountry = ShippingPrice::all();

        return view('admin.tax.edit', compact('products', 'allCountry'));

    }

    public function getByCountry(Request $request)
    {
        $taxExist = Tax::where('country', $request->country)->where('product_id', $request->product_id)->first();
        $allCountry = ShippingPrice::all();

        if (isset($taxExist)) {
            $tax = $taxExist->value;
        } else {
            $tax = 0;
        }

        $data = [
            'tax' => $tax,
            'country' => $request->country,
            'product_id' => $request->product_id
        ];

        $products = Product::orderBy('id', 'DESC')->get();
        return view('admin.tax.index', compact('products', 'data', 'allCountry'));
    }

    public function update(Request $request)
    {
        $taxExist = Tax::where('product_id', $request->product_id)->where('country', $request->country)->first();

        try {
            if (isset($taxExist)) {
                $taxExist->value = $request->value;
                $taxExist->save();
            } else {
                $tax = new Tax;
                $tax->product_id = $request->product_id;
                $tax->country = $request->country;
                $tax->value = $request->value;
                $tax->save();
            }
            return redirect()->route('admin.packages.index_tax')->with('message', 'Successfully');
        } catch (\Throwable $th) {
            return redirect()->route('admin.packages.index_tax')->with('message', 'Failed to edit');
        }

    }

    public function validateVatId()
    {
        dd('in constrution');
        \VIESAPI\VIESAPIClient::registerAutoloader();
        $viesapi = new \VIESAPI\VIESAPIClient('lTvIknEFQOOp', 'hW2nDabYENyp');
        $vies = $viesapi->get_vies_data('CZ26126893');

        if ($vies) {
            dd($vies);
            try {
                //code...

                $validate = new ValidateVatViesapi;
                $validate->uid = $vies->uid;
                $validate->country_code = $vies->country_code;
                $validate->vat_number = $vies->vat_number;
                $validate->valid = $vies->valid;
                $validate->trader_name = $vies->trader_name;
                $validate->trader_company_type = $vies->trader_company_type;
                $validate->trader_address = $vies->trader_address;
                $validate->return_id = $vies->id;
                $validate->date = $vies->date;
                $validate->source = $vies->source;
                $validate->save();
            } catch (\Throwable $th) {
                //throw $th;
            }

        } else {
            dd($viesapi->get_last_error());
        }
    }

}

