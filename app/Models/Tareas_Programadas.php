<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tareas_Programadas extends Model
{
    use HasFactory;

    protected $table='tarea_programada';
    protected $primaryKey = 'tarea_id';
    public $timestamps = true;
    protected $fillable = [        
        'tarea_nombre',                    
        'tarea_tipo_tiempo',
        'tarea_fecha_hora',      
        'tarea_estado'   
    ];
    protected $guarded =[
    ];
}
