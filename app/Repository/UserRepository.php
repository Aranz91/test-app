<?php

namespace App\Repository;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function update(array $data)
    {
        return User::where('google_id', $data['google_id'])->update($data);
    }

    /**
     * @param string $googleId
     * @return User|false
     */
    public function getByGoogleId(string $googleId)
    {
        $user = User::where('google_id', $googleId)->first();

        return $user ?? false;
    }
}