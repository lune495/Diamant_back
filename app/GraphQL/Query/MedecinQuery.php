<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Medecin;
class MedecinQuery extends Query
{
    protected $attributes = [
        'name' => 'medecins'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Medecin'));
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
        $query = Medecin::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Medecin $item)
        {
            return
            [
                'id'                      => $item->id,
                'nom'                     => $item->nom,
                'prenom'                  => $item->prenom,
                'module'                  => $item->module
            ];
        });

    }
}
