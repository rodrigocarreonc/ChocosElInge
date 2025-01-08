<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Venta;
use App\Models\Egreso;

class BalanceController extends Controller{
    public function get(){
        $egresos = Egreso::all();
        return response()->json($egresos);
    }

    public function outflow(Request $request){
        try{
            $mensajes = [
                'concepto.required' => 'El concepto es obligatorio.',
                'monto.required' => 'El monto es obligatorio.',
                'monto.numeric' => 'El monto debe ser un número.'
            ];
            $validated = $request->validate(Egreso::reglasCreate(), $mensajes);

            $egreso = Egreso::create($validated);
    
            return response()->json([
                'message' => 'Egreso creado exitosamente!',
                'egreso' => $egreso
            ], 201);

        }catch(\Illuminate\Validation\ValidationException $e){
            return response()->json([
                'message' => 'Error al registrar egreso.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function delete($id){
        $outflow = Egreso::find($id);
        if(!$outflow){
            return response()->json([
                'message' => 'Egreso no encontrado o inexistente :(',
            ], 404);
        }

        $outflow->delete();

        return response()->json([
            'message' => 'Egreso eliminado exitosamente!',
            'egreso' => $outflow
        ], 200);
    }

    public function balance(){
        $ventas = Venta::all();
        $egresos = Egreso::all();
        $totalVentas = 0;
        $totalEgresos = 0;
        $total = 0;

        foreach ($ventas as $venta) {
            $totalVentas += $venta->total;
        }

        foreach ($egresos as $egreso) {
            $totalEgresos += $egreso->monto;
        }

        $total = $totalVentas - $totalEgresos;

        return response()->json([
            'total_ventas' => $totalVentas,
            'total_egresos' => $totalEgresos,
            'total' => $total
        ]);
    }
}
