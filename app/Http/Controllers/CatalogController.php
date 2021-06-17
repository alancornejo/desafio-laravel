<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalog;

class CatalogController extends Controller
{
    public function show (Request $request)
    {
        $params = $request->all();
        $name = (array_key_exists("name", $params)) ? $params['name'] : '';
        $supplier = (array_key_exists("supplier", $params)) ? $params['supplier'] : '';

        $catalog = Catalog::select()
                            ->orWhere('name', $name)
                            ->orWhere('supplier', $supplier)
                            ->get();;

        return response()->json(['data' => $catalog]);
    }

    public function listUniqueSuppliers()
    {
        $dataSupplier = Catalog::select('supplier')
                            ->groupBy('supplier')
                            ->get();

        $supplier = array();
        foreach ($dataSupplier as &$data) {
           array_push($supplier, $data['supplier']);
        }

        $supplierRandom = $supplier[array_rand($supplier)];
        return $supplierRandom;
    }
}
