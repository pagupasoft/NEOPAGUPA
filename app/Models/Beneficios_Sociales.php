<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Beneficios_Sociales extends Model
{
    use HasFactory;
    protected $table ='beneficios_sociales';
    protected $primaryKey = 'beneficios_id';
    public $timestamps = true;
    protected $fillable = [        
        'beneficios_fecha',
        'beneficios_fecha_emision',
        'beneficios_tipo', 
        'beneficios_valor',
        'beneficios_periodo',
        'beneficios_descripcion',
        'beneficios_estado',
        'diario_id',
        'empleado_id',
        'tipo_id'
    ];
    protected $guarded =[
    ];
    public function scopebeneficios($query){
        return $query->join('diario','diario.diario_id','=','beneficios_sociales.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('beneficios_estado','=','1')->orderBy('beneficios_fecha','asc');
    }
    public function scopeEmpleados($query){
        return $query->join('empleado','empleado.empleado_id','=','beneficios_sociales.empleado_id')->join('diario','diario.diario_id','=','beneficios_sociales.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('beneficios_estado','=','1')->orderBy('empleado_nombre','asc');
    }
    public function scopeExtraerbeneficios($query,$fecha,$tipo,$sucursal){
        return $query->join('empleado','empleado.empleado_id','=','beneficios_sociales.empleado_id')
        ->join('empleado_cargo','empleado_cargo.empleado_cargo_id','=','empleado.cargo_id')
        ->join('tipo_empleado', 'tipo_empleado.tipo_id','=','empleado.tipo_id')
        ->where('tipo_empleado.sucursal_id','=',$sucursal)
        ->where('empleado_cargo.empresa_id','=',Auth::user()->empresa_id)
        ->where('beneficios_estado','=','1')
        ->where('beneficios_fecha', '=', $fecha)
        ->where('tipo_id', '=', $tipo)
        ->orderBy('empleado.empleado_nombre','asc');
    }
    public function scopeValidacion($query, $id){
        return $query->join('diario','diario.diario_id','=','beneficios_sociales.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('diario.diario_id','=',$id);
    }
    public function diario()
    {
        return $this->belongsTo(Diario::class, 'diario_id', 'diario_id');
    }
   
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'empleado_id');
    }
}
