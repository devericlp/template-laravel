<?php

namespace App\Actions\Users;

use App\Actions\StoreImage;
use App\Models\User;

/**
 * Function to handle user creation
 *
 * @param  array  $data
 */
class CreateUser
{
    public function handle(array $data): void
    {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->tenant_id = $data['tenant_id'];
        $user->email_verified_at = now();

        if ($data['avatar']) {

            $extension = $data['avatar']->getClientOriginalExtension();

            $user->avatar = (new StoreImage)->handle(
                file: $data['avatar'],
                path: 'users',
                extension: $extension
            );
        }

        $user->save();

        $user->assignRole($data['role_id']);
    }
}
