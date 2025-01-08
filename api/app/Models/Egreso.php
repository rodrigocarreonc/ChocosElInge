<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Egreso extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = ['concepto', 'monto', 'fecha'];

    public static function reglasCreate(){
        return [
            'concepto' => 'required|string',
            'monto' => 'required|numeric'
        ];
    }
}
