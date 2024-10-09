<?php
namespace App\GraphQL\Type;

use App\Models\{Produit,Outil};
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Type as GraphQLType;

class ProduitType extends GraphQLType
{
    protected $attributes = [
        'name'          => 'Produit',
        'description'   => ''
    ];

    public function fields(): array
    {
       return
            [ 
                'id'                        => ['type' => Type::id(), 'description' => ''],
                'image'                     => ['type' => Type::string()],
                'code'                      => ['type' => Type::string()],
                'designation'               => ['type' => Type::string()],
                'description'               => ['type' => Type::string()],
                'pa'                        => ['type' => Type::int()],
                'pv'                        => ['type' => Type::int()],
                'qte'                       => ['type' => Type::int()],
                'limite'                    => ['type' => Type::int()],

                'capital'                   => ['type' => Type::string()],
                'nbr_produit'               => ['type' => Type::string()],
            ];
    }

    // You can also resolve a field by declaring a method in the class
    // with the following format resolve[FIELD_NAME]Field()
    // protected function resolveEmailField($root, array $args)
    // {
    //     return strtolower($root->email);
    // }
    protected function resolveCapitalField($root, array $args)
    {
        $produits = Produit::all();
        $capital = 0;
        foreach ($produits as $produit)
        {
            $capital = $capital + ($produit->pa * $produit->qte);
        }
        $capital = Outil::formatPrixToMonetaire($capital, false, true);
        return $capital;    
    }
    protected function resolveNbrProduitField($root, array $args)
    {
        $productCount = Produit::count();
        return $productCount;    
    }
    protected function resolvePaField($root, array $args)
    {
        
        return $root['pa']==null ? 0 : $root['pa'];
    }
    protected function resolveQteField($root, array $args)
    {
        
        return $root['qte']==null ? 0 : $root['qte'];
    }
    protected function resolvePvField($root, array $args)
    {
        
        return $root['pv']==null ? 0 : $root['pv'];
    }
    protected function resolveImageField($root, array $args)
    {
        return $root['image'] ? $root['image'] : null;
    }
}