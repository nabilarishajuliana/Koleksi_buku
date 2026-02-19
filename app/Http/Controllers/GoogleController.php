<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Cek apakah email sudah ada
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update id_google jika belum ada
            $user->update([
                'id_google' => $googleUser->getId(),
            ]);
        } else {
            // Buat user baru
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'id_google' => $googleUser->getId(),
                'password' => bcrypt('google-login'),
            ]);
        }

        // Login user
        Auth::login($user);

        session(['otp_user_id' => $user->id]);
        Auth::logout();

        $otp = rand(100000, 999999);
        $user->update(['otp' => $otp]);

        Mail::raw("Kode OTP kamu adalah: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode OTP Login');
        });

        return redirect()->route('otp.form');


        // return redirect('/dashboard');
    }
}
