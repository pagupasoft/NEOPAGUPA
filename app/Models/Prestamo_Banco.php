<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Prestamo_Banco extends Model
{
    use HasFactory;
    protected $table ='prestamo_banco';
    protected $primaryKey = 'prestamo_id';
    public $timestamps=true;
    protected $fillable = [
        'prestamo_inicio',        
        'prestamo_fin',
        'prestamo_monto',
        'prestamo_interes',
        'prestamo_plazo',
        'prestamo_total_interes',
        'prestamo_pago_total',
        'prestamo_estado',
        'cuenta_debe',
        'cuenta_haber',
        'sucursal_id',
        'banco_id',
        'empresa_id',
    ];
    protected $guarded =[
    ]; 
    public function scopePrestamos($query){
        return $query->where('prestamo_banco.empresa_id','=',Auth::user()->empresa_id)->where('prestamo_estado','=','1');
    }
    public function scopePrestamo($query, $id){
        return $query->where('prestamo_banco.empresa_id','=',Auth::user()->empresa_id)->where('prestamo_estado','=','1')->where('prestamo_id','=',$id);
    } 
    public function detalles(){
        return $this->hasMany(Detalle_Prestamo::class, 'prestamo_id', 'prestamo_id');
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
    
    public function banco(){
        return $this->belongsTo(Banco::class, 'banco_id', 'banco_id');
    } 
    public function sucursal(){
        return $this->belongsTo(sucursal::class, 'sucursal_id', 'sucursal_id');
    }  
    public function cuentadebe(){
        return $this->belongsTo(Cuenta::class, 'cuenta_debe', 'cuenta_id');
    }
    public function cuentahaber(){
        return $this->belongsTo(Cuenta::class, 'cuenta_haber', 'cuenta_id');
    }
}
