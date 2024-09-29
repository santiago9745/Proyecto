<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'rol' => 'required',
            'nombre' => 'required|max:255|min:2',
            'primerApellido' => 'required|max:255|min:2',
            'segundoApellido' => 'required|max:255|min:2',
            'telefono' => 'required|max:8',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:5|max:255',
            'terms' => 'required'
        ]);
        
        $attributes['nombre'] = strtoupper($attributes['nombre']);
        $attributes['primerApellido'] = strtoupper($attributes['primerApellido']);
        $attributes['segundoApellido'] = strtoupper($attributes['segundoApellido']);

        $user = User::create($attributes);
        auth()->login($user);

        if(auth()->user()->rol == 'admin'){
            return redirect('/dashboard');
        }
        else{
            return redirect()->to('/');
        }
        
    }
}
