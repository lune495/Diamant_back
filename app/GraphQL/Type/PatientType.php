<?php
namespace App\GraphQL\Type;

use App\Models\Patient;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class PatientType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Patient',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom'                       => ['type' => Type::string()],
                'prenom'                    => ['type' => Type::string()],
                'telephone'                 => ['type' => Type::int()],
                'adresse'                   => ['type' => Type::string()],
                'suivis'                    => ['type' => Type::listOf(GraphQL::type('Suivi')), 'description' => ''],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveNomField($root, array $args)
    // {
    //     return strtolower($root->nom_complet);
    // }
}