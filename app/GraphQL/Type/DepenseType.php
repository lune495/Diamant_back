<?php
namespace App\GraphQL\Type;

use App\Models\Depense;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;
class DepenseType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Depense',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom'                       => ['type' => Type::string()],
                'montant'                    => ['type' => Type::string()],
                'user'                      => ['type' => GraphQL::type('User')],
                'created_at'                => ['type' => Type::string()],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
     protected function resolveCreatedAtField($root, $args)
    {
        if (!isset($root['created_at']))
        {
            $created_at = $root->created_at;
        }
        else
        {
            $created_at = $root['created_at'];
        }
        return Carbon::parse($created_at)->format('d/m/Y H:i:s');
    }
}