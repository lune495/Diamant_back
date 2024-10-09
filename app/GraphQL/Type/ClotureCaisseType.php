<?php
namespace App\GraphQL\Type;

// use App\Models\Depense;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;
class ClotureCaisseType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'ClotureCaisse',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'date_fermeture'            => ['type' => Type::string()],
                'montant_total'             => ['type' => Type::int()],
                'user_id'                   => ['type' => Type::int()],
                'user'                      => ['type' => GraphQL::type('User')],
                'date_fermeture_fr'         => ['type' => Type::string()],
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
    protected function resolveDateFermetureFrField($root, $args)
    {
        if (!isset($root['date_fermeture_fr'])) {
            $date_fermeture_fr = $root['date_fermeture'];
        } else {
            $date_fermeture_fr = $root['date_fermeture_fr'];
        }
    
        return Carbon::parse($date_fermeture_fr)->format('d/m/Y H:i:s');
    }
}