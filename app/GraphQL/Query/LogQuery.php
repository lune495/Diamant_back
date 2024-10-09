<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\Log;
class LogQuery extends Query
{
    protected $attributes = [
        'name' => 'logs'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Log'));
    }

    public function args(): array
    {
        return
        [
            'id'           => ['type' => Type::int()],
            'date'         => ['type' => Type::string()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Log::query();
        $query->orderBy('id', 'desc');
        $query = $query->get();
        return $query->map(function (Log $item)
        {
            return
            [
                'id'                     => $item->id,
                'designation'            => $item->designation,
                'date'                   => $item->date,
                'prix'                   => $item->prix,
                'remise'                 => $item->remise,
                'avance'                 => $item->avance,
                'montant'                => $item->montant
            ];
        });

    }
}
