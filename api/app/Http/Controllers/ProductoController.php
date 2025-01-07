<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class ProductoController extends Controller
{
    public function all(){
        $productos = Producto::all();
        return response()->json($productos);
    }

    public function create(Request $request){
        try {
            $mensajes = [
                'nombre.required' => 'El campo nombre es obligatorio.',
                'nombre.string' => 'El nombre debe ser una cadena de texto.',
                'nombre.max' => 'El nombre no puede tener más de 100 caracteres.',
                'precio.required' => 'El campo precio es obligatorio.',
                'precio.numeric' => 'El precio debe ser un número.',
                'precio.min' => 'El precio debe ser mayor a 0.',
                'stock.required' => 'El campo stock es obligatorio.',
                'stock.integer' => 'El stock debe ser un número entero.',
                'stock.min' => 'El stock no puede ser negativo.',
            ];

            $validateData = $request->validate(Producto::reglasCreate(), $mensajes);

            $producto = Producto::create($validateData);

            return response()->json([
                'message' => 'Producto creado exitosamente!',
                'product' => $producto,
            ], 201);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Errores de validación.',
                'errors' => $e->errors(),
            ], 422);
        }
    }


    
    public function update($id, Request $request){
        $producto = Producto::find($id);

        if(!$producto){
            return response()->json([
                'message' => "Producto no encontrado o inexistente :("
            ],404);
        }
        
        $validateData = $request->validate(Producto::reglasUpdate());

        $producto->update($validateData);

        return response()->json([
            'message' => 'Producto editado exitosamente!',
            'product' => $producto
        ],200);
    }

    public function delete($id){
        $producto = Producto::find($id);

        if(!$producto){
            return response()->json([
                'message' => "Producto no encontrado o inexistente :("
            ],404);
        }

        $producto->delete();

        return response()->json([
            'message' => 'Producto eliminado Correctamente!',
            "producto" => $producto
        ],200);
    }
}
