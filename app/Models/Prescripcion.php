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

    public function scopePrescripcionesPaciente($query){
        return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                    )->join('orden_atencion','orden_atencion.orden_id','=','expediente.orden_id'
                    )->join('sucursal','sucursal.sucursal_id','=','orden_atencion.sucursal_id'
                    )->join('paciente', 'paciente.paciente_id', '=', 'orden_atencion.paciente_id'
                    )->where('sucursal.empresa_id','=',Auth::user()->empresa_id
    );//->where('prescripcion.prescripcion_estado','=','1');
    }

    public function scopePrescripcionesBusqueda($query, $request){
        if(intval($request->pacienteID)==0)
            return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                )->join('orden_atencion','orden_atencion.orden_id','=','expediente.orden_id'
                )->join('sucursal','sucursal.sucursal_id','=','orden_atencion.sucursal_id'
                )->join('paciente', 'paciente.paciente_id', '=', 'orden_atencion.paciente_id'
                )->where('sucursal.empresa_id','=',Auth::user()->empresa_id
                )->where('prescripcion.prescripcion_estado','=',"$request->estado"
                )->whereBetween('orden_atencion.orden_fecha',[$request->fecha_desde, $request->fecha_hasta]);
        else
            return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                )->join('orden_atencion','orden_atencion.orden_id','=','expediente.orden_id'
                )->join('sucursal','sucursal.sucursal_id','=','orden_atencion.sucursal_id'
                )->join('paciente', 'paciente.paciente_id', '=', 'orden_atencion.paciente_id'
                )->where('sucursal.empresa_id','=',Auth::user()->empresa_id
                )->where('prescripcion.prescripcion_estado','=',"$request->estado"
                )->Where('orden_atencion.paciente_id','=',$request->pacienteID
                )->whereBetween('orden_atencion.orden_fecha',[$request->fecha_desde, $request->fecha_hasta]);
    }

    public function scopePrescripcionDetalle($query, $id){
        return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                    )->join('prescripcion_medicamento','prescripcion_medicamento.prescripcion_id','=','prescripcion.prescripcion_id'
                    )->join('medicamento','medicamento.medicamento_id','=','prescripcion_medicamento.medicamento_id'
                    )->join('producto','producto.producto_id','=','medicamento.producto_id'
                    )->join('tipo_medicamento','tipo_medicamento.tipo_id','=','medicamento.tipo_id'
                    )->where('tipo_medicamento.empresa_id','=',Auth::user()->empresa_id
                    )->where('expediente.orden_id','=',$id);
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
    
    public function scopeFindByExpediente($query, $expedienteId){
        return $query->join('expediente','expediente.expediente_id','=','prescripcion.expediente_id'
                    )->join('orden_atencion','orden_atencion.orden_id','=','expediente.orden_id'
                    )->join('sucursal','sucursal.sucursal_id','=','orden_atencion.sucursal_id'
                    )->where('sucursal.empresa_id','=',Auth::user()->empresa_id
                    )->where('expediente.expediente_id','=',"$expedienteId");                  
    }
}
