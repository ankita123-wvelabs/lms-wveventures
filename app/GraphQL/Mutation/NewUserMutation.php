<?php

    namespace App\GraphQL\Mutation;

    use GraphQL;
    use App\Models\User;
    use GraphQL\Type\Definition\Type;
    use Rebing\GraphQL\Support\Mutation;

    class NewUserMutation extends Mutation
    {
      protected $attributes = [
        'name' => 'newUser'
      ];

    public function rules(array $args = []): array
    {
        return [
            'name' => [
                'required', 'max:50'
            ],
            'email' => [
                'required', 'email', 'unique:users,email', 
            ],
            'password' => [
                'required', 'string', 'min:5'
            ],
        ];
    }

    public function validationErrorMessages(array $args = []): array
    {
        return [
            'name.required' => 'Please enter your full name',
            'name.string' => 'Your name must be a valid string',
            // 'email.required' => 'Please enter your email address',
            // 'email.email' => 'Please enter a valid email address',
            // 'email.exists' => 'Sorry, this email address is already in use',                     
        ];
    }
    // public function rules(array $args = [])
    // {
    //     return [
    //         'id' => [
    //             'required', 'numeric', 'min:1', 'exists:users,id'
    //         ],
    //     ];
    // }

      public function type(): Type
      {
        return GraphQL::type('User');
      }

      public function args(): array
      {
        return [
          'name' => [
            'name' => 'name',
            'type' => Type::nonNull(Type::string()),
            'rules' => ['required'],
          ],
          'email' => [
            'name' => 'email',
            'type' => Type::nonNull(Type::string()),
            'rules' => ['required'],
          ],
          'password' => [
            'name' => 'password',
            'type' => Type::nonNull(Type::string()),
            'rules' => ['required'],
          ],
        ];
      }

      public function authenticated($root, $args, $currentUser)
      {
        return !!$currentUser;
      }

      public function resolve($root, $args)
      {
        $user = new User();
        $user->fill($args);
        // $user->name = $args['name'];
        // $user->email = $args['email'];
        // $user->password = $args['password'];  
        $user->save();

        return $user;
      }
    }