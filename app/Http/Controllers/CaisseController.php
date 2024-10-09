<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image as Image;
use App\Models\{Service,Outil,User,Produit,Module,ElementService,Log,TypeService,ClotureCaisse,Depense,Vente};
use \PDF;
use App\Events\MyEvent;
use \DNS1D;
use Illuminate\Support\Facades\Storage;

class CaisseController extends Controller
{
    private $queryName = "services";

    public function save(Request $request)
    {
        try 
        {
            $errors =null;
            $item = new Service();
            $log = new Log();
            $user = Auth::user();
            if (!empty($request->id))
            {
                $item = Service::find($request->id);
            }
            if (empty($request->medecin_id))
            {
                $errors = "Renseignez le Medecin";
            }
            if (empty($request->nom_complet))
            {
                $errors = "Renseignez le nom";
            }
            $str_json_type_service = json_encode($request->type_services);
            $type_service_tabs = json_decode($str_json_type_service, true);

            // Ajoutez un verrouillage de la table factice pour éviter les opérations concurrentes.
            DB::table('service_locks')->lockForUpdate()->get();
            DB::beginTransaction();
            $item->nom_complet = $request->nom_complet;
            $item->nature = $request->nature;
            $item->montant = $request->montant;
            $item->adresse = $request->adresse;
            $item->remise = $request->remise;
            $item->medecin_id = $request->medecin_id;
            $item->module_id = $request->module_id;
            $item->user_id = $user->id;
            $montant = 0;
            if (!isset($errors)) 
            {
                $item->save();
                $id = $item->id;
                if($item->save())
                {
                    foreach ($type_service_tabs as $type_service_tab) 
                    {
                        $tpc = TypeService::find($type_service_tab['type_service_id']);
                        if (!isset($tpc)) {
                        $errors = "Type  Service inexistant";
                        }
                        $element_service = new ElementService();
                        $element_service->service_id =  $id;
                        $element_service->type_service_id =  $type_service_tab['type_service_id'];
                        $element_service->save();
                        if($element_service->save())
                        {
                            $montant  = $montant + $element_service->type_service->prix;
                        }
                    }
                    $log->designation = $item->module->nom;
                    $log->id_evnt = $id;
                    $log->date = $item->created_at;
                    $log->prix = $montant;
                    $log->remise = $item->remise;
                    $log->montant = $item->montant;
                    $log->user_id = $user->id;
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
    public function closeCaisse(Request $request)
    {
        try {
            // Calculez le montant total de la caisse à la fermeture (par exemple, en ajoutant les montants des consultations non facturées)
            // $totalCaisse = $request->montant_total;
            $errors =null;
            $montant = 0;
            $allDatesNotNull = DB::table('cloture_caisses')->whereNull('date_fermeture')->doesntExist();
            $count = DB::table('cloture_caisses')->count();
            if ($count === 0) {
                $logs = DB::table('logs')
                    ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                    // ->where(function ($query) {
                    //     $query->where('created_at', '>', function ($subQuery) {
                    //         $subQuery->select('date_fermeture')
                    //             ->from('cloture_caisses')
                    //             ->whereNotNull('date_fermeture')
                    //             ->orderByDesc('date_fermeture')
                    //             ->limit(1);
                    //     });
                    // })
                    ->where('created_at', '<=', now())
                    ->groupBy('designation')
                    ->orderBy('designation')
                    ->get();
            } else {
                $logs = DB::table('logs')
                    ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                    ->where(function ($query) {
                        $query->where('created_at', '>=', function ($subQuery) {
                            $subQuery->select('date_fermeture')
                                ->from('cloture_caisses')
                                ->orderByDesc('date_fermeture')
                                ->limit(1);
                        });
                    })
                    ->where('created_at', '<=', now())
                    ->groupBy('designation')
                    ->orderBy('designation')
                    ->get();
            }        
            foreach ($logs as $log){

                $montant = $montant + $log->total_prix;
            }
            if ($montant == 0)
            {
                $errors = "Vous pouvez pas cloturer une caisse Vide";
            }
            $user = Auth::user();   
            // Enregistrez les détails de la clôture de caisse
            if (isset($errors))
            {
                throw new \Exception('{"data": null, "errors": "'. $errors .'" }');
            }
            $caisseCloture = new ClotureCaisse();
            $caisseCloture->date_fermeture = now(); // Ou utilisez la date/heure appropriée
            $caisseCloture->montant_total = $montant;
            $caisseCloture->user_id = $user->id;
            $caisseCloture->save();

            return response()->json(['message' => 'Caisse fermée avec succès.']);
        } catch (\Throwable $e) {
            return $e->getMessage();
            // return response()->json(['error' => 'Une erreur est survenue lors de la clôture de la caisse.']);
        }
    }
    
    public function Notif()
    {
        // event(new MyEvent("Hello"));
        dd("test");
    }

    public function generatePDF($id)
    {
        $results = [];
        $service = Service::with(['user','medecin','module','element_services.type_service'])->find($id);
        $results['nom_module'] = Module::find($service->module_id)->nom ?? '';
        if($service!=null)
        {
         $results['service'] = $service;
         $pdf = PDF::loadView("pdf.ticket-service", $results);
         $measure = array(0,0,225.772,650.197);
         return $pdf->setPaper($measure, 'orientation')->stream();
         //  return $pdf->stream();
        }
    }
    public function generatePDF3($id)
    {
        $results = [];
        $queryName = "ventes";
        $vente = Vente::find($id);
        if($vente!=null)
        {
        $results['vente'] = $vente;
        $pdf = PDF::loadView("pdf.ticket-pharmacie", $results);
        $measure = array(0,0,225.772,650.197);
        return $pdf->setPaper($measure, 'orientation')->stream();
        }else{
            return view('notfound');
        }
    }

    public function generateHistorique($module_id,$start=null,$end=null)
    {
        $results = [];
        $latestClosureDate = DB::table('cloture_caisses')
                ->select(DB::raw('MAX(date_fermeture) AS latest_date_fermeture'))
                ->whereNotNull('date_fermeture')
                ->first();
        $latestClosureDate = $latestClosureDate->latest_date_fermeture;
        // Si $start n'est pas fourni, utiliser $latestClosureDate
        if (is_null($start)) {
            $start = $latestClosureDate;
        }

        // Si $end n'est pas fourni, utiliser la date actuelle
        if (is_null($end)) {
            $end = now();
        }
        $data = Service::with(['user','medecin','module','element_services.type_service'])
                          ->where('module_id',$module_id)->whereBetween('created_at', [$start, $end])->get();
        $results['nom_module'] = Module::find($module_id)->nom ?? '';
        $results['data'] = $data;
        $results['derniere_date_fermeture'] = $start;
        $results['current_date'] = $end;
        $pdf = PDF::loadView("pdf.historique-pdf",$results);
        return $pdf->stream();

    }

    public function statutPDFpharmacie($id)
    {
        $vente = Vente::find($id);
        if($vente!=null)
        {
            if($vente->paye != 1){
                $ventes = $vente->vente_produits()->get();
                foreach ($ventes as $key => $vt) {
                    $produit = Produit::find($vt->produit_id);
                    $produit->qte = isset($produit) ? $produit->qte - $vt->qte : $produit->qte;
                    $produit->save();
                }
                $vente->paye = 1;
                $vente->save();
                event(new MyEvent($vente));
            }
            $log = Log::where('id_evnt',$id)->where('designation','pharmacie')->first();
            if($log!=null)
            {
                $log->statut_pharma = false;
                $log->save();
            }
        }   
    }

    public function generatePDF2()
    {
        // Calculez le montant total de la caisse à la fermeture (par exemple, en ajoutant les montants des consultations non facturées)
        // $totalCaisse = $request->montant_total;
        $errors =null;
        $montant = 0;
        $results = [];
        $count = DB::table('cloture_caisses')->count();
        if ($count === 0) {
            $data = DB::table('logs')
                ->select('designation',DB::raw('SUM(prix) AS total_prix'))
                ->where('created_at','>',"1900-09-08 19:16:39")
                ->where('created_at','<=',now())
                ->where('statut_pharma','=','false')
                ->groupBy('designation')
                ->orderBy('designation')
                ->get()
                ->toArray();
                $latestClosureDate = now()->format('Y-m-d H:i:s');
                // Depense
                $depenses = DB::table('depenses')
                ->orderBy('id', 'asc')
                ->where('created_at','>',"1900-09-08 19:16:39")
                ->where('created_at','<=',now())
                ->get();
                $results['data'] = $data;
                $results['depenses'] = $depenses;
                $results['derniere_date_fermeture'] = $latestClosureDate;
                $results['current_date'] = now()->format('Y-m-d H:i:s');
                // dd($results);
        } else {
            $data = DB::table('logs')
                ->select('designation', DB::raw('SUM(prix - remise) AS total_prix'))
                ->where(function ($query) {
                    $query->where('created_at', '>=', function ($subQuery) {
                        $subQuery->select('date_fermeture')
                            ->from('cloture_caisses')
                            ->orderByDesc('date_fermeture')
                            ->limit(1);
                    });
                })
                ->where('created_at', '<=', now())
                ->where('designation','!=','pharmacie')
                ->groupBy('designation')
                ->orderBy('designation')
                ->get();

                $latestClosureDate = DB::table('cloture_caisses')
                ->select(DB::raw('MAX(date_fermeture) AS latest_date_fermeture'))
                ->whereNotNull('date_fermeture')
                ->first();
                //dd($latestClosureDate);

                // PHARMACIE
                $pharmacie = DB::table('ventes')
                ->select(DB::raw('SUM(montant) AS montant'))
                ->where('paye',true)
                ->whereBetween('created_at', [$latestClosureDate ? $latestClosureDate->latest_date_fermeture : "0000-00-00 00:00:00", now()])
                ->get();
                $pharmacie = $pharmacie->first()->montant;
                // Depense
                $depenses = DB::table('depenses')
                ->orderBy('id', 'asc')
                ->whereBetween('created_at', [$latestClosureDate ? $latestClosureDate->latest_date_fermeture : "0000-00-00 00:00:00", now()])
                ->get();
                $results['data'] = $data;
                $results['pharmacie'] = $pharmacie;
                $results['depenses'] = $depenses;
                $results['derniere_date_fermeture'] = $latestClosureDate->latest_date_fermeture;
                $results['current_date'] = now()->format('Y-m-d H:i:s');
                //dd($results);

                // Sortir manuellement la situation
                // $data = DB::table('logs')
                // ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                // // ->where(function ($query) {
                // //     $query->where('created_at', '>=', function ($subQuery) {
                // //         $subQuery->select('date_fermeture')
                // //             ->from('cloture_caisses')
                // //             ->orderByDesc('date_fermeture')
                // //             ->limit(1);
                // //     });
                // // })
                // // ->where('created_at', '<=', now())
                // ->whereBetween('created_at', ["2024-07-05 14:03:58", "2024-07-06 07:43:07"])
                // // ->where('statut_pharma','=',false)
                // ->where('designation', '!=', 'pharmacie')
                // ->groupBy('designation')
                // ->orderBy('designation')
                // ->get();

                // // $latestClosureDate = DB::table('cloture_caisses')
                // // // ->select(DB::raw('MAX(date_fermeture) AS latest_date_fermeture'))
                // // // ->whereNotNull('date_fermeture')
                // // // ->first();
                // // ->orderBy('id', 'asc')
                // // ->whereBetween('date_fermeture', ["2024-07-05 14:03:58", "2024-07-06 07:43:07"])
                // // ->get();

                // //     // PHARMACIE
                // $pharmacie = DB::table('ventes')
                // ->select(DB::raw('SUM(montant) AS montant'))
                // ->where('statut',false)
                // ->whereBetween('created_at', ["2024-07-05 14:03:58", "2024-07-06 07:43:07"])
                // ->get();
                // $pharmacie = $pharmacie->first()->montant;
                // //dd($latestClosureDate);
                // // Depense
                // $depenses = DB::table('depenses')
                // ->orderBy('id', 'asc')
                // ->whereBetween('created_at', ["2024-07-05 14:03:58", "2024-07-06 07:43:07"])
                // ->get();
                // $results['data'] = $data;
                // $results['pharmacie'] = $pharmacie;
                // $results['depenses'] = $depenses;
                // $results['derniere_date_fermeture'] = "2024-07-05 14:03:58";
                // $results['current_date'] = "2024-07-06 07:43:07";
         }
        $pdf = PDF::loadView("pdf.situation-pdf",$results);
        return $pdf->stream();
    }
    public function FiltreSituationParDate($start,$end)
    {
        // Sortir manuellement la situation
                $data = DB::table('logs')
                ->select('designation', DB::raw('SUM(prix) AS total_prix'))
                // ->where(function ($query) {
                //     $query->where('created_at', '>=', function ($subQuery) {
                //         $subQuery->select('date_fermeture')
                //             ->from('cloture_caisses')
                //             ->orderByDesc('date_fermeture')
                //             ->limit(1);
                //     });
                // })
                // ->where('created_at', '<=', now())
                ->whereBetween('created_at', [$start, $end])
                // ->where('statut_pharma','=',false)
                ->where('designation', '!=', 'pharmacie')
                ->groupBy('designation')
                ->orderBy('designation')
                ->get();

                // $latestClosureDate = DB::table('cloture_caisses')
                // // ->select(DB::raw('MAX(date_fermeture) AS latest_date_fermeture'))
                // // ->whereNotNull('date_fermeture')
                // // ->first();
                // ->orderBy('id', 'asc')
                // ->whereBetween('date_fermeture', ["2024-07-05 14:03:58", "2024-07-06 07:43:07"])
                // ->get();

                //     // PHARMACIE
                $pharmacie = DB::table('ventes')
                ->select(DB::raw('SUM(montant) AS montant'))
                ->where('statut',false)
                ->whereBetween('created_at', [$start, $end])
                ->get();
                $pharmacie = $pharmacie->first()->montant;
                //dd($latestClosureDate);
                // Depense
                $depenses = DB::table('depenses')
                ->orderBy('id', 'asc')
                ->whereBetween('created_at', [$start, $end])
                ->get();
                $results['data'] = $data;
                $results['pharmacie'] = $pharmacie;
                $results['depenses'] = $depenses;
                $results['derniere_date_fermeture'] = $start;
                $results['current_date'] = $end;
            $pdf = PDF::loadView("pdf.situation-pdf",$results);
            return $pdf->stream();
    }
    public function SituationParFiltreDate($start)
    {
        // dd($start);
        $data = DB::table('cloture_caisses')
        ->select('*')
        ->where('created_at', '>=', function ($query) use ($start) {
            $query->selectRaw('MIN(created_at)')
                ->from('cloture_caisses')
                ->whereDate('created_at', '=', $start);
        })
        ->where('created_at', '<', function ($query) use ($start) {
            $query->selectRaw('MIN(created_at)')
                ->from('cloture_caisses')
                ->where('created_at', '>', "{$start}");
        })
        ->orderBy('created_at')
        ->get();
        dd($data);
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


    public function generatePDF6(Request $request)
    {
        $numBarcodes = $request->input('num_barcodes', 10); // Par défaut 10 codes-barres si non spécifié
        $barcodes = [];

        for ($i = 0; $i < 10; $i++) {
            // Générer un code-barres unique, par exemple un nombre aléatoire de 12 chiffres
            $barcode = str_pad(mt_rand(0, 999999999999), 12, '0', STR_PAD_LEFT);
            $barcodes[] = $barcode;
        }

        $barcodeImages = [];
        foreach ($barcodes as $barcode) {
            // Créer un chemin pour chaque image dans le sous-répertoire 'barcodes'
            $imagePath = 'barcodes/' . $barcode . '.png';
            $barcodeImage = DNS1D::getBarcodePNG($barcode, 'C39');

            // Stocker l'image
            Storage::disk('public')->put($imagePath, base64_decode($barcodeImage));

            // Ajouter le chemin complet de l'image dans le tableau
            $barcodeImages[] = [
                'code' => $barcode,
                'image' => public_path('storage/' . $imagePath),
            ];
        }

        // Générer le PDF
        $pdf = PDF::loadView('pdf.barcode_pdf', compact('barcodeImages'));
        return $pdf->stream();
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
        return Consultation::where('titre','like','%'.$name)->get();
    }
}