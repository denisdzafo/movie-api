<?php

namespace App\Http\Repository;

use App\Models\User;
use Carbon\Carbon;

class UserRepository
{
    public function registerUser($name, $email, $password)
    {
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();
        return $user;
    }


    public function confirmUserEmail($userId)
    {
        $user =  User::find($userId);
        $user->email_verified_at = Carbon::now();
        $user->save();
    }

}
