<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Outil,Depense};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
class DepenseController extends Controller
{
    //
      private $queryName = "depenses";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Depense();
            $user = Auth::user();
            if (!empty($request->id))
            {
                $item = Depense::find($request->id);
            }
            if (empty($request->nom))
            {
                $errors = "Renseignez la nature de la depense";
            }
            if (empty($request->montant))
            {
                $errors = "Renseignez le montant";
            }
            DB::beginTransaction();
            $item->nom = $request->nom;
            $item->montant = $request->montant;
            $item->user_id = $user->id;
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
