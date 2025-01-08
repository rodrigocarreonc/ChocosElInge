<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = ['fecha', 'total', "created_at", "updated_at"];

    protected $hidden = ['created_at', 'updated_at'];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class);
    }
}
