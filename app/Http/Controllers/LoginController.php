<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(){
        return view('login.index');
    }

    public function iniciarSesion(Request $request){
        $data = $request->validate([
            'username'=>'required',
            'password'=>'required'
        ],  [
            'username.required'=>'Ingrese un nombre de usuario válido.',
            'password.required'=>'Ingrese una contraseña válida.',
        ]);

        if (Auth::attempt($data)){
            return redirect(route('principal'));
        }

        $name = $request->get("username");
        $query = User::where('username', '=', $name)->get();

        if ($query->count() == 0){
            return back()->withErrors(['username' => "El nombre de usuario ingresado es inválido."])->withInput(request(['username']));
        }

        $hashp = $query[0]->password;
        $password = $request->get("password");

        if (!password_verify($password, $hashp)){
            return back()->withErrors(['password' => "La contraseña ingresada es inválida."])->withInput(request(['username']));
        }
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->flush();
        return redirect(route('login'));
    }
}
