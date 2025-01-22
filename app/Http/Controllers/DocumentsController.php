<?php

namespace App\Http\Controllers;

use App\Models\Documents;
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
        $documentsQuery = Documents::orderBy('id', 'DESC');
        $fdate = $request->fdate ? $request->fdate. " 00:00:00" : '' ;
        $sdate = $request->sdate ? $request->sdate. " 23:59:59" : '' ;

        if ($fdate) {
            $documentsQuery->where('created_at', '>=', $fdate);
        }
        if ($sdate) {
            $documentsQuery->where('created_at', '<=', $sdate);
        }

        $documents = $documentsQuery->paginate(9);

        return view('daily.documents', compact('documents', 'fdate', 'sdate'));
    }

    public function downloadFile($id)
    {
        $file = Documents::where("id", $id)->first();
        $filepath = storage_path("app/public/{$file->path}");
        return response()->download($filepath);
    }

    public function getDateDocuments(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $documents = Documents::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('daily.documents', compact('documents'));
    }
}
