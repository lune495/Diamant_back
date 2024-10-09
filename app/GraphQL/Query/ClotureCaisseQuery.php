<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{ClotureCaisse,Outil};
class ClotureCaisseQuery extends Query
{
    protected $attributes = [
        'name' => 'cloturecaisses'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('ClotureCaisse'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = ClotureCaisse::query();
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (ClotureCaisse $item)
        {
            return
            [
                'id'                  => $item->id,
                'date_fermeture'      => $item->date_fermeture,
                'date_fermeture_fr'   => $item->date_fermeture_fr,
                'montant_total'       => $item->montant_total,
                'user'                => $item->user,
            ];
        });

    }
}
