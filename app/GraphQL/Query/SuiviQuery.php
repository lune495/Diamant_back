<?php
namespace App\GraphQL\Query;

use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Query;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\Models\{Suivi, Outil};
use Illuminate\Support\Facades\Auth;

class SuiviQuery extends Query
{
    protected $attributes = [
        'name' => 'suivis'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Suivi'));
    }

    public function args(): array
    {
        return [
            'id' => ['type' => Type::int()],
        ];
    }

    public function resolve($root, $args)
    {
        $query = Suivi::query();

        if (isset($args['id'])) {
            $query = $query->where('id', $args['id']);
        }

        $query->orderBy('id', 'desc');
        $query = $query->get();

        return $query->map(function (Service $item) {
            return [
                'id'                  => $item->id,
                'patient'             => $item->patient,
                'diagnostic'          => $item->diagnostic,
                'traitement'          => $item->traitement,
                'rdv'                 => $item->rdv
            ];
        });
    }
}
