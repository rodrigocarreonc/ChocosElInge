<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Support\Facades\DB;


class VentaController extends Controller
{
    public function all(){
        $ventas = Venta::with('detalles.producto', 'detalles.sabor')->get();
        return response()->json($ventas);
    }

    public function get($id){
        $venta = Venta::with('detalles.producto', 'detalles.sabor')->find($id);

        if(!$venta){
            return response()->json([
                'message' => 'Venta no encontrada o inexistente :(',
            ], 404);
        }

        return response()->json($venta);
    }

    public function create(Request $request){
        $mensajes = [
            'productos.required' => 'Debes agregar al menos un producto.',
            'productos.array' => 'El campo productos debe ser un arreglo.',
            'productos.*.producto_id.required' => 'El ID del producto es obligatorio.',
            'productos.*.cantidad.required' => 'La cantidad es obligatoria.',
            'productos.*.cantidad.integer' => 'La cantidad debe ser un número entero.',
            'productos.*.cantidad.min' => 'La cantidad debe ser mayor a 0.',
        ];

        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.producto_id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ], $mensajes);

        try{
            DB::beginTransaction();

            $venta = Venta::create([
                'total' => 0,
            ]);
            $total = 0;

            foreach($request->productos as $producto){
                $productoDB = Producto::findOrFail($producto['producto_id']);

                $subtotal = $productoDB->precio * $producto['cantidad'];

                if($productoDB->stock < $producto['cantidad']){
                    DB::rollBack();
                    return response()->json([
                        'message' => 'No hay suficiente stock para el producto '.$productoDB->nombre,
                    ], 422);
                }

                $productoDB->stock -= $producto['cantidad'];
                $productoDB->save();

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto['producto_id'],
                    'precio_unitario' => $productoDB->precio,
                    'cantidad' => $producto['cantidad'],
                    'subtotal' => $subtotal,
                    'sabor_id' => isset($producto['sabor_id']) ? $producto['sabor_id'] : null,
                ]);

                $total += $subtotal;
            }

            $venta->update([
                'total' => $total,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Venta registrada exitosamente!',
                'venta' => $venta->load('detalles.producto', 'detalles.sabor'),   
            ], 201);

        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'message' => 'Error al registrar la venta.',
                'error' => $e->getMessage(),
            ], 422);
        }
    }
}
