<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    
    protected $hidden = ['created_at','updated_at'];

    protected $fillable = ['nombre','precio','stock'];

    public $timestamps = false;

    public static function reglasCreate()
    {
        return [
            'nombre' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ];
    }

    public static function reglasUpdate()
    {
        return [
            'nombre' => 'sometimes|string|max:100',
            'precio' => 'sometimes|numeric|min:0.01',
            'stock' => 'sometimes|integer|min:0',
        ];
    }
}
