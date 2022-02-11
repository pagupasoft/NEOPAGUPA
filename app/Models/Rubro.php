<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Rubro extends Model
{
    use HasFactory;
    protected $table='rubro';
    protected $primaryKey = 'rubro_id';
    public $timestamps = true;
    protected $fillable = [        
        'rubro_nombre',
        'rubro_descripcion',
        'rubro_tipo',                  
        'empresa_id',
        'rubro_estado',         
    ];
    protected $guarded =[
    ];   
    public function scopeRubros($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('rubro_estado','=','1')->orderBy('rubro_nombre','asc');
    }
    public function scopeRubrosRH($query){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('rubro_estado','=','1')->orderBy('rubro_id','asc');
    }
    public function scopeRubro($query, $id){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('rubro_id','=',$id);
    }
    public function scopeRubrotipo($query, $id){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('rubro_estado','=','1')->where('rubro_tipo','=',$id);
    }
    public function scopeexiste($query, $nomb){
        return $query->where('empresa_id','=',Auth::user()->empresa_id)->where('rubro_nombre','=',$nomb);
    }
    public function empresa(){
        return $this->belongsTo(Empresa::class, 'empresa_id', 'empresa_id');
    }
}
