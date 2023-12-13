<?php

namespace App\GraphQL\Queries;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use Auth;

class UserQuery extends Query
{
    protected $attributes = [
        'name' => 'user',
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function authorize(array $args): bool
    {
        // true, if logged in
        return ! Auth::guest();
    }


    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return User::findOrFail($args['id']);
    }
}