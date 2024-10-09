<?php
namespace App\GraphQL\Type;

use App\Models\ElementService;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ElementServiceType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'ElementService',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                         => ['type' => Type::id(), 'description' => ''],
                'service'                    => ['type' => GraphQL::type('Service')],
                'type_service'               => ['type' => GraphQL::type('TypeService')],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}