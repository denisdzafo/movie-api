<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmationEmail;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests\UserRegisterRequest;
use App\Http\Repository\UserRepository;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\JWT;
use Tymon\JWTAuth\Facades\JWTAuth as JWTAuth;


class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(UserRegisterRequest $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        $user = $this->userRepository->registerUser($name, $email, $password);
        if($user){
            $confirmationLink = url("/confirm-email/".$user->id);
            try{
                Mail::to($user->email)->send(new ConfirmationEmail($confirmationLink, $user));
            }
            catch (Exception $e){

            }
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'error'], 502);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = JWTAuth::attempt($credentials)) {
            return response()->json(['status' => 'success', 'token' => $token], 200)->header('Authorization', $token);
        }

        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out Successfully.'
        ], 200);
    }


    public function refresh()
    {
        if ($token =  auth()->refresh(true, true)) {
            return response()
                ->json(['status' => 'successs', 'token' => $token], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    public function confirmEmail($userId)
    {
        $this->userRepository->confirmUserEmail($userId);

        return view('confirmation.success');
    }
}
