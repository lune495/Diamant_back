<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Dossier};

class DossierQuery extends Query
{
    protected $attributes = [
        'name' => 'dossiers'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Dossier'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'numero'              => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Dossier::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        $query->orderBy('id', 'asc');
        $query = $query->get();

        return $query->map(function (Dossier $item)
        {
            return
            [
                'id'                      => $item->id,
                'numero'                  => $item->numero,
                'patient'                 => $item->patient,
                'created_at'              => $item->created_at
            ];
        });
    }
}