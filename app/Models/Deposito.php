<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
    protected $guarded =[
    ];
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
