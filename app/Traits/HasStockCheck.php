<?php

namespace App\Traits;

use App\Http\Controllers\Admin\ProductAdminController;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

trait HasStockCheck
{
    // new version

    public function Stock(Product $product_info)
    {
        return $this->getStock($product_info);
    }

    public function getStock(Product $product_info)
    {
        // Inicia com estoque presumido disponível
        $stockP = 1;
        $product_info = Product::find($product_info->id);

        if ($product_info->kit != 0) {
            // Extrai as partes dos kits e inicializa a lista de estoque calculado para cada kit
            $parts = explode('|', $product_info->kit_produtos);
            $stockByProduct = $this->calculateStockForEachProduct($parts);

            // Se algum produto não tiver estoque suficiente, define stockP como 0
            if (in_array(0, $stockByProduct, true)) {
                $stockP = 0;
            } else {
                // Calcula o menor estoque disponível entre os produtos do kit
                $stockP = min($stockByProduct);
            }
        } else {
            // Para produtos individuais, verifica diretamente o estoque
            $stockP = $this->checkSingleProductStock($product_info->id);
        }

        return $stockP;
    }

    /**
     * Calcula o estoque para cada produto no kit
     *
     * @param array $parts Partes do kit
     * @return array Lista de estoques disponíveis para cada produto
     */
    private function calculateStockForEachProduct(array $parts)
    {
        $stockByProduct = [];

        foreach ($parts as $part) {
            list($id_produto, $quantidade) = explode('-', $part);
            $temStock = DB::table('stock')
                ->where('product_id', $id_produto)
                ->sum('amount');

            // Verifica se o estoque disponível atende à demanda da quantidade necessária
            if ($temStock < $quantidade) {
                $stockByProduct[] = 0; // Adiciona 0 ao estoque se não suficiente
            } else {
                // Calcula quantos kits completos podem ser feitos com o estoque disponível
                $stockByProduct[] = floor($temStock / $quantidade);
            }
        }

        return $stockByProduct;
    }

    /**
     * Verifica o estoque para um produto individual
     *
     * @param int $productId ID do produto
     * @return int Quantidade de estoque disponível
     */
    private function checkSingleProductStock($productId)
    {
        return DB::table('stock')
            ->where('product_id', $productId)
            ->sum('amount');
    }

}
