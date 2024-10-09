<?php

namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Illuminate\Support\Arr;
use \App\Models\{Service,ClotureCaisse,Outil};

class ServicePaginatedQuery extends Query
{
    protected $attributes = [
        'name'              => 'servicespaginated',
        'description'       => ''
    ];

    public function type():type
    {
        return GraphQL::type('servicespaginated');
    }

    public function args():array
    {
        return
        [
            'id'                            => ['type' => Type::int()],
            'nom_complet'                   => ['type' => Type::string()],
        
            'page'                          => ['name' => 'page', 'description' => 'The page', 'type' => Type::int() ],
            'count'                         => ['name' => 'count',  'description' => 'The count', 'type' => Type::int() ]
        ];
    }


    public function resolve($root, $args)
    {
        $query = Service::query();
        if (isset($args['id']))
        {
            $query->where('id', $args['id']);
        }   
        if (isset($args['nom_complet']))
        {
            $query->where('nom_complet',Outil::getOperateurLikeDB(),'%'.$args['nom_complet'].'%');
        }
        // Obtenez la date de fermeture la plus récente depuis la table ClotureCaisse
        $latestClosureDate = ClotureCaisse::orderBy('date_fermeture', 'desc')
            ->value('date_fermeture');

        $query = $query->whereBetween('created_at', [$latestClosureDate, now()]);
      
        $count = Arr::get($args, 'count', 20);
        $page  = Arr::get($args, 'page', 1);

        return $query->orderBy('created_at', 'desc')->paginate($count, ['*'], 'page', $page);
    }
}

