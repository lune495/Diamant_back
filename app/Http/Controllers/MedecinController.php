<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Outil,Medecin};
use Illuminate\Support\Facades\DB;

class MedecinController extends Controller
{
    //
    private $queryName = "medecins";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Medecin();
            if (!empty($request->id))
            {
                $item = Medecin::find($request->id);
            }
            if (empty($request->nom))
            {
                $errors = "Renseignez le nom du medecin";
            }
            if (empty($request->prenom))
            {
                $errors = "Renseignez le prenom du medecin";
            }
            if (empty($request->module_id))
            {
                $errors = "Renseignez le service";
            }
            DB::beginTransaction();
            $item->nom = $request->nom;
            $item->prenom = $request->prenom;
            $item->module_id = $request->module_id;
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
