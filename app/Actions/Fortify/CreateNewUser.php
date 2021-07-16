<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Video;
use GuzzleHttp\Utils;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array $input
     * @return User
     * @throws ValidationException
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'description' => Arr::exists($input, 'description') ? $input['description'] : '',
            'email' => $input['email'],
            'tags' => Utils::jsonEncode($input['tags']),
            'password' => Hash::make($input['password']),
        ]);

//        Video::create([
//            'title' => $input['name'],
//            'link' => $input['intro_video'],
//            'user_id' => $user->id,
//            'is_profile' => true
//        ]);

        return $user;
    }
}
