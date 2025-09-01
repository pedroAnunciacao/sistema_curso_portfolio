<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
        $request = Request::create(request()->getSchemeAndHttpHost().'/oauth/token', 'POST', [
            'grant_type' => 'password',
            'client_id' => '0198fe0f-a577-737a-b8fc-ac210886f7d4',
            'client_secret' => 'YNgIVgqobQwb5yem58ekZNSwQtbELhtyfRaTp0Yc',
            'username' => request('username'),
            'password' => request('password'),
        ]);

        $response = app()->handle($request);
        $content = json_decode($response->getContent());

        if (!$response->isOk()) {
            return response()->json(['error' => $content->error], $response->getStatusCode());
        }

        return response()->json([
            'tokenType' => $content->token_type,
            'expiresIn' => $content->expires_in,
            'accessToken' => $content->access_token,
            'refreshToken' => $content->refresh_token,
        ], $response->getStatusCode());
    }

    /**
     * Return authenticated user info
     */
    public function me(Request $request)
    {
        $user = $request->user()->loadMissing('pessoa');
        return new UserResource($user);
    }
}
