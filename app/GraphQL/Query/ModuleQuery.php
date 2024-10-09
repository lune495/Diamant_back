<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Module;
use DateTime;

class ModuleQuery extends Query
{
    protected $attributes = [
        'name' => 'modules'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Module'));
    }

    public function args(): array
    {
        return
        [
            'id'                  => ['type' => Type::int()],
            'nom'                 => ['type' => Type::string()],
            'search'              => ['type' => Type::string()]
        ];
    }

    public function resolve($root, $args)
    {
        $query = Module::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }

        if (isset($args['search'])) {
            // Ajoutez une clause WHERE pour filtrer par type de service
            $query->whereHas('type_services', function ($q) use ($args) {
                $q->where('nom', 'like', '%' . $args['search'] . '%');
            });
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Module $item)
        {
            return
            [
                'id'                       => $item->id,
                'nom'                      => $item->nom,
                'type_services'            => $item->type_services,
                'medecins'                 => $item->medecins,
            ];
        });

    }
}
