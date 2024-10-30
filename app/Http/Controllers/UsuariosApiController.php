<?php
namespace App\Http\Controllers\Api;
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
        return response()->json(Usuario::all());
    }

    public function getUser($id)
    {
        return response()->json(Usuario::findOrFail($id));
    }

    public function create(Request $request)
    {
        // Validaciones y lógica de creación
    }

    public function update(Request $request, $id)
    {
        // Validaciones y lógica de actualización
    }

    public function delete($id)
    {
        // Lógica para eliminar usuario
    }

    public function searchUser(Request $request)
    {
        // Lógica para buscar usuarios
    }
}

?>