<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Cuenta extends Model
{
    use HasFactory;
    protected $table='cuenta';
    protected $primaryKey = 'cuenta_id';
    public $timestamps=true;
    protected $fillable = [
        'cuenta_numero',
        'cuenta_nombre',       
        'cuenta_secuencial',        
        'cuenta_nivel',
        'cuenta_estado',
        'cuenta_padre_id',
        'empresa_id',            
    ];
    protected $guarded =[
    ];
    
    public function scopeNivel($query, $cuentaPadre){
        if ($cuentaPadre == 0){
            return $query->where('empresa_id','=',Auth::user()->empresa_id)->whereNull('cuenta_padre_id');
        }else{
            return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_padre_id','=',$cuentaPadre);
        }
    }
    public function scopeNivelPadre($query, $cuentaPadre){
            return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_numero','=',$cuentaPadre);
    }
    public function scopebuscarCuenta($query, $cuenta){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_numero','=',$cuenta);
}

    public function scopeCuentasNivel($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')->orderBy('cuenta_nivel','desc');
    }
    public function scopeCuentas($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')->orderBy('cuenta_numero','asc');
    }
    public function scopeCuentasMovimiento($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')
        ->groupBy('cuenta_id','cuenta_numero','cuenta_nombre')
        ->havingRaw(DB::raw('(select count(*) from cuenta as hijas where cuenta.cuenta_id=hijas.cuenta_padre_id ) = 0'))
        ->orderBy('cuenta_numero','asc');
    }
    public function scopeCuentasResultado($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')->where('cuenta_numero','like','4%')->orwhere('cuenta_numero','like','5%')->orwhere('cuenta_numero','like','6%');
    }
    public function scopeCuentasFinanciero($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')->where('cuenta_numero','like','1%')->orwhere('cuenta_numero','like','2%')->orwhere('cuenta_numero','like','3%');
    }
    public function scopeCuentasDesc($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')->orderBy('cuenta_numero','desc');
    }
    public function scopeCuenta($query, $id){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_id','=',$id);
    }
    public function scopeCuentaByNumero($query, $numero){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_numero','=',$numero);
    }
    public function scopeCuentasRango($query,$cuentaInicio,$cuentaFin){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('cuenta_estado','=','1')->where('cuenta_numero','>=',$cuentaInicio)->where('cuenta_numero','<=',$cuentaFin)->orderBy('cuenta_numero','asc');
    }
    public function cuentaPadre()
    {
        return $this->belongsTo(Cuenta::class, 'cuenta_padre_id', 'cuenta_id');
    }
    public function cuentasHija()
    {
        return $this->hasMany(Cuenta::class, 'cuenta_padre_id', 'cuenta_id');
    }
}
