<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Depense,ClotureCaisse};

class DepenseQuery extends Query
{
    protected $attributes = [
        'name' => 'depenses'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Depense'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom'                 => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Depense::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        $query->orderBy('id', 'asc');
        // Obtenez la date de fermeture la plus récente depuis la table ClotureCaisse
        $latestClosureDate = ClotureCaisse::orderBy('date_fermeture', 'desc')->value('date_fermeture');
        if(isset($latestClosureDate))
        {
            $query = $query->whereBetween('created_at', [$latestClosureDate, now()]);
        }
        $query = $query->get();
        return $query->map(function (Depense $item)
        {
            return
            [
                'id'                      => $item->id,
                'nom'                     => $item->nom,
                'montant'                 => $item->montant,
                'user'                    => $item->user,
                'created_at'              => $item->created_at
            ];
        });
    }
}