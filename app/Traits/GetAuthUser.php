<?php


namespace App\Traits;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;

trait GetAuthUser
{
    public function getAuthUser()
    {
        return JWTAuth::parseToken()->toUser();
    }
}
