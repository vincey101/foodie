<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $finduser = User::where('google_id', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($finduser) {
                if (!$finduser->google_id) {
                    $finduser->update(['google_id' => $user->id]);
                }
                Auth::login($finduser);
                session()->regenerate();
                session()->flash('success', 'Login successful!');
                return redirect('/');
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'password' => bcrypt('123456dummy')
                ]);

                Auth::login($newUser);
                session()->regenerate();
                session()->flash('success', 'Sign up successful!');
                return redirect('/');
            }
        } catch (Exception $e) {
            return redirect('/')->with('error', 'These credentials do not match our records.');
        }
    }
} 