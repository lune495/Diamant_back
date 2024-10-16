<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Suivi,Outil};

class SuiviController extends Controller
{
    //

    private $queryName = "suivis";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Suivi();
            if (!empty($request->id))
            {
                $item = Suivi::find($request->id);
            }
            // if (empty($request->nom))
            // {
            //     $errors = "Renseignez la categorie";
            // }
            DB::beginTransaction();
            $item->patient_id = $request->patient_id;
            $item->diagnostic = $request->diagnostic;
            $item->traitement = $request->traitement;
            $item->rdv = $request->rdv;
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
