<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class OTPController extends Controller
{
    public function showForm()
    {
        return view('auth.otp');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);

        $user = User::find(session('otp_user_id'));

        if ($user && $user->otp == $request->otp) {

            // Hapus OTP setelah berhasil
            $user->update(['otp' => null]);

            Auth::login($user);

            session()->forget('otp_user_id');

            return redirect('/dashboard');
        }

        return back()->with('error', 'OTP salah!');
    }
}
