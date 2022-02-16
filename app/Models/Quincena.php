<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Quincena extends Model
{
    use HasFactory;
    protected $table ='quincena';
    protected $primaryKey = 'quincena_id';
    public $timestamps = true;
    protected $fillable = [     
        'quincena_numero',
        'quincena_serie',
        'quincena_secuencial',
        'quincena_fecha',
        'quincena_tipo', 
        'quincena_valor',
        'quincena_saldo',
        'quincena_descripcion',
        'quincena_estado',
        'diario_id',
        'cabecera_rol_id',
        'empleado_id',
        'rango_id'
    ];
    protected $guarded =[
    ];
    public function scopeQuincenas($query){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('quincena_estado','=','1')->orderBy('quincena_fecha','asc');
    }
    public function scopeQuincenaSinPuntoEmision($query){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('quincena.rango_id','=',null)->orderBy('quincena_fecha','asc');
    }
    public function scopeQuincena($query, $id){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('quincena_id','=',$id);
    }
    public function scopebuscarquincena($query,$empleado_id,$fechadesde,$fechahasta){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_fecha', '>=', $fechadesde)
        ->where('quincena_fecha', '<=', $fechahasta)
        ->where('empleado_id', '=', $empleado_id);
    }
    public function scopeBuscarQuincenasFecha($query,$fechadesde,$fechahasta){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_fecha', '>=', $fechadesde)
        ->where('quincena_fecha', '<=', $fechahasta);
    }
    public function scopeQuincenasFechaEmpleado($query,$fechadesde,$fechahasta,$empleado_id){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_fecha', '>=', $fechadesde)
        ->where('quincena_fecha', '<=', $fechahasta)
        ->where('empleado_id', '=', $empleado_id);
    }
    public function scopeQuincenasFechaEstado($query,$fechadesde,$fechahasta,$estado){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_fecha', '>=', $fechadesde)
        ->where('quincena_fecha', '<=', $fechahasta)
        ->where('quincena_estado','=',$estado);
    }
    public function scopeQuincenasEmpleadoEstado($query,$empleado_id,$estado){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('empleado_id', '=', $empleado_id)
        ->where('quincena_estado','=',$estado);
    }
    public function scopeQuincenasSucursal($query, $id){
        return $query->join('rango_documento','rango_documento.rango_id','=','quincena.rango_id')
        ->join('punto_emision','punto_emision.punto_id','=','rango_documento.punto_id')
        ->join('sucursal','sucursal.sucursal_id','=','punto_emision.sucursal_id')
        ->where('sucursal.empresa_id','=',Auth::user()->empresa_id)->where('quincena_estado','=','1')->where('sucursal.sucursal_id','=',$id);
    }
    public function scopeValidacion($query, $id){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('diario.diario_id','=',$id);
    }
    public function scopeEstados($query){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->orderBy('quincena_estado','asc');
    }
    public function scopeQuincenaEmpleado($query, $id){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('quincena_estado','=','1')->where('empleado_id','=',$id);
    }
    public function scopeSecuencial($query, $id){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)->where('quincena.rango_id','=',$id);
    }
    public function scopeQuincenasDiferente($query,$fechadesde,$fechahasta,$empleado_id,$estado){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_fecha', '>=', $fechadesde)
        ->where('quincena_fecha', '<=', $fechahasta)
        ->where('empleado_id', '=', $empleado_id)
        ->where('quincena_estado','=',$estado)
        ->orderBy('quincena_fecha','asc');
    }
    public function scopeQuincenasEmpleado($query,$empleado_id){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('empleado_id', '=', $empleado_id)
        ->orderBy('quincena_fecha','asc');
    }
    public function scopeQuincenasfecha($query,$fechadesde,$fechahasta){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_fecha', '>=', $fechadesde)
        ->where('quincena_fecha', '<=', $fechahasta)
        ->orderBy('quincena_fecha','asc');
    }
    public function scopeQuincenasestado($query,$estado){
        return $query->join('diario','diario.diario_id','=','quincena.diario_id')->where('diario.empresa_id','=',Auth::user()->empresa_id)
        ->where('quincena_estado','=',$estado)
        ->orderBy('quincena_fecha','asc');
    }
    public function diario()
    {
        return $this->belongsTo(Diario::class, 'diario_id', 'diario_id');
    }
    public function rango()
    {
        return $this->belongsTo(Rango_Documento::class, 'rango_id', 'rango_id');
    }
    
    public function rol()
    {
        return $this->belongsTo(Rol_Consolidado::class, 'cabecera_rol_id', 'cabecera_rol_id');
    }
    public function decuento()
    {
        return $this->hasMany(Descuento_Quincena::class, 'quincena_id', 'quincena_id');
    }
    public function rolcm()
    {
        return $this->belongsTo(Cabecera_Rol_CM::class, 'cabecera_rol_id', 'cabecera_rol_id');
    }
    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'empleado_id');
    }
}
