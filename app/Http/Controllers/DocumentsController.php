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

    public function downloadFile($id, $product_id)
    {
        $file = Documents::where("id", $id)->first();
        $product = Product::find($product_id);

        if (!$file) {
            return response()->json(['error' => 'Arquivo não encontrado.'], 404);
        }

        $filepath = storage_path("app/public/videos/{$file->content}");

        if (!file_exists($filepath)) {
            return response()->json(['error' => 'Arquivo não encontrado no armazenamento.'], 404);
        }

        $userLogin = auth()->user()->login;

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);

        switch (strtolower($extension)) {
            case 'zip':
                $newFilePath = $this->addPasswordToZip($filepath, $product->title, $userLogin);
                break;
            default:
                $newFilePath = $this->createZipWithPassword($filepath, $product->title, $userLogin);
                break;
        }

        return response()->file($newFilePath);
    }

    private function addPasswordToZip($zipFilePath, $title, $password)
    {
        $zip = new \ZipArchive();
        $username = auth()->user()->login;

        // Define o caminho temporário para salvar o arquivo protegido
        $tempDir = storage_path("app/public/videos/temp");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true); // Cria a pasta "temp" se não existir
        }

        // Define o caminho completo do arquivo temporário
        $tempPath = $tempDir . '/' . basename($zipFilePath."_".$username, '.zip') . '_secured.zip';

        if ($zip->open($zipFilePath) === true) {
            $zip->setPassword((string)$password);

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $zip->setEncryptionName($zip->getNameIndex($i), \ZipArchive::EM_AES_256, $password);
            }

            $zip->close();

            copy($zipFilePath, $tempPath);
        } else {
            throw new \Exception("Não foi possível abrir o arquivo ZIP.");
        }

        return $tempPath;
    }

    private function createZipWithPassword($filePath, $title, $password)
    {
        $zip = new \ZipArchive();
        $username = auth()->user()->login;
        $tempDir = storage_path("app/public/videos/temp");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true); // Cria a pasta "temp" se não existir
        }

        $zipFilePath = $tempDir . '/' . basename($filePath."_".$username, '.' . pathinfo($filePath, PATHINFO_EXTENSION)) . '.zip';

        // Cria o arquivo ZIP e aplica a senha
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $fileName = basename($title."_".$username);
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
