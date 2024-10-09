<?php
namespace App\GraphQL\Type;

use App\Models\{Produit,Outil,VenteProduit};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;
class VenteType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Vente',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'numero'                    => ['type' => Type::string()],
                'montant'                   => ['type' => Type::string()],
                'nom_complet'               => ['type' => Type::string()],
                'montant_avec_remise'       => ['type' => Type::float()],
                'remise_total'              => ['type' => Type::float()],
                'montant_ht'                => ['type' => Type::float()],
                'montant_ttc'               => ['type' => Type::float()],
                'montant_taxe'              => ['type' => Type::float()],
                'qte'                       => ['type' => Type::string()],
                'montantencaisse'           => ['type' => Type::string()],
                'monnaie'                   => ['type' => Type::string()],
                'statut'                    => ['type' => Type::boolean()],
                'paye'                      => ['type' => Type::boolean()],

                'user_id'                   => ['type' => Type::int()],
                'user'                      => ['type' => GraphQL::type('User')],
                'client'                    => ['type' => GraphQL::type('Client')],
                'taxe'                      => ['type' => GraphQL::type('Taxe')],
                'vente_produits'            => ['type' => Type::listOf(GraphQL::type('VenteProduit')), 'description' => ''],
                'created_at'                => ['type' => Type::string()],
                'created_at_fr'             => ['type' => Type::string()],

            ];
    }

    /*************** Pour les dates ***************/
    protected function resolveCreatedAtField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $date_at = $root->created_at;
        }
        else
        {
            $date_at = is_string($root['created_at']) ? $root['created_at'] : $root['created_at']->format(Outil::formatdate());
        }
        return $date_at;
    }
    protected function resolveMontantHtField($root, $args)
    {
        return $root['montant'];
    }
    protected function resolveMontantAvecRemiseField($root, $args)
    {
        $venteprdts = VenteProduit::where('vente_id',$root['id'])->get();
        $montant_total_vente = 0;
        foreach($venteprdts as $venteprdt){
            $montant_remise = ((($venteprdt->prix_vente * $venteprdt->qte)*$venteprdt->remise)/100);
            // dd($montant_remise);
            $montant_total_vente = $montant_total_vente + (($venteprdt->prix_vente * $venteprdt->qte)-$montant_remise);
        }
        return round($montant_total_vente);
    }
    protected function resolveRemiseTotalField($root, $args)
    {
         $venteprdts = VenteProduit::where('vente_id',$root['id'])->get();
         $remise_total = 0;
         $cpt = 0;
         foreach($venteprdts as $venteprdt){
            $cpt++;
            $remise_total = $remise_total + $venteprdt->remise;
        }
        return  round($remise_total/$cpt);
    }
    protected function resolveMontantTtcField($root, $args)
    {
        $venteprdts = VenteProduit::where('vente_id',$root['id'])->get();
        $montant_total_vente = 0;
        foreach($venteprdts as $venteprdt){
            $montant_remise = ((($venteprdt->prix_vente * $venteprdt->qte)*$venteprdt->remise)/100);
            // dd($montant_remise);
            $montant_total_vente = $montant_total_vente + (($venteprdt->prix_vente * $venteprdt->qte)-$montant_remise);
        }
        $montant_ttc = $root['taxe'] ? $montant_total_vente + (($montant_total_vente * $root['taxe']['value'])/100) : 0;
        return isset($root['taxe']) ? round($montant_ttc) : 0;
    }
    protected function resolveMontantTaxeField($root, $args)
    {
        $venteprdts = VenteProduit::where('vente_id',$root['id'])->get();
        $montant_total_vente = 0;
        foreach($venteprdts as $venteprdt){
            $montant_remise = ((($venteprdt->prix_vente * $venteprdt->qte)*$venteprdt->remise)/100);
            // dd($montant_remise);
            $montant_total_vente = $montant_total_vente + (($venteprdt->prix_vente * $venteprdt->qte)-$montant_remise);
        }
        $montant_remise = $root['taxe'] ? ($montant_total_vente * $root['taxe']['value'])/100 : 0;
        return $montant_remise;
    }
    protected function resolveCreatedAtFrField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $created_at = $root->created_at;
        }
        else
        {
            $created_at = $root['created_at'];
        }
        return Carbon::parse($created_at)->format('d/m/Y H:i:s');
    }

    protected function resolveUpdatedAtField($root, $args)
    {
        if (!isset($root['updated_at']))
        {
            $date_at = $root->updated_at;
        }
        else
        {
            $date_at = is_string($root['updated_at']) ? $root['updated_at'] : $root['updated_at']->format(Outil::formatdate());
        }
        return $date_at;
    }   

    protected function resolveUpdatedAtFrField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $date_at = $root->created_at;
        }
        else
        {
            $date_at = $root['created_at'];
        }
        return Carbon::parse($date_at)->format('d/m/Y H:i:s');
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}