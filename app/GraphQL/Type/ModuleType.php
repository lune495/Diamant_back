<?php
namespace App\GraphQL\Type;

use App\Models\Module;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;

class ModuleType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Module',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom'                       => ['type' => Type::string()],
                'type_services'             => ['type' => Type::listOf(GraphQL::type('TypeService')), 'description' => ''],
                'medecins'                  => ['type' => Type::listOf(GraphQL::type('Medecin')), 'description' => ''],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
}