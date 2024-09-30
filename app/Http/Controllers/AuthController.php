<?php
namespace App\Http\Controllers;
use App\Mail\ForgetPassword;
use App\Mail\SendEmail;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'The email address format is not valid.',
            'password.required' => 'Please enter your Password.',
        ]);
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // Check if user status is inactive
            if ($user->status == 'inactive') {
                $otp = rand(100000, 999999);
                $user->otp = $otp;
                $user->save();
                $details = [
                    'title' => 'Mail from Vcana Global',
                    'body' => 'This is a test email sent from Laravel.',
                    'otp'=>$otp,
                ];
                Mail::to('supriyachandel9855@gmail.com')->send(new SendEmail($details));
                return redirect('/otpverify')->with('error', 'Please verify your OTP.');
            }
    
            // Redirect based on role
            if ($user->role == 1) {
                return redirect('/');
            } elseif ($user->role == 2) {
                return redirect('/user/dashboard');
            } elseif ($user->role == 3) {
                return redirect('/subadmin/dashboard');
            }
        }
    
        return redirect()->back()->withErrors('Invalid credentials');
    }
    public function signup(Request $request)
    {
        $otp = rand(100000, 999999);
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone_number' => 'max:15|unique:users',
            'password' => 'required|string|min:8',
        ]  , [
            'name.required' => 'Please enter your email address.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'The email address format is not valid.',
            'password.required' => 'Please enter your Password.',
        ]
        
    
    
    );
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
        try 
        {
            $user->save();
            $details = [
                'title' => 'Mail from Vcana Global',
                'body' => 'Please enter this code to complete your verification.',
                'otp'=>$otp,
            ];
            Mail::to('supriyachandel9855@gmail.com')->send(new SendEmail($details));
        } catch (\Exception $e) {
            return 'Failed to send email. Error: ' . $e->getMessage();
        }
        return redirect()->intended('otpverify');
    }

    public function forget(Request $request)
    {
        return view('emailverify');
    }
    public function forgetpassword(Request $request)
    {
        $otp = rand(100000, 999999);
        $user = User::where('email',$request->email)->first();
        
            if($user)
            {
                $user->reset_otp =$otp;
                $user->save();
                $url = route('resetPassword');
                $details = [
                    'title' => 'Mail from Vcana Global',
                    'body' => 'Please click on given link for reset the password.',
                    'url'=>$url,
                    'otp'=>$otp,

                ];
                Mail::to('supriyachandel9855@gmail.com')->send(new ForgetPassword($details));
                return view('text');
            }else
            {
                return back()->with('error','Enter Registered Email.');
            }
       
    }
    function resetPassword()
    {

        return view('reset_password');
    }
    public function savePassword(Request $request)
    {   
        $request->validate([
            'otp' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::where('reset_otp',$request->otp)->first();
        if($user)
        {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('login');
        }else
        {
            return back()->with('error','Enter Valid otp .
            ');
        }
    }

    public function changePassword()
    {
        return view('change_password');
    }
    public function saveChangePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);
        $user = User::where('email',Auth()->user()->email)->first();
        if($user)
        {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect('/');
        }else
        {
            return back()->with('error','Enter Valid otp .
            ');
        }
    }
    public function otpverify(Request $request)
    {
        $user = User::where('email',auth()->user()->email)->where('otp',$request->otp)->first();
        if($user)
        {
            $user->status = 'active';
            $user->save();
            if ($user->role == 1) {
                return redirect('/admin/dashboard');
            } elseif ($user->role == 2) {
                return redirect('/user/dashboard');
            } elseif ($user->role == 3) {
                return redirect('/subadmin/dashboard');
            }
        }else
        {
            return redirect()->intended('otpverify');
        }
    }
}
