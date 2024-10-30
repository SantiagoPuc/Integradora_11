<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UsuariosApiController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::all();
        return response()->json($usuarios);
    }

    public function getUser($id)
    {
        $user = Usuario::findOrFail($id);
        return response()->json($user);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:40',
            'apellido_1' => 'required|string|max:40',
            'apellido_2' => 'required|string|max:40',
            'telefono' => 'required|string|size:10|regex:/^999\d{7}$/',
            'fecha_ingreso' => 'required|date',
            'correo' => 'required|string|email|max:320|unique:usuario,correo',
            'id_tipo' => 'required|integer',
            'username' => 'required|string|max:40|unique:usuario,username',
            'password' => 'required|string|min:8',
            'foto' => 'nullable|image|max:2048',
        ]);

        $fotoPath = $request->file('foto') ? 
            $request->file('foto')->store('fotos', 'public') : 
            '0.jpg';

        Usuario::create([
            'nombre' => $request->nombre,
            'apellido_1' => $request->apellido_1,
            'apellido_2' => $request->apellido_2,
            'telefono' => $request->telefono,
            'fecha_ingreso' => $request->fecha_ingreso,
            'correo' => $request->correo,
            'id_tipo' => $request->id_tipo,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'foto' => $fotoPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Usuario creado exitosamente.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:40',
            'apellido_1' => 'required|string|max:40',
            'apellido_2' => 'required|string|max:40',
            'telefono' => 'required|string|size:10|regex:/^999\d{7}$/',
            'correo' => 'required|string|email|max:320|unique:usuario,correo,' . $id,
            'id_tipo' => 'required|integer',
            'username' => 'required|string|max:40|unique:usuario,username,' . $id,
            'password' => 'nullable|string|min:8',
            'foto' => 'nullable|image|max:2048',
        ]);

        $user = Usuario::findOrFail($id);

        if ($request->hasFile('foto')) {
            if ($user->foto != '0.jpg') {
                Storage::disk('public')->delete($user->foto);
            }
            $fotoPath = $request->file('foto')->store('fotos', 'public');
        } else {
            $fotoPath = $user->foto;
        }

        $user->update([
            'nombre' => $request->nombre,
            'apellido_1' => $request->apellido_1,
            'apellido_2' => $request->apellido_2,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'id_tipo' => $request->id_tipo,
            'username' => $request->username,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
            'foto' => $fotoPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Usuario actualizado exitosamente.']);
    }

    public function delete($id)
    {
        $user = Usuario::findOrFail($id);
        if ($user->foto != '0.jpg') {
            Storage::disk('public')->delete($user->foto);
        }
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function searchUser(Request $request)
    {
        $query = $request->get('query');
        $usuarios = Usuario::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('apellido_1', 'LIKE', "%{$query}%")
            ->orWhere('apellido_2', 'LIKE', "%{$query}%")
            ->orWhere('username', 'LIKE', "%{$query}%")
            ->get();
        return response()->json($usuarios);
    }
}
