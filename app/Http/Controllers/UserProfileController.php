<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function show()
    {
        return view('pages.user-profile');
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'username' => ['required','max:255', 'min:2'],
            'nombre' => ['max:100'],
            'primerApellido' => ['max:100'],
            'segundoApellido' => ['max:100'],
            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),]
            
        ]);

        auth()->user()->update([
            'username' => $request->get('username'),
            'nombre' => $request->get('nombre'),
            'primerApellido' => $request->get('primerApellido'),
            'segundoApellido' => $request->get('segundoApellido'),
            'email' => $request->get('email')
        ]);
        return back()->with('succes', 'Profile succesfully updated');
    }
}
