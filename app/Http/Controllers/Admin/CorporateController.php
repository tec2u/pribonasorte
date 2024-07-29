<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use stdClass;
use VIESAPI\VIESAPIClient;

class CorporateController extends Controller
{
    public function verify($idCorporate){
        try {
            $viesapi = new VIESAPIClient(); //para rodar em ambiente de produção passar parametros env('API_VIES_IDENTIFIER'), env('API_VIES_KEY')
            $vies = $viesapi->get_vies_data($idCorporate) ; // iva para teste: PL7272445205

            $response = new stdClass();
            $response->success = $vies;
            $response->error = $viesapi->get_last_error();
            return response()->json($response);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }

    }
}
