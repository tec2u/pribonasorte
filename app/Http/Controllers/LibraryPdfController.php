<?php

namespace App\Http\Controllers;

use App\Models\LibraryPdf;
use Illuminate\Http\Request;

class LibraryPdfController extends Controller
{
    public function index()
    {
        $library = LibraryPdf::paginate(9);
        return view('library.list', compact('library'));
    }
}