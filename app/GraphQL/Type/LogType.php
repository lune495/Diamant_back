<?php
namespace App\GraphQL\Type;

use App\Models\Log;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class LogType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Log',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'designation'               => ['type' => Type::string()],
                'id_evnt'                   => ['type' => Type::int()],
                'date'                      => ['type' => Type::string()],
                'prix'                      => ['type' => Type::int()],
                'remise'                    => ['type' => Type::int()],
                'avance'                    => ['type' => Type::int()],
                'montant'                   => ['type' => Type::int()],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}