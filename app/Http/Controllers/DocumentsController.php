<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\EcommOrders;
use App\Models\OrderPackage;
use App\Models\Product;
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
        $product = Product::find($product_id);

        if (!$file) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        $filepath = storage_path("app/public/videos/stitch pb.zip");

        return response()->file($filepath);
        if (!file_exists($filepath)) {
            return response()->json(['error' => 'Arquivo não encontrado no armazenamento.'], 404);
        }
        return response()->json(['id' => $id, 'product' =>$file]);

        $userLogin = auth()->user()->login;
        $metadata = [
            'user' => $userLogin,
            'description' => 'Arquivo baixado com metadados',
            'downloaded_at' => now()->toDateTimeString(),
        ];

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
            case 'zip':
                $newFilePath = $this->addPasswordToZip($filepath, $product->title, $userLogin);
                break;
            default:
                $newFilePath = $this->createZipWithPassword($filepath, $product->title, $userLogin);
                break;
        }

    }

    private function addPasswordToZip($zipFilePath, $title, $password)
    {
        $zip = new \ZipArchive();
        $tempPath = str_replace('.zip', '_secured.zip', $zipFilePath);

        if ($zip->open($zipFilePath) === true) {
            $zip->setPassword($password);
            $fileName = basename($title);

            // Protege os arquivos já dentro do ZIP
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $zip->setEncryptionName($fileName, \ZipArchive::EM_AES_256, $password);
            }

            $zip->close();
            rename($zipFilePath, $tempPath); // Substitui o ZIP original pelo protegido
        } else {
            throw new \Exception("Não foi possível abrir o arquivo ZIP.");
        }

        return $tempPath;
    }

    private function createZipWithPassword($filePath, $title, $password)
    {
        $zip = new \ZipArchive();
        $zipFilePath = str_replace(pathinfo($filePath, PATHINFO_EXTENSION), 'zip', $filePath);

        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $fileName = basename($title);
            $zip->addFile($filePath, $fileName);
            $zip->setEncryptionName($fileName, \ZipArchive::EM_AES_256, $password);
            $zip->close();
        } else {
            throw new \Exception("Não foi possível criar o arquivo ZIP.");
        }

        return $zipFilePath;
    }

    public function getDateDocuments(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $documents = Documents::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('daily.documents', compact('documents'));
    }
}
