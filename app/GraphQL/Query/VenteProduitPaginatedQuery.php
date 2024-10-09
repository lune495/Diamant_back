<?php

namespace App\GraphQL\Query;

use App\Models\{Outil,VenteProduit};
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
            'id'                       => ['type' => Type::int()],
            'produit_id'               => ['type' => Type::int()],
            'vente_id'                 => ['type' => Type::int()],
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

        $query->orderBy('produit_id','asc');
        $count = Arr::get($args, 'count', 20);
        $page  = Arr::get($args, 'page', 1);

        return $query->orderBy('created_at', 'desc')->paginate($count, ['*'], 'page', $page);

    }
}
