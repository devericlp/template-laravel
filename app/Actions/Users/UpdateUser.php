<?php

namespace App\Actions\Users;

use App\Actions\StoreImage;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

/**
 * Function to handle user update
 *
 * @param array $data
 */
class UpdateUser
{
    public function handle(User $user, array $data): void
    {

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
        ];

        if (!is_null($data['tenant_id'])) {
            $updateData['tenant_id'] = $data['tenant_id'];
        }

        if (!is_null($data['password'])) {
            $updateData['password'] = $data['password'];
        }

        // if the avatar is being updated
        if ($data['avatar']) {

            $extension = $data['avatar']->getClientOriginalExtension();

            $updateData['avatar'] = (new StoreImage())->handle(
                file: $data['avatar'],
                path: 'users',
                extension: $extension
            );
        }

        if (is_null($data['avatar']) && $user->avatar) {
            if (Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $updateData['avatar'] = null;
        }

        $user->update($updateData);

        // Update role
        $user->syncRoles($data['role_id']);
    }
}
