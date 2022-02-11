<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Prescripcion extends Model
{
    use HasFactory;
    protected $table ='prescripcion';
    protected $primaryKey = 'prescripcion_id';
    public $timestamps=true;
    protected $fillable = [
        'prescripcion_recomendacion',        
        'prescripcion_observacion',
        'prescripcion_estado',
        'expediente_id',        
    ];
    protected $guarded =[
    ];    
    public function scopePrescripciones($query){
        return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                    )->join('orden_atencion','orden_atencion.orden_id','=','expediente.orden_id'
                    )->join('sucursal','sucursal.sucursal_id','=','orden_atencion.sucursal_id'
                    )->where('sucursal.empresa_id','=',Auth::user()->empresa_id
                    )->where('prescripcion.prescripcion_estado','=','1');                  
    }
    public function scopePrescripcion($query, $id){
        return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                    )->join('orden_atencion','orden_atencion.orden_id','=','expediente.orden_id'
                    )->join('sucursal','sucursal.sucursal_id','=','orden_atencion.sucursal_id'
                    )->where('sucursal.empresa_id','=',Auth::user()->empresa_id
                    )->where('prescripcion.prescripcion_id','=',$id);
    }
    public function presMedicamento()
    {
        return $this->hasMany(Prescripcion_Medicamento::class, 'prescripcion_id', 'prescripcion_id');
    }   
}
