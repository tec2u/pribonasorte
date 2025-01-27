<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\EcommOrders;
use App\Models\OrderPackage;
use Illuminate\Http\Request;

class DocumentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ordersQuery = EcommOrders::with(['product', 'product.documentAdditional'])->whereHas('product', function ($query) {
            $query->where('type', 'virtual');
        })->where('id_user', auth()->user()->id)->where('status_order', 'order placed');

        $fdate = $request->fdate ? $request->fdate . " 00:00:00" : '';
        $sdate = $request->sdate ? $request->sdate . " 23:59:59" : '';

        if ($fdate) {
            $ordersQuery->where('created_at', '>=', $fdate);
        }
        if ($sdate) {
            $ordersQuery->where('created_at', '<=', $sdate);
        }

        $orders = $ordersQuery->paginate(9);
        return response()->json($orders);
        return view('daily.documents', compact('orders', 'fdate', 'sdate'));
    }

    public function downloadFile($id)
    {
        $file = Documents::where("id", $id)->first();

        if (!$file) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        $filepath = storage_path("app/public/{$file->path}");

        if (!file_exists($filepath)) {
            return response()->json(['error' => 'Arquivo não encontrado no armazenamento.'], 404);
        }

        $userLogin = auth()->user()->login;

        $headers = [
            'Content-Type' => mime_content_type($filepath),
            'Content-Disposition' => "attachment; filename=\"{$file->name}\"",
            'X-User-Login' => $userLogin,
        ];

        return response()->download($filepath, $file->name, $headers);
    }

    public function getDateDocuments(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $documents = Documents::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('daily.documents', compact('documents'));
    }
}
