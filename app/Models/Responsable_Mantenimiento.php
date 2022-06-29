<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsable_Mantenimiento extends Model
{
    use HasFactory;
    protected $table="responsable_mantenimiento";
    protected $primaryKey="responsable_id";
    public $timestamp=true;
    protected $fillable=[
        'responsable_estado',
        'orden_id',
        'empleado_id'
    ];

    protected $guarded=[
    ];

    public function empleado(){
        return $this->hasOne(Empleado::class, 'empleado_id', 'empleado_id');
    }

    public function orden(){
        return $this->belongsTo(Orden_Mantenimiento::class, 'orden_id', 'orden_id');
    }
}
