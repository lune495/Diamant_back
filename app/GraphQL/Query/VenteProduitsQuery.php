<?php

namespace App\GraphQL\Query;

use App\Models\VenteProduit;
use  App\Models\Outil;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;

class VenteProduitsQuery extends Query
{
    protected $attributes =
    [
        'name' => 'venteproduits'
    ];

    public function type():type
    {
        return Type::listOf(GraphQL::type('VenteProduit'));
    }

    public function args():array
    {
        return
        [
            'id'                => ['type' => Type::int()],
            'vente_id'          => ['type' => Type::int()],
            'produit_id'        => ['type' => Type::int()]

        ];
    }

    public function resolve($root, $args)
    {
        $query = VenteProduit::with('vente');
        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }
        if (isset($args['vente_id']))
        {
            $query->where('vente_id', $args['vente_id']);
        }
        if (isset($args['produit_id']))
        {
            $query->where('produit_id', $args['produit_id']);
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();

        return $query->map(function (VenteProduit $item)
        {
            return
            [
                'id'                                                              => $item->id,
                'vente_id'                                                        => $item->vente_id,
                'produit_id'                                                      => $item->produit_id,
                'qte'                                                             => $item->qte,
                'total'                                                           => $item->total,
                'remise'                                                          => $item->remise,
                'pu_net'                                                          => $item->pu_net,
                'montant_net'                                                     => $item->montant_net,
                'montant_remise'                                                  => $item->montant_remise,
                'vente'                                                           => $item->vente,
                'produit'                                                         => $item->produit,
                'created_at'                                                      => $item->created_at->format(Outil::formatdate()),
                'updated_at'                                                      => $item->updated_at->format(Outil::formatdate()),
                'deleted_at'                                                      => empty($item->deleted_at) ? $item->deleted_at : $item->deleted_at->format(Outil::formatdate()),
            ]   ;
        });

    }
}
