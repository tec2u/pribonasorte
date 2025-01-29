<?php

namespace App\Http\Controllers;

use App\Models\Documents;
use App\Models\EcommOrders;
use App\Models\Product;
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
                $newFilePath = $this->addPasswordToZip($filepath, $product->name, $userLogin);
                break;
            default:
                $newFilePath = $this->createZipWithPassword($filepath, $product->name, $userLogin);
                break;
        }

        $downloadFileName = $product->name . ".zip";

        $newFilePath = $this->addMetadataToFile($newFilePath, $userLogin);

        // Retorna o arquivo para download com o nome definido
        return response()->download($newFilePath, $downloadFileName);
    }

    function addMetadataToFile($filePath, $userLogin)
    {
        $outputFile = storage_path("app/public/temp/modified_" . basename($filePath));

        // ExifTool adicionando metadados ao arquivo
        $command = "exiftool -overwrite_original -Comment='Baixado por: {$userLogin}' -User='Sistema Laravel' -Copyright='Seu Sistema' -o {$outputFile} {$filePath}";

        shell_exec($command);

        return $outputFile;
    }

    private function addPasswordToZip($zipFilePath, $title, $password)
    {
        $zip = new \ZipArchive();
        $username = auth()->user()->login;

        // Diretório temporário onde o arquivo com senha será salvo
        $tempDir = storage_path("app/public/videos/temp");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true); // Cria a pasta "temp" se não existir
        }
        $this->clearTempFiles($tempDir, $username);

        // Caminho do arquivo ZIP temporário com senha
        $tempPath = $tempDir . '/' . "{$username}_{$title}_secured.zip";

        // Cria o arquivo ZIP com senha
        if ($zip->open($tempPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $fileName = basename($zipFilePath); // Apenas o nome do arquivo
            $zip->addFile($zipFilePath, $fileName); // Adiciona o arquivo ao ZIP

            // Aplica a senha no arquivo dentro do ZIP
            $zip->setEncryptionName($fileName, \ZipArchive::EM_AES_256, $password);

            $zip->close(); // Fecha o ZIP
        } else {
            throw new \Exception("Não foi possível criar o arquivo ZIP.");
        }

        return $tempPath; // Retorna o caminho do arquivo com senha
    }

    private function createZipWithPassword($filePath, $title, $password)
    {
        $zip = new \ZipArchive();
        $username = auth()->user()->login;

        // Diretório temporário onde o arquivo com senha será salvo
        $tempDir = storage_path("app/public/videos/temp");
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true); // Cria a pasta "temp" se não existir
        }
        $this->clearTempFiles($tempDir, $username);

        // Caminho do arquivo ZIP temporário com senha
        $zipFilePath = $tempDir . '/' . "{$username}_{$title}_secured.zip";

        // Cria o arquivo ZIP com senha
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $fileName = basename($filePath); // Apenas o nome do arquivo
            $zip->addFile($filePath, $fileName); // Adiciona o arquivo ao ZIP

            // Verifica se o arquivo foi adicionado corretamente
            if ($zip->locateName($fileName) !== false) {
                // Aplica a senha no arquivo dentro do ZIP
                $zip->setEncryptionName($fileName, \ZipArchive::EM_AES_256, $password);
            } else {
                $zip->close();
                throw new \Exception("O arquivo não foi adicionado corretamente ao ZIP.");
            }

            $zip->close(); // Fecha o ZIP
        } else {
            throw new \Exception("Não foi possível criar o arquivo ZIP.");
        }

        return $zipFilePath; // Retorna o caminho do arquivo ZIP com senha
    }

    private function clearTempFiles($directory, $username)
    {
        $files = glob($directory . '/' . $username . '_*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public function getDateDocuments(Request $request)
    {

        $fdate = $request->get('fdate') . " 00:00:00";
        $sdate = $request->get('sdate') . " 23:59:59";

        $documents = Documents::where('created_at', '>=', $fdate)->where('created_at', '<=', $sdate)->paginate(9);

        return view('daily.documents', compact('documents'));
    }
}
