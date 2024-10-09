<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{TypeService,Outil,Module};
use Illuminate\Support\Facades\DB;

class TypeServiceController extends Controller
{
    //
    private $queryName = "type_services";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new TypeService();
            if (!empty($request->id))
            {
                $item = TypeService::find($request->id);
            }
            if (empty($request->module_id))
            {
                $errors = "Renseignez le module";
            }
            if (empty($request->nom))
            {
                $errors = "Renseignez le nom du type de service";
            }
            if (empty($request->prix))
            {
                $errors = "Renseignez le prix";
            }
            DB::beginTransaction();
            $item->nom = $request->nom;
            $item->prix = $request->prix;
            $item->module_id = $request->module_id;
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
