<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    /**
     * Login via Passport Password Grant
     */
    public function login()
    {
        $request = Request::create(request()->getSchemeAndHttpHost() . '/oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_CLIENT_ID'),
            'client_secret' => env('PASSPORT_CLIENT_SECRET'),
            'username' => request('username'),
            'password' => request('password'),
        ]);

        $response = app()->handle($request);
        $content = json_decode($response->getContent(), true); // decodifica como array

        if (isset($content['error'])) {
            return response()->json([
                'error' => $content['error_description'] ?? $content['error']
            ], $response->getStatusCode());
        }

        return response()->json([
            'tokenType' => $content['token_type'],
            'expiresIn' => $content['expires_in'],
            'accessToken' => $content['access_token'],
            'refreshToken' => $content['refresh_token'],
        ], $response->getStatusCode());
    }

    /**
     * Return authenticated user info
     */
    public function me(Request $request)
    {
        $user = $request->user();

        if($user->person->client){
           $user->loadMissing('person.client'); 
        }

        if($user->person->teacher){
           $user->loadMissing('person.teacher'); 
        }

        if($user->person->student){
           $user->loadMissing('person.student'); 
        }

        return new UserResource($user);
    }
}
