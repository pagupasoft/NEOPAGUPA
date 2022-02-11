<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Proforma extends Model
{
    use HasFactory;
    protected $table='proforma';
    protected $primaryKey = 'proforma_id';
    public $timestamps=true;
    protected $fillable = [
        'proforma_numero',
        'proforma_serie',
        'proforma_secuencial',
        'proforma_fecha', 
        'proforma_subtotal', 
        'proforma_tarifa0', 
        'proforma_tarifa12', 
        'proforma_descuento', 
        'proforma_iva', 
        'proforma_total', 
        'proforma_comentario',
        'proforma_porcentaje_iva', 
        'proforma_estado', 
        'bodega_id',
        'cliente_id',        
        'rango_documento_id',        
    ];
    protected $guarded =[
    ];

    public function scopeProformas($query){
        return $query->join('cliente','cliente.cliente_id','=','proforma.cliente_id')->join('bodega','bodega.bodega_id','=','proforma.bodega_id')->join('sucursal','sucursal.sucursal_id','=','bodega.sucursal_id')->where('sucursal.empresa_id','=',Auth::user()->empresa_id)->orderBy('proforma_numero','asc');
    }
    public function scopeClientesDistinsc($query){
        return $query->join('cliente','cliente.cliente_id','=','proforma.cliente_id')->join('bodega','bodega.bodega_id','=','proforma.bodega_id')->join('sucursal','sucursal.sucursal_id','=','bodega.sucursal_id')->join('tipo_identificacion','tipo_identificacion.tipo_identificacion_id','=','cliente.tipo_identificacion_id')->where('tipo_identificacion.empresa_id','=',Auth::user()->empresa_id)->orderBy('cliente.cliente_nombre','asc');
    }
    public function scopeSucursalDistinsc($query){
        return $query->join('cliente','cliente.cliente_id','=','proforma.cliente_id')->join('bodega','bodega.bodega_id','=','proforma.bodega_id')->join('sucursal','sucursal.sucursal_id','=','bodega.sucursal_id')->join('tipo_identificacion','tipo_identificacion.tipo_identificacion_id','=','cliente.tipo_identificacion_id')->where('tipo_identificacion.empresa_id','=',Auth::user()->empresa_id)->orderBy('sucursal_nombre','asc');
    }
    public function scopeProforma($query, $id){
        return $query->join('cliente','cliente.cliente_id','=','proforma.cliente_id')->join('bodega','bodega.bodega_id','=','proforma.bodega_id')->join('sucursal','sucursal.sucursal_id','=','bodega.sucursal_id')->where('sucursal.empresa_id','=',Auth::user()->empresa_id)->where('proforma_id','=',$id);
    }
    public function scopeSecuencial($query, $id){
        return $query->join('bodega','bodega.bodega_id','=','proforma.bodega_id')->join('sucursal','sucursal.sucursal_id','=','bodega.sucursal_id')->where('empresa_id','=',Auth::user()->empresa_id)->where('proforma.rango_id','=',$id);
    }
    public function detalles(){
        return $this->hasMany(Detalle_Proforma::class, 'proforma_id', 'proforma_id');
    }
    public function bodega()
    {
        return $this->belongsTo(Bodega::class, 'bodega_id', 'bodega_id');
    }
    public function cliente()
    {
        return $this->belongsTo(cliente::class, 'cliente_id', 'cliente_id');
    }
    public function rangoDocumento(){
        return $this->belongsTo(Rango_Documento::class, 'rango_id', 'rango_id');
    }
    public function empresa(){
        return $this->hasOneThrough(Empresa::class, Forma_Pago::class,'forma_pago_id','empresa_id','forma_pago_id','empresa_id');
    }

}
