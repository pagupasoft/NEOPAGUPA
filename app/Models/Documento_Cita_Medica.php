<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento_Cita_Medica extends Model
{
    use HasFactory;
    protected $table='documento_cita_medica';
    protected $primaryKey = 'doccita_id';
    public $timestamps=true;
    protected $fillable = [
        'doccita_nombre',           
        'doccita_url',  
        'doccita_estado',  
        'orden_id',        
    ];
    protected $guarded =[
    ];

}
