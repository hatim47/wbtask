<?php

namespace App\Http\Controllers;

use App\Logic\UserLogic;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(protected UserLogic $userLogic)
    {
    }

    public function showLogin()
    {
        return view("login");
    }

    public function showRegister()
    {
        return view("register");
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required",
            "password" => "required"
        ]);

        $rememberMe = $request->has("remember_me");
        $isValid = Auth::attempt($credentials, $rememberMe);


        if(!$isValid){
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors("Wrong email or password, please try again");
        }

        $isActive = Auth::user()->is_active;

        if (!$isActive) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('login')
                ->withErrors("Account is inactive");
        }

        return redirect()->route('home');
    }

    public function doRegister(Request $request)
    {
        $request->validate([
            "name" => "bail|required|string|max:35",
            "email" => "bail|required|unique:users,email|max:35",
            "password" => "bail|required|string|confirmed"
        ]);

        $this->userLogic->insert(
            $request->input('name'),
            $request->input('email'),
            $request->input('password'),
        );

        return redirect()->route("login")
            ->with("notif", ["Succsess\nRegistration success, please login using your account!"]);
    }

    public function forgetview()
    {
        return view("forgot");

    }

    public function forgot(Request $request)
    {
        $request->validate([
            "email" => "|required|max:35" 
        ]);
        $forgot = User::where('email', $request->email)->first();

    if (!$forgot) {
        return redirect()->back()
            ->withErrors(['email' => 'No account found with that email.'])
            ->withInput(); // optional: keep old input
    }

    // Optional: store the email in session or generate token etc.
    return redirect()->route('reseted')
        ->with('notif', ['Success', 'Email found, please reset your password!']);
    }
    public function reseted()
    {
        return view("reset");

    }
    public function passGen()
    {
        return view("forgot");

    }
    
    public function passet()
    {
        return view("passet");

    }
}
