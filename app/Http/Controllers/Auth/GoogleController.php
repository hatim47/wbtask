<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserTeam;
use App\Models\Team;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Models\TeamInvitation;
use App\Models\BoardUser;
use Illuminate\Http\Request;
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
public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        // Find or create user
        $user = User::updateOrCreate(
            ['google_id' => $googleUser->id],
            [
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(Str::random(12)),
            ]
        );

        // Check for team invitation
        $teamInvitation = TeamInvitation::where('email', $googleUser->email)->where('status','pending')->first();

        if ($teamInvitation) {
            // ✅ Update invitation status
            $teamInvitation->status = 'accepted';
            $teamInvitation->save();

            // Add to invited team
            UserTeam::create([
                'user_id' => $user->id,
                'team_id' => $teamInvitation->team_id,
                'status' => $teamInvitation->team_role ?? 'Member',
            ]);

            // If invited to a board as well
            if (!empty($teamInvitation->board_id)) {
                BoardUser::create([
                    'user_id'  => $user->id,
                    'board_id' => $teamInvitation->board_id,
                    'status'   => $teamInvitation->board_role ?? 'Member',
                ]);
            }
        } else {
            // No invitation? Create a default team if none exist
            $hasTeams = UserTeam::where('user_id', $user->id)->exists();
            if (!$hasTeams) {
                $defaultTeam = Team::create([
                    'name' => $googleUser->name,
                ]);

                UserTeam::create([
                    'team_id' => $defaultTeam->id,
                    'user_id' => $user->id,
                    'status' => 'Owner',
                ]);
            }
        }

        // ✅ Login once and redirect
        Auth::login($user);
        session(['user_id' => Auth::id()]);
        return redirect('/Home/show')->with('success', 'Successfully logged in with Google!');
    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Google login failed!');
    }
}
    // Handle Google callback
//     public function handleGoogleCallback()
//     {
//         // Get user from Google
//        try {
//             $googleUser = Socialite::driver('google')->user();
//             // dd($googleUser);
//             // Find or create user
//             $user = User::updateOrCreate(
//                 ['google_id' => $googleUser->id],
//                 [
//                     'name' => $googleUser->name,
//                     'email' => $googleUser->email,
//                     'password' => bcrypt(Str::random(12)), // Generate a random password
//                 ]
//             );
//   $team = TeamInvitation::where('email', $googleUser->email)->where('status','pending')->exists();
//         if ($team) {
//             $teamInvitation = TeamInvitation::where('email', $googleUser->email)->first();        
//             UserTeam::create([
//                 'user_id' => $user->id,
//                 'team_id' => $teamInvitation->team_id,
//                 'status' => $teamInvitation->team_role ?? 'Member'
//             ]);
//         if (!empty($teamInvitation->board_id)) {
//             BoardUser::create([
//                 'user_id'  => $user->id,
//                 'board_id' => $teamInvitation->board_id,
//                'status' => $teamInvitation->board_role ?? 'Member',
//             ]); 
//             Auth::login($user);
// session(['user_id' => Auth::id()]);
//             return redirect('/Home/show')->with('success', 'Successfully logged in with Google!'); 
//         } 
//          $teamInvitation->status = 'accepted';
//     $teamInvitation->save();
//           Auth::login($user);
// session(['user_id' => Auth::id()]);
//             return redirect('/Home/show')->with('success', 'Successfully logged in with Google!');           
//         }  
//         else{
//              $hasTeams = UserTeam::where("user_id", $user->id)->exists();
//     if (!$hasTeams) {        
//         $defaultTeam = Team::create([
//         'name' => $googleUser->name,
//         ]);
//         UserTeam::create([
//             'team_id' => $defaultTeam->id,
//             'user_id' => $user->id,
//             'status' => 'Owner',
//         ]);
//                 Auth::login($user);
// session(['user_id' => Auth::id()]);
//             return redirect('/Home/show')->with('success', 'Successfully logged in with Google!');
//     }
//         } 
//             Auth::login($user);
// session(['user_id' => Auth::id()]);
//             return redirect('/Home/show')->with('success', 'Successfully logged in with Google!');
//         } catch (Exception $e) {            
//             return redirect('/')->with('error', 'Google login failed!');
//         } 

//     }
}
