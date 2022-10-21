<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\User\UserResource;
use App\Actions\User\LoginAction;
use Carbon\Carbon;
use Exception;





class UserApiController extends Controller
{

    public function login(LoginRequest $request, LoginAction $loginAction)
    {
        try {

            $response = $this->respondWithToken( $loginAction->attempt($request) );
            return response()->json($response, 200);

        } catch (Exception $e) {

            return response()->json([
                'status'    => false,
                'message'   => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);

        }
    }

    public function logout( Request $request )
    {
        try {

          $request->user()->tokens->each(function($token, $key) {
            $token->delete();
          });

          return response()->json([
              'status'    => true,
              'message'   => 'Session closed succesfully.'
          ], Response::HTTP_OK);
          
        } catch (Exception $e) {

           return response()->json([
                'status'    => false,
                'message'   => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);

        }
    }

    public function access()
    {
        
        return response()->json([
            'status'    => true,
            'message'   => 'Access successfully'
        ], 200);

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return [
            'status'        => true,
            'token'         => $token,
            'token_type'    => 'Bearer',
            'user'          => UserResource::make(auth()->user()),
            'message'       => 'Access success',
        ];
    }
}
