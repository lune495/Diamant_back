<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\ClotureCaisse;

class ClotureCaissePaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'cloturecaissePaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('cloture_caissepaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
        
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = ClotureCaisse::query();
      
       $count = Arr::get($args, 'count', 20);
       $page  = Arr::get($args, 'page', 1);
       return $query->orderBy('id')->paginate($count, ['*'], 'page', $page);
    }
}

