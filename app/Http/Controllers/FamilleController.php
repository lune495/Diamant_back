<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{FamilleHistoire,Outil,User};

class FamilleController extends Controller
{
    //

    private $queryName = "famille_histoires";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new FamilleHistoire();
            if (!empty($request->id))
            {
                $item = FamilleHistoire::find($request->id);
            }
            if (empty($request->nom))
            {
                $errors = "Renseignez la categorie";
            }
            DB::beginTransaction();
            $item->nom = $request->nom;
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
