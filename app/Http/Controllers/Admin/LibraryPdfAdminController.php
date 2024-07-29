<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LibraryPdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LibraryPdfAdminController extends Controller
{
    //

    public function index()
    {
        $library = LibraryPdf::all();
        return view('admin.members.library', compact('library'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'pdf_file' => 'required|mimes:pdf', // Valide o arquivo PDF
        ]);

        $pdf = new LibraryPdf;
        $pdf->title = $request->input('title');

        if ($request->hasFile('pdf_file')) {
            $pdfFile = $request->file('pdf_file');
            $pdfFileName = time() . '.' . $pdfFile->getClientOriginalExtension();
            $pdfFile->storeAs('library', $pdfFileName, 'public'); // "storage/app/public/pdfs"
            $pdf->pdf_data = $pdfFileName;
        }

        $pdf->save();

        return redirect()->route('list.library')->with('success', 'PDF created successfully');
    }

    public function delete($id)
    {
        $pdf = LibraryPdf::findOrFail($id);
        $pdf->delete();

        if (Storage::disk('public')->exists('library/' . $pdf->pdf_data)) {
            Storage::disk('public')->delete('library/' . $pdf->pdf_data);
        }

        return redirect()->back();
    }

    public function download($id)
    {
        // Encontre o PDF pelo ID
        $pdf = LibraryPdf::findOrFail($id);

        $filePath = storage_path('app/public/library/' . $pdf->pdf_data);

        // Verifique se o arquivo existe no sistema de arquivos
        if (!Storage::disk('public')->exists('library/' . $pdf->pdf_data) || !file_exists($filePath)) {
            abort(404, 'PDF not found');
        }

        // Defina o nome do arquivo para o download (pode personalizar conforme necessário)
        $fileName = $pdf->title . '.pdf';

        return response()->stream(function () use ($filePath) {
            $fileStream = fopen($filePath, 'rb');
            fpassthru($fileStream);
            fclose($fileStream);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}