<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\User;

class UserPaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'userspaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('userspaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
            'role_id'                       => ['type' => Type::int()],
            'name'                          => ['type' => Type::string()],
        
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = User::query();
        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }
        if (isset($args['role_id']))
        {
            $query->where('role_id', $args['role_id']);
        }
        if (isset($args['name']))
        {
            $query->where('name',$args['name']);
        }
      
        $count = Arr::get($args, 'count', 20);
        $page  = Arr::get($args, 'page', 1);

        return $query->orderBy('created_at', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}

