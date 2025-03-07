<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
        ->scopes(['openid', 'profile', 'email']) // Ensure these scopes are requested
        ->redirect();
    }

    // Handle Google callback
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // dd($googleUser);

            // Find or create user
            $user = User::updateOrCreate(
                ['google_id' => $googleUser->id],
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(12)), // Generate a random password
                ]
            );

            // Log in the user
            Auth::login($user);

            return redirect('/team')->with('success', 'Successfully logged in with Google!');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Google login failed!');
        }
    }
}
