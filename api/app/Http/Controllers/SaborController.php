<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\SaboresChocos;

class SaborController extends Controller
{
    public function get(){
        $sabores = SaboresChocos::all();
        return response()->json($sabores);
    }

    public function create(Request $request){
        try {
            $mensajes = [
                'sabor.required' => 'El campo nombre es obligatorio.',
                'sabor.string' => 'El nombre debe ser una cadena de texto.',
                'sabor.max' => 'El nombre no puede tener más de 100 caracteres.'
            ];

            $validateData = $request->validate(SaboresChocos::reglasCreate(), $mensajes);

            $producto = SaboresChocos::create($validateData);

            return response()->json([
                'message' => 'Sabor creado exitosamente!',
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
        $producto = SaboresChocos::find($id);

        if(!$producto){
            return response()->json([
                'message' => "Sabor no encontrado o inexistente :("
            ],404);
        }
        
        $validateData = $request->validate(SaboresChocos::reglasUpdate());

        $producto->update($validateData);

        return response()->json([
            'message' => 'Sabor editado exitosamente!',
            'product' => $producto
        ],200);
    }

    public function delete($id){
        $producto = SaboresChocos::find($id);

        if(!$producto){
            return response()->json([
                'message' => "Sabor no encontrado o inexistente :("
            ],404);
        }

        $producto->delete();

        return response()->json([
            'message' => 'Sabor eliminado Correctamente!',
            "producto" => $producto
        ],200);
    }
}
