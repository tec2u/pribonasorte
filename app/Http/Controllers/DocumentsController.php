<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\EcommOrders;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use setasign\Fpdi\Tcpdf\Fpdi;
use ZipArchive;

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
        // return response()->json($orders);
        return view('daily.documents', compact('orders', 'fdate', 'sdate'));
    }

    public function downloadFile($id)
    {
        $file = Documents::where("id", $id)->first();

        if (!$file) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        $filepath = storage_path("app/public/videos/{$file->content}");

        if (!file_exists($filepath)) {
            return response()->json(['error' => 'Arquivo não encontrado no armazenamento.'], 404);
        }

        $userLogin = auth()->user()->login;
        $metadata = [
            'user' => $userLogin,
            'description' => 'Arquivo baixado com metadados',
            'downloaded_at' => now()->toDateTimeString(),
        ];

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
            case 'pdf':
                $newFilePath = $this->addMetadataToPdf($filepath, $metadata);
                break;

            case 'docx':
                $newFilePath = $this->addMetadataToDoc($filepath, $metadata);
                break;

            case 'zip':
                $newFilePath = $this->addMetadataToZip($filepath, $metadata);
                break;
        }

        // $headers = [
        //     'Content-Type' => mime_content_type($newFilePath),
        //     'Content-Disposition' => "attachment; filename=\"{$file->title}\"",
        //     'X-User-Login' => $userLogin,
        // ];

        return response()->json($extension);
        // return response()->download($newFilePath, $file->title, $headers);
    }

    private function addMetadataToPdf($filePath, $metadata)
    {
        $newFilePath = storage_path("app/public/documents/with_metadata_" . time() . ".pdf");

        // Inicialize o FPDI baseado no TCPDF
        $pdf = new Fpdi();

        // Configuração do documento
        $pdf->SetCreator('Seu Sistema');
        $pdf->SetAuthor($metadata['user']);
        $pdf->SetTitle($metadata['description']);
        $pdf->SetSubject('Arquivo com metadados');
        $pdf->SetKeywords('metadados, pdf, download');

        $pdf->AddPage();
        $pdf->setSourceFile($filePath);
        $templateId = $pdf->importPage(1);
        $pdf->useTemplate($templateId);

        $pdf->Output($newFilePath, 'F');

        return $newFilePath;
    }

    private function addMetadataToDoc($filePath, $metadata)
    {
        $newFilePath = storage_path("app/public/documents/with_metadata_" . time() . ".docx");

        $templateProcessor = new TemplateProcessor($filePath);

        // Adicionar metadados no cabeçalho do documento (exemplo básico)
        $templateProcessor->setValue('Title', $metadata['description']);
        $templateProcessor->setValue('Author', $metadata['user']);
        $templateProcessor->setValue('Date', $metadata['downloaded_at']);

        $templateProcessor->saveAs($newFilePath);

        return $newFilePath;
    }

    private function addMetadataToZip($filePath, $metadata)
    {
        $newFilePath = storage_path("app/public/documents/with_metadata_" . time() . ".zip");

        $zip = new ZipArchive();
        if ($zip->open($newFilePath, ZipArchive::CREATE) === true) {
            $zip->addFile($filePath, basename($filePath));

            $metadataContent = json_encode($metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $zip->addFromString('metadata.json', $metadataContent);

            $zip->close();
        } else {
            throw new \Exception('Falha ao criar o arquivo ZIP com metadados.');
        }

        return $newFilePath;
    }

    public function getDateDocuments(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $documents = Documents::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('daily.documents', compact('documents'));
    }
}
