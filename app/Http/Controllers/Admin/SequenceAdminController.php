<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Product;

use App\Models\ShippingPrice;
use Illuminate\Http\Request;

class SequenceAdminController extends Controller
{
    public function index()
    {
        $products = Product::orderby('sequence', 'asc')->where('activated', 1)->get();

        return view('admin.sequence.edit', compact('products'));
    }

    public function update(Request $request)
    {
        try {
            $itemOrder = $request->input('itemOrder');
            if (isset($itemOrder)) {
                $itemIDs = explode(',', $itemOrder);

                foreach ($itemIDs as $index => $id) {
                    $item = Product::find($id);
                    $item->sequence = $index;
                    $item->save();
                }

                return response()->json(['success' => 'Success!']);
            }
        } catch (\Throwable $th) {
            return response()->json(['Error' => 'Failed']);
        }
    }


}
