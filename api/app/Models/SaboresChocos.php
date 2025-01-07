<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaboresChocos extends Model
{
    use HasFactory;

    protected $table = 'SaboresChocos';
    protected $fillable = ['sabor'];

    public $timestamps = false;

    public static function reglasCreate()
    {
        return [
            'sabor' => 'required|string|max:100'
        ];
    }

    public static function reglasUpdate()
    {
        return [
            'sabor' => 'sometimes|string|max:100'
        ];
    }
}
