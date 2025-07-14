<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTeam;
use App\Models\Team;
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
   $defaultTeam = Team::create([
            'name' => $googleUser->name,
            // other default fields if required
        ]);

        // Add user to this new team as Owner
        UserTeam::create([
            'team_id' => $defaultTeam->id,
            'user_id' => $user->id,
            'status' => 'Owner',
        ]);
            // Log in the user
            Auth::login($user);
session(['user_id' => Auth::id()]);
            return redirect('/Home/show')->with('success', 'Successfully logged in with Google!');
        } catch (Exception $e) {
            return redirect('/')->with('error', 'Google login failed!');
        }
    }
}
