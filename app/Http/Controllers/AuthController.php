<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login_registro');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        // Verificar las credenciales manualmente
        $user = Usuario::where('username', $credentials['username'])->first();
    
        // Comprobar si el usuario existe y si la contraseña coincide
        if ($user && $user->password === $credentials['password']) {
            Auth::login($user); // Iniciar sesión
            return redirect()->intended(route('adm.index')); // Redirigir a Adm_index
        }
    
        // Si las credenciales no son correctas
        return back()->withErrors(['username' => 'Usuario o password incorrectos']);
    }
    

    public function register(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'username' => 'required|string|unique:usuario',
            'password' => 'required|string|min:6',
            // Agrega otras validaciones según sea necesario
        ]);

        // Crear un nuevo usuario sin hashear la contraseña
        $usuario = new Usuario();
        $usuario->nombre = $request->nombre; // Agrega el campo 'nombre'
        $usuario->apellido_1 = $request->apellido_1; // Agrega el campo 'apellido_1'
        $usuario->apellido_2 = $request->apellido_2; // Agrega el campo 'apellido_2'
        $usuario->telefono = $request->telefono; // Agrega el campo 'telefono'
        $usuario->fecha_ingreso = now(); // o lo que necesites
        $usuario->correo = $request->correo; // Agrega el campo 'correo'
        $usuario->id_tipo = $request->id_tipo; // Agrega el campo 'id_tipo'
        $usuario->username = $request->username; // Asegúrate de que sea único
        $usuario->password = $request->password; // Almacena la contraseña directamente
        $usuario->foto = $request->foto; // Agrega el campo 'foto' si es necesario
        $usuario->save();

        Auth::guard('usuario')->login($usuario);
        return redirect()->route('admin.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('usuario')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
