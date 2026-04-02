<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Response;

class FirebaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            return response()->json(['error' => 'Unauthorized: missing token'], 401);
        }

        $idToken = $matches[1];

        try {
            $firebase = (new Factory)
                ->withServiceAccount(storage_path('app/firebase/firebase_credentials.json'))
                ->createAuth();

            $verifiedToken = $firebase->verifyIdToken($idToken);
            $uid = $verifiedToken->claims()->get('sub');

            $request->attributes->set('firebase_uid', $uid);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Unauthorized: invalid token'], 401);
        }

        return $next($request);
    }
}
