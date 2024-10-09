<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Rebing\GraphQL\Support\Facades\GraphQL;

class ElementServicePaginatedType extends GraphQLType
{
    protected $attributes =
    [
        'name' => 'element_servicepaginated'
    ];
    public function fields(): array
    {  return
        [
            'metadata' =>
            [
                'type' => GraphQL::type('Metadata'),
                'resolve' => function ($root)
                {
                    return array_except($root->toArray(), ['data']);
                }
            ],
            'data' =>
            [
                'type' => Type::listOf(GraphQL::type('ElementService')),
                'resolve' => function ($root)
                {
                    return $root;
                }
            ]
        ];
    }
}
