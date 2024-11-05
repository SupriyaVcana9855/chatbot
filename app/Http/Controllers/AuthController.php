<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Http\Requests\ForgetPasswordRequest;
use App\Http\Requests\SavePasswordRequest;
use App\Http\Requests\SaveChangePasswordRequest;
use App\Http\Requests\OtpVerifyRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use App\Mail\ForgetPassword;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        // dd($request->all());
        if (!User::where('email', $request->email)->exists()) {
            return redirect()->back()->with('error', 'This email does not exist in our records.');
        }        
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status == 'inactive') {
                $otp = rand(100000, 999999);
                $user->otp = $otp;
                $user->save();
                $details = [
                    'title' => 'Mail from Vcana Global',
                    'body' => 'This is a test email sent from Laravel.',
                    'otp' => $otp,
                ];
                Mail::to($request->email)->send(new SendEmail($details));
                return redirect('/otpverify')->with('error', 'Please verify your OTP.');
            }

            if ($user->role == 1) {
                return redirect('/');
            } elseif ($user->role == 2) {
                return redirect('/user/dashboard');
            } elseif ($user->role == 3) {
                return redirect('/subadmin/dashboard');
            }
        }

        return redirect()->back()->with('error', 'Invalid credentials');
    }

    public function signup(SignupRequest $request)
    {

        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->with('error', 'Account already exists with this email.');
        }
        
        $otp = rand(100000, 999999);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => 2,
            'status' => "inactive",
            'otp' => $otp,
        ]);
        Auth::login($user);
        try {
            $details = [
                'title' => 'Mail from Vcana Global',
                'body' => 'Please enter this code to complete your verification.',
                'otp' => $otp,
            ];
            Mail::to($user->email)->send(new SendEmail($details));
        } catch (\Exception $e) {
            return 'Failed to send email. Error: ' . $e->getMessage();
        }
        return redirect()->intended('otpverify');
    }

    public function forgetpassword(ForgetPasswordRequest $request)
    {
        $otp = rand(100000, 999999);
        $user = User::where('email', $request->email)->first();
        
        if ($user) {
            $user->reset_otp = $otp;
            $user->save();
            $url = route('resetPassword');
            $details = [
                'title' => 'Mail from Vcana Global',
                'body' => 'Please click on the given link to reset the password.',
                'url' => $url,
                'otp' => $otp,
            ];
            Mail::to($request->email)->send(new ForgetPassword($details));
            return view('text');
        } else {
            return back()->with('error', 'Enter Registered Email.');
        }
    }

    public function resetPassword()
    {
        return view('reset_password');
    }

    public function savePassword(SavePasswordRequest $request)
    {
        $user = User::where('reset_otp', $request->otp)->first();
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login');
        } else {
            return back()->with('error', 'Enter Valid OTP.');
        }
    }

    public function changePassword()
    {
        return view('change_password');
    }

    public function saveChangePassword(SaveChangePasswordRequest $request)
    {
        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect('/');
    }

    public function otpverify(OtpVerifyRequest $request)
    {
        $user = User::where('email', auth()->user()->email)->where('otp', $request->otp)->first();
        if ($user) {
            $user->status = 'active';
            $user->save();
            if ($user->role == 1) {
                return redirect('/admin/dashboard');
            } elseif ($user->role == 2) {
                return redirect('/user/dashboard');
            } elseif ($user->role == 3) {
                return redirect('/subadmin/dashboard');
            }
        } else {
            return redirect()->intended('otpverify');
        }
    }
}
