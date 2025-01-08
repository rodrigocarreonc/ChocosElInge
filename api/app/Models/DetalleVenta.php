<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaborChoco;

class DetalleVenta extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'DetalleVentas';

    protected $fillable = ['venta_id', 'producto_id', 'cantidad', 'precio_unitario', 'subtotal', 'sabor_id'];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function venta()
    {
        return $this->belongsTo(Venta::class);
    }

    public function sabor()
    {
        return $this->belongsTo(SaboresChocos::class, 'sabor_id');
    }
}
