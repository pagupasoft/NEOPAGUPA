<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Deposito extends Model
{
    use HasFactory;
    protected $table='deposito';
    protected $primaryKey = 'deposito_id';
    public $timestamps=true;
    protected $fillable = [
        'deposito_fecha',
        'deposito_tipo',       
        'deposito_numero',        
        'deposito_valor',
        'deposito_descripcion',
        'deposito_estado',
        'empresa_id',
        'cuenta_bancaria_id',
        'deposito_conciliacion',
        'deposito_fecha_conciliacion',         
    ];
    protected $guarded =[
    ];
    public function scopeDeposito($query, $id){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('deposito.deposito_id','=',$id);
    }
    public function scopeDepositosByCuenta($query, $id){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('deposito.cuenta_bancaria_id','=',$id)->orderby('deposito.deposito_id','asc');
    }    
    public function scopeDepositosOtrosByCuenta($query, $id){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('deposito.cuenta_bancaria_id','=',$id)->orderby('deposito.deposito_id', 'asc');
    }
    public function detalleDiario(){
        return $this->hasMany(Detalle_Diario::class, 'deposito_id', 'deposito_id');
    }
    public function cuentaBancaria()
    {
        return $this->belongsTo(Cuenta_Bancaria::class, 'cuenta_bancaria_id', 'cuenta_bancaria_id');
    }
    public function chequeCliente(){
        return $this->hasOne(Cheque_Cliente::class, 'deposito_id', 'deposito_id');
    }
}
