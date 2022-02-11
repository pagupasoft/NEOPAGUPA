<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Examen extends Model
{
    use HasFactory;
    protected $table='examen';
    protected $primaryKey = 'examen_id';
    public $timestamps = true;
    protected $fillable = [
        'examen_estado',
        'tipo_id',
        'producto_id',
    ];
    protected $guarded =[];
    
    public function scopeExamenes($query){
        return $query->join('tipo_examen','tipo_examen.tipo_id','=','examen.tipo_id'
                    )->join('producto','examen.producto_id','=','producto.producto_id'
                    )->where('tipo_examen.empresa_id', '=', Auth::user()->empresa_id
                    )->where('examen.examen_estado','=','1')->orderBy('producto_nombre','asc');
    }
    public function scopeExamen($query, $id){
        return $query->join('tipo_examen','tipo_examen.tipo_id','=','examen.tipo_id'
                    )->where('tipo_examen.empresa_id', '=', Auth::user()->empresa_id
                    )->where('examen.examen_id','=',$id);
    }
    public function scopeBuscarProductoslaboratorio($query, $id){
        return $query->join('tipo_examen','tipo_examen.tipo_id','=','examen.tipo_id'
                    )->join('detalle_laboratorio','examen.examen_id','=','detalle_laboratorio.examen_id'
                    )->where('tipo_examen.empresa_id', '=', Auth::user()->empresa_id
                    )->where('examen.producto_id','=',$id);
    }
    public function detalleslaboratorio()
    {
        return $this->hasMany(Detalle_Laboratorio::class, 'examen_id', 'examen_id');
    }
    public function detallesexamen()
    {
        return $this->hasMany(Detalle_Examen::class, 'examen_id', 'examen_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'producto_id');
    } 
     
}
