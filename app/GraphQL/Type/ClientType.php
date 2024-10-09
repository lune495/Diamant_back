<?php
namespace App\GraphQL\Type;

use App\Models\Client;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ClientType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Client',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom_complet'               => ['type' => Type::string()],
                'email'                     => ['type' => Type::string()],
                'adresse'                   => ['type' => Type::string()],
                'telephone'                 => ['type' => Type::int()]
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    protected function resolveNom_CompletField($root, array $args)
    {
        return strtolower($root->nom_complet);
    }
}