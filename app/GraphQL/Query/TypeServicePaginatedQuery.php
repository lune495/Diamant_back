<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\TypeService;

class TypeServicePaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'type_servicespaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('type_servicespaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
            'nom'                           => ['type' => Type::string()],
        
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = TypeService::query();
        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }
        if (isset($args['nom']))
        {
            $query->where('nom',$args['nom']);
        }
      
        $count = Arr::get($args, 'count', 20);
        $page  = Arr::get($args, 'page', 1);

        return $query->orderBy('created_at', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}

