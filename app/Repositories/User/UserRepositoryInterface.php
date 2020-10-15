<?php

namespace App\Repositories\User;

interface UserRepositoryInterface
{
    public function handleUploadImage($boolean = false, $image, $user = null);
}
