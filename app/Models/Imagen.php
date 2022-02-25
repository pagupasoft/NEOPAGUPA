<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Imagen extends Model
{
    use HasFactory;
    protected $table='imagen';
    protected $primaryKey = 'imagen_id';
    public $timestamps = true;
    protected $fillable = [
        'imagen_nombre',
        'imagen_estado',
        'tipo_id',
    ];
    protected $guarded =[];
    
    public function scopeImagenes($query){
        return $query->join('tipo_imagen','tipo_imagen.tipo_id','=','imagen.tipo_id'
                    )->where('tipo_imagen.empresa_id', '=', Auth::user()->empresa_id
                    )->where('imagen.imagen_estado','=','1')->orderBy('imagen_nombre','asc');

    }
    public function scopeImagen($query, $id){
        return $query->join('tipo_imagen','tipo_imagen.tipo_id','=','imagen.tipo_id'
                    )->where('tipo_imagen.empresa_id', '=', Auth::user()->empresa_id
                    )->where('imagen.imagen_id','=',$id);
    }  
}
