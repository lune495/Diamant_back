<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Patient,Outil};
class PatientQuery extends Query
{
    protected $attributes = [
        'name' => 'patients'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Patient'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom'                 => ['type' => Type::string()],
            'prenom'              => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Patient::query();
        if (isset($args['nom']))
        {
            $query = $query->where('nom',Outil::getOperateurLikeDB(),'%'.$args['nom'].'%');
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Patient $item)
        {
            return
            [
                'id'                  => $item->id,
                'nom'                 => $item->nom,
                'prenom'              => $item->prenom,
                'telephone'           => $item->telephone,
                'adresse'             => $item->adresse,
            ];
        });

    }
}
