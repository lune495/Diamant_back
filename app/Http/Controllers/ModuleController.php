<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Outil,Module};
use Illuminate\Support\Facades\DB;

class ModuleController extends Controller
{
    //
    private $queryName = "modules";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Module();
            if (!empty($request->id))
            {
                $item = Module::find($request->id);
            }
            if (empty($request->nom))
            {
                $errors = "Renseignez le nom du module";
            }
            DB::beginTransaction();
            $item->nom = $request->nom;
            $montant = 0;
            if (!isset($errors)) 
            {
                $item->save();
                $id = $item->id;
                DB::commit();
                return  Outil::redirectgraphql($this->queryName, "id:{$id}", Outil::$queries[$this->queryName]);
            }
            if (isset($errors))
            {
                throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
            }
        } catch (\Throwable $e) {
                DB::rollback();
                return $e->getMessage();
        }
    }
}
