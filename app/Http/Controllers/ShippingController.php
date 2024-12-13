<?php

namespace App\Http\Controllers;

use App\Models\CartOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShippingController extends Controller
{
    public function calculateShippingPrice(Request $request, $userID)
    {

        $productsFromCart = CartOrder::with('product')->where('id_user', $userID)->get();

        $items = [];

        foreach ($productsFromCart as $product) {
            $obj = [
                'quantity' => intval($product->amount),
                'height' => floatval($product->product->height),
                'width' => floatval($product->product->width),
                'length' => floatval($product->product->depth),
                'weight' => floatval($product->product->weight),
            ];
            $items[] = $obj;
            unset($obj);
        }

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json', // Opcional dependendo do serviço
            'User-Agent' => 'Abimael (a.bimael2000@hotmail.com)',
            'Authorization' => 'Bearer '.env("API_SUPERFRETE_KEY"), // Ajuste conforme necessário
        ])->post(env("API_SUPERFRETE_URL")."/calculator", [
            'from' => [
                'postal_code' => '12953015',
            ],
            'to' => [
                'postal_code' => preg_replace('/\D/', '', $request->zip_code),
            ],
            'services' => '1,2,17',
            'options' => [
                'own_hand' => true,
                'receipt' => false,
                'insurance_value' => 0,
                'use_insurance_value' => false,
            ],
            'products' => $items,
        ]);
        // $data = json_decode($response->json());

        return response()->json($response->json());
    }
}
