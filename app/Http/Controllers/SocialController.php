<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Socialite; // No need for full namespace here

class SocialController extends Controller
{
    public function redirect()
    {
        //dd(Socialite::driver('google'));
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('google_id', $googleUser->id)
                        ->orWhere('email', $googleUser->email)
                        ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->user['given_name'],
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => null, // Password is not needed for social login
                ]);
            } else {
                // Update google_id if it is null
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
            }

            $token = $user->createToken('authToken')->accessToken;

            return response()->json(['token' => $token, 'user' => $user]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to authenticate user'], 401);
        }
    }
}
