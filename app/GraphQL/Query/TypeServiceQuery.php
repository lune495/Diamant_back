<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\TypeService;
class TypeServiceQuery extends Query
{
    protected $attributes = [
        'name' => 'type_services'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('TypeService'));
    }

    public function args(): array
    {
        return
        [
            'id'                 => ['type' => Type::int()],
            'nom'                => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = TypeService::query();
        if (isset($args['id']))
        {
            $query = $query->where('id', $args['id']);
        }
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (TypeService $item)
        {
            return
            [
                'id'                      => $item->id,
                'nom'                     => $item->nom,
                'prix'                    => $item->prix,
                'module'                  => $item->module
            ];
        });

    }
}
