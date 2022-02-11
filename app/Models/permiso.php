<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Permiso extends Model
{
    use HasFactory;
    protected $table='permiso';
    protected $primaryKey = 'permiso_id';
    public $timestamps=true;
    protected $fillable = [
        'permiso_nombre',
        'permiso_ruta',
        'permiso_tipo', 
        'permiso_icono',
        'permiso_orden',
        'permiso_estado',        
        'empresa_id', 
        'grupo_id',    
    ];
    protected $guarded =[
    ];
    public function scopePermisos($query){
        return $query->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso.empresa_id','=',Auth::user()->empresa_id)->where('permiso_estado','=','1')->orderBy('permiso_nombre','asc');
    }
    public function scopePermiso($query, $id){
        return $query->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso.empresa_id','=',Auth::user()->empresa_id)->where('permiso_id','=',$id);
    }
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
    public function grupo()
    {
        return $this->belongsTo(GrupoPer::class, 'grupo_id', 'grupo_id');
    }
}
