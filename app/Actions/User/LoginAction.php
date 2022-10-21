<?php

namespace App\Actions\User;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Exception;


Class LoginAction {

    private $token_string;

    public function __construct()
    {
        $this->token_string = Str::upper(\config('app.name'));
    }

    public function attempt(Request $request){

        $credentials = [
            'email'     => Str::lower($request->email),
            'password'  => $request->password
        ];
    
        if (Auth::attempt($credentials)) {
    
            $result = Auth::user()->createToken($this->token_string);
            $token = $result->token;
            if ($request->remember)
                $token->expires_at = Carbon::now()->addWeeks(1);
    
            $token->save();

            return $result->accessToken;
        }
    
        throw new Exception('Invalid credentials.', Response::HTTP_UNPROCESSABLE_ENTITY);

    }

}