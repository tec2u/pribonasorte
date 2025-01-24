<?php

namespace App\Http\Controllers;

use App\Models\OrderPackage;
use App\Models\Video;
use Illuminate\Http\Request;

class VideosController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $fdate = $request->fdate ? $request->fdate. " 00:00:00" : '' ;
        $sdate = $request->sdate ? $request->sdate. " 23:59:59" : '' ;

        $ordersQuery = OrderPackage::with(['product', 'product.videoAdditional'])->whereHas('product', function ($query) {
            $query->where('type', 'curso');
        })->where('user_id', auth()->user()->id)->where('payment_status', 1)->where('status', 1);

        if ($fdate) {
            $ordersQuery->where('created_at', '>=', $fdate);
        }
        if ($sdate) {
            $ordersQuery->where('created_at', '<=', $sdate);
        }

        $orders = $ordersQuery->paginate(9);

        return view('daily.videos', compact('orders', 'fdate', 'sdate'));
    }

    public function downloadFile($id)
    {
        $file = Video::where("id", $id)->first();
        $filepath = storage_path("app/public/{$file->path}");
        return response()->download($filepath);
    }

    public function getDateVideos(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $videos = Video::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('daily.videos', compact('videos'));
    }
}
