<?php
namespace App\Repository;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data);
    public function update(array $data);
    public function getByGoogleId(string $googleId);
}