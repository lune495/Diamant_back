<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as Image;
use App\Models\{Labo2,Outil,User,ElementLabo2s,TypeLabo2,Log};

use \PDF;

class Labo2Controller extends Controller
{

    private $queryName = "labo2s";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Labo2();
            $log = new Log();
            if (!empty($request->id))
            {
                $item = Labo2::find($request->id);
            }
            if (empty($request->medecin_id))
            {
                $errors = "Renseignez le Medecin";
            }
            if (empty($request->nom_complet))
            {
                $errors = "Renseignez le nom";
            }
            $str_json_type_labo2 = json_encode($request->type_labo2s);
            $type_labo2_tabs = json_decode($str_json_type_labo2, true);
            DB::beginTransaction();
            $item->nom_complet = $request->nom_complet;
            $item->adresse = $request->adresse;
            $item->remise = $request->remise;
            $item->medecin_id = $request->medecin_id;
            $item->user_id = $request->user_id;
            $montant = 0;
            if (!isset($errors)) 
            {
                $item->save();
                $id = $item->id;
                if($item->save())
                {
                    foreach ($type_labo2_tabs as $type_labo2_tab) 
                    {
                        $tpc = TypeLabo2::find($type_labo2_tab['type_labo2_id']);
                        if (!isset($tpc)) {
                        $errors = "Type  Labo2 inexistant";
                        }
                        $element_labo2 = new ElementLabo2s();
                        $element_labo2->labo2_id =  $id;
                        $element_labo2->type_labo2_id =  $type_labo2_tab['type_labo2_id'];
                        $element_labo2->save();
                        if($element_labo2->save())
                        {
                            $montant  = $montant + $element_labo2->type_labo2->prix;
                        }
                    }
                    $log->designation = "Labo2";
                    $log->id_evnt = $id;
                    $log->date = $item->created_at;
                    $log->prix = $montant;
                    $log->remise = $item->remise;
                    $log->montant = isset($item->montant) ? $item->montant : 0;
                    $log->save();
                }
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

    public function generatePDF($id)
    {
        $dentaire = Labo2::find($id);
        if($dentaire!=null)
        {
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, true);
        //  dd($data);
         $pdf = PDF::loadView("pdf.ticket-labo2", $data);
        $measure = array(0,0,225.772,650.197);
        return $pdf->setPaper($measure, 'orientation')->stream();
            //  return $pdf->stream();
        }else{
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, false);
            return view('notfound');
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        return Labo2::all();

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

     /**
     * Search for a name.
     * @param str $name
     */
    public function search($name)
    {
        //
        return Labo2::where('titre','like','%'.$name)->get();
    }
}
