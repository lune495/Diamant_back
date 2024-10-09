<?php
namespace App\GraphQL\Type;

use App\Models\{Service,ElementService};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;
use Carbon\Carbon;

class ServiceType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Service',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
       
            [
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'nom_complet'               => ['type' => Type::string()],
                'nature'                    => ['type' => Type::string()],
                'montant'                   => ['type' => Type::int()],
                'adresse'                   => ['type' => Type::string()],
                'remise'                    => ['type' => Type::int()],
                'montant_total'             => ['type' => Type::int()],
                'medecin'                   => ['type' => GraphQL::type('Medecin')],
                'user'                      => ['type' => GraphQL::type('User')],
                'module'                    => ['type' => GraphQL::type('Module')],
                'element_services'          => ['type' => Type::listOf(GraphQL::type('ElementService')), 'description' => ''],
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

    protected function resolveMontantTotalField($root, $args)
    {
        if (!isset($root['remise']))
        {
            $remise = $root->remise;
        }
        else
        {
            $remise = $root['remise'];
        }
        $element_services = ElementService::where('service_id',$root['id'])->get();
        $montant_total = 0;
        foreach($element_services as $element_service){
            $element_service->type_service ? $montant_total = $montant_total + $element_service->type_service->prix : "";
        }
        return $montant_total - $remise;
    }
}   