<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductByCountry;
use App\Models\ShippingPrice;
use Illuminate\Http\Request;

class ProductByCountryAdminController extends Controller
{

    public function filterBycountry()
    {
        $country = ShippingPrice::all();

        foreach ($country as $c) {
            $quant = ProductByCountry::where('id_country', $c->id)->get();
            if (count($quant) > 0) {
                $c->quant = count($quant);
            }
        }

        return view('admin.productBycountry.listCountry', compact('country'));
    }

    public function filterBycountrySelect($id)
    {
        $country = ShippingPrice::where('id', $id)->first();
        $products = Product::orderby('sequence', 'asc')->where('activated', 1)->get();

        if (!isset($country))
            return abort(404);

        $existsCountrySelected = ProductByCountry::where('id_country', $country->id)->get();

        if (count($existsCountrySelected) > 0) {
            foreach ($existsCountrySelected as $productCountry) {
                foreach ($products as $item) {
                    if ($productCountry->id_product == $item->id) {
                        $item->estaNaCategoria = true;
                    }
                }
            }
        }

        return view('admin.productBycountry.listProduct', compact('country', 'products'));
    }

    public function filterBycountrySelected(Request $request)
    {
        $products = [];

        $country = ShippingPrice::where('id', $request->id_country)->first();
        if (!isset($country))
            return abort(404);

        if (empty($request->image)) {
            ProductByCountry::where('id_country', $country->id)->delete();
            return redirect()->back();
        }

        foreach ($request->image as $key => $value) {
            array_push($products, $value);
        }


        ProductByCountry::where('id_country', $country->id)->delete();

        foreach ($products as $value) {
            $product = Product::where('id', $value)->first();

            if (isset($product)) {
                $save = new ProductByCountry;
                $save->id_country = $country->id;
                $save->id_product = $value;
                $save->save();
            }
        }

        return redirect()->route('admin.packages.filterBycountry.country');

    }
}
