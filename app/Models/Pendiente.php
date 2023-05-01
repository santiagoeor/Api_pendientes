<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendiente extends Model
{
    use HasFactory;

    protected $primaryKey = 'pend';

    public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id', 'catg');
    }
}
