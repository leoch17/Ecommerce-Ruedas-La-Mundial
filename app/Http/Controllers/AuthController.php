<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login() {
        return view('frontend.account.login');
    }

    public function register() {
        return view('frontend.account.register');
    }

    public function processRegister(Request $request) {

        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:5|confirmed'
        ]);

        if ($validator->passes()) {

            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            session()->flash('success','Usted se ha registrado satisfactoriamente');

            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password],$request->get('remember'))) {

                if (session()->has('url.intended')){
                    return redirect(session()->get('url.intended'));
                }

                return redirect()->route('account.profile');

            } else {
                //session()->flash('error','Correo electrónico/contraseña incorrectos');
                
                return redirect()->route('account.login')
                        ->withInput($request->only('email'))
                        ->with('error','Correo electrónico/contraseña incorrectos');
            }

        } else {
            return redirect()->route('account.login')
            ->withErrors($validator)
            ->withInput($request->only('email'));
        }
    }
    
    public function profile() {
        return view('frontend.account.profile');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success','¡Te has desconectado satisfactoriamente!');
    }
}
