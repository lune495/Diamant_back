<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Client,Outil};
class ClientQuery extends Query
{
    protected $attributes = [
        'name' => 'clients'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Client'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom_complet'         => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Client::query();
        if (isset($args['nom_complet']))
        {
            $query = $query->where('nom_complet',Outil::getOperateurLikeDB(),'%'.$args['nom_complet'].'%');
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Client $item)
        {
            return
            [
                'id'                  => $item->id,
                'nom_complet'         => $item->nom_complet,
                'telephone'           => $item->telephone,
                'adresse'             => $item->adresse,
                'email'               => $item->email,
            ];
        });

    }
}
