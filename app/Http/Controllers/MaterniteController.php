<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image as Image;
use App\Models\{Maternite,Outil,User,ElementMaternites,TypeMaternite,Log};
use \PDF;


class MaterniteController extends Controller
{

    private $queryName = "maternites";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Maternite();
            $log = new Log();
            if (!empty($request->id))
            {
                $item = Maternite::find($request->id);
            }
            if (empty($request->medecin_id))
            {
                $errors = "Renseignez le Medecin";
            }
            if (empty($request->nom_complet))
            {
                $errors = "Renseignez le nom";
            }
            $str_json_type_maternite = json_encode($request->type_maternites);
            $type_maternite_tabs = json_decode($str_json_type_maternite, true);
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
                    foreach ($type_maternite_tabs as $type_maternite_tab) 
                    {
                        $tpc = TypeMaternite::find($type_maternite_tab['type_maternite_id']);
                        if (!isset($tpc)) {
                        $errors = "Type  Maternite inexistant";
                        }
                        $element_maternite = new ElementMaternites();
                        $element_maternite->maternite_id =  $id;
                        $element_maternite->type_maternite_id =  $type_maternite_tab['type_maternite_id'];
                        $element_maternite->save();
                        if($element_maternite->save())
                        {
                            $montant  = $montant + $element_maternite->type_maternite->prix;
                        }
                    }
                    $log->designation = "Maternite";
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
        $dentaire = Maternite::find($id);
        if($dentaire!=null)
        {
         $data = Outil::getOneItemWithGraphQl($this->queryName, $id, true);
        //  dd($data);
         $pdf = PDF::loadView("pdf.ticket-maternite", $data);
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
        
        return Maternite::all();

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
        return Maternite::where('titre','like','%'.$name)->get();
    }
}
