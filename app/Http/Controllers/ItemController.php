<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Función para leer items de la base de datos
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $item = Item::find($request->id);
            return $item ? response()->json($item) : response()->json(['message' => 'Item not found'], 404);
        }
        $items = Item::all();
        return response()->json($items);
    }

    // Función para crear un nuevo item en la base de datos
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'required|integer',
            'created' => 'required|date',
        ]);

        $item = Item::create($data);
        return response()->json(['message' => 'Item created', 'data' => $item], 201);
    }

    // Función para actualizar un item existente en la base de datos
    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'category_id' => 'sometimes|required|integer',
            'created' => 'sometimes|required|date',
        ]);

        $item->update($data);
        return response()->json(['message' => 'Item updated', 'data' => $item]);
    }

    // Función para eliminar un item de la base de datos
    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        $item->delete();
        return response()->json(['message' => 'Item deleted']);
    }
}
