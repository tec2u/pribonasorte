<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Product;
use App\Models\ShippingPrice;
use Illuminate\Http\Request;

class CategoriaAdminController extends Controller
{

    public function index()
    {
        $categorias = Categoria::orderBy('sequencia', 'desc')->get();

        foreach ($categorias as $item) {
            $item->quant = $this->quantProducts($item->id);
        }

        return view('admin.categorias.index', compact('categorias'));
    }


    public function create()
    {
        return view('admin.categorias.create');
    }

    public function store(Request $request)
    {
        if (!isset($request->nome)) {
            return redirect()->back();
        }

        $exists = Categoria::where('nome', $request->nome)->first();
        if (isset($exists)) {
            return redirect()->back();
        }

        $categoriaMaiorOrdem = Categoria::orderBy('sequencia', 'desc')->first();

        $categoria = new Categoria;
        $categoria->nome = $request->nome;
        if (isset($categoriaMaiorOrdem)) {
            $categoria->sequencia = $categoriaMaiorOrdem->sequencia + 1;
        } else {
            $categoria->sequencia = 1;
        }
        $categoria->save();

        return $this->index();
    }


    public function quantProducts($id)
    {
        $categoria = Categoria::where('id', $id)->first();
        $products = Product::all();

        if (!isset($categoria)) {
            abort(404);
        }

        $quant = 0;

        foreach ($products as $item) {
            $exists = str_replace('-', '', $item->id_categoria);

            if (strpos($exists, $id) !== false) {
                $quant++;
            }
        }

        return $quant;
    }

    public function retirarCategoria($id)
    {

        $categoria = Categoria::where('id', $id)->first();
        $products = Product::all();

        if (!isset($categoria)) {
            abort(404);
        }

        foreach ($products as $item) {
            $categorias = $item->id_categoria;
            $exists = str_replace('-', '', $item->id_categoria);

            $textoSemUm = str_replace(["-$id", "$id-"], "", $categorias);
            $textoSemUm = str_replace("$id", "", $textoSemUm);


            if (strpos($exists, $id) !== false) {
                if (isset($textoSemUm) && $textoSemUm != "") {
                    $item->id_categoria = $textoSemUm;
                } else {
                    $item->id_categoria = null;
                }
            } else if ($item->id_categoria == '') {
                $item->id_categoria = null;
            }

            $item->save();
            // dd($item);
        }

    }

    public function edit($id)
    {
        $categoria = Categoria::where('id', $id)->first();
        $products = Product::all();

        if (!isset($categoria)) {
            abort(404);
        }

        foreach ($products as $item) {
            $categorias = str_replace('-', '', $item->id_categoria);

            if (strpos($categorias, $id) !== false) {
                $item->estaNaCategoria = true;
            } else {
                $item->estaNaCategoria = false;
            }
        }



        return view('admin.categorias.edit', compact('categoria', 'products'));
    }

    public function update(Request $request)
    {

        $products = [];

        $categoria = Categoria::where('id', $request->id_categoria)->first();
        $categoria->nome = $request->nome;
        $categoria->save();

        if (empty($request->image)) {
            return redirect()->back();
        }

        foreach ($request->image as $key => $value) {
            array_push($products, $value);
        }

        $this->retirarCategoria($request->id_categoria);

        foreach ($products as $value) {
            $product = Product::where('id', $value)->first();

            if (!isset($product)) {
                return redirect()->back();
            }

            if (isset($product->id_categoria)) {
                $product->id_categoria .= "-$request->id_categoria";
            } else {
                $product->id_categoria = "$request->id_categoria";
            }

            $product->save();
        }

        return redirect()->back();

    }

    public function destroy($id)
    {
        $categoria = Categoria::where('id', $id)->first();
        if (isset($categoria)) {
            $this->retirarCategoria($id);
            $categoria->delete();
        }

        return $this->index();

    }



}
