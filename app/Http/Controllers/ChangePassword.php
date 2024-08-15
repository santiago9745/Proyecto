<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\facades\DB;

class ChangePassword extends Controller
{

    protected $user;

    public function __construct()
    {
        Auth::logout();

        $id = intval(request()->id);
        $this->user = User::find($id);
    }
    
    public function show()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'email' => ['required'],
            'password' => ['required', 'min:5'],
            'confirm-password' => ['same:password']
        ]);
        $email=$attributes['email'];
        $existingUser = User::where('email', $attributes['email'])->first();
        if ($existingUser) {
            $sql=DB::insert("UPDATE users SET estado=1, password=? WHERE email='$email'",[
                bcrypt($attributes['password'])
            ]);
            
            return view('auth.login');
        } else {
            return back()->with('error', 'Your email does not match the email who requested the password change');
        }
    }
}
