<?php

namespace App\Http\Controllers;

use App\Models\Arqueo_Caja;
use App\Models\Bodega;
use App\Models\Centro_Consumo;
use App\Models\Concepto_Retencion;
use App\Models\Empresa;
use App\Models\Firma_Electronica;
use App\Models\Proveedor;
use App\Models\Punto_Emision;
use App\Models\Rango_Documento;
use App\Models\Retencion_Compra;
use App\Models\Sustento_Tributario;
use App\Models\Tarifa_Iva;
use App\Models\Tipo_Comprobante;
use App\Models\Transaccion_Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class cargarXMLController extends Controller
{
    public function nuevo($punto)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            return view('admin.compras.xml.index',['punto'=>$punto,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function cargar(Request $request)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $datos = null;
            $count = 1;
            if($request->file('file_sri')->isValid()){
                $empresa = Empresa::empresa()->first();
                $name = $empresa->empresa_ruc. '-SRI.' .$request->file('file_sri')->getClientOriginalExtension();
                $path = $request->file('file_sri')->move(public_path().'\temp', $name); 
                $contenido = file_get_contents ($path);
                $contenido = utf8_encode($contenido);
                $registros = explode ( "\n", $contenido);
                for ($i = 2; $i < sizeof($registros); $i ++) {
                    $data[$i] = explode("\t", $registros[$i]);
                    if(count($data[$i])>1){
                        $transaccion = Transaccion_Compra::TransaccionByAutorizacion($data[$i][9])->first();
                        if(isset($transaccion->transaccion_id) == false){
                            if($data[$i][0] == 'Factura' or $data[$i][0] == 'Notas de Crédito' or $data[$i][0] == 'Notas de Débito'){
                                $datos[$count]['proveedor'] = $data[$i][3];
                                $datos[$count]['fecha'] = $data[$i][4];
                                $datos[$count]['numero'] = $data[$i][1];
                                $datos[$count]['clave'] = $data[$i][9];
                                $datos[$count]['doc'] = $data[$i][0];                      
                                $count ++;
                            }
                        }   
                    }                    
                }       
               // return $datos;     
            }
            return view('admin.compras.xml.index',['datos'=>$datos,'punto'=>$request->get('puntoID'),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    
    public function procesar($clave,$punto)
    {
        try{
            $firmaElectronica = Firma_Electronica::firma()->first();
            $pubKey =Crypt::decryptString($firmaElectronica->firma_pubKey);
            $data=openssl_x509_parse($pubKey,true);

            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();        
            $rangoDocumento=Rango_Documento::PuntoRango($punto,'Comprobante de Retención')->first();
            $cajaAbierta=Arqueo_Caja::arqueoCajaxuser(Auth::user()->user_id)->first();
            $secuencial=1;
            if($rangoDocumento){
                $secuencial=$rangoDocumento->rango_inicio;
                $secuencialAux=Retencion_Compra::secuencial($rangoDocumento->rango_id)->max('retencion_secuencial');
                if($secuencialAux){$secuencial=$secuencialAux+1;}
                $electrocnico = new facturacionElectronicaController();
                $consultaDoc = $electrocnico->consultarDOC($clave);
                if ($consultaDoc['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['estado'] == 'AUTORIZADO') {
                    $xmlEnvio = simplexml_load_string($consultaDoc['RespuestaAutorizacionComprobante']['autorizaciones']['autorizacion']['comprobante']);
                    $poveedorXML = Proveedor::ProveedoresByRuc($xmlEnvio->infoTributaria->ruc)->first();
                    return view('admin.compras.transaccionCompra.nuevo',['caduca'=>$data['validTo_time_t'],'poveedorXML'=>$poveedorXML,'xml'=>$xmlEnvio,'cajaAbierta'=>$cajaAbierta,'rangoDocumento'=>$rangoDocumento,'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9),'conceptosFuente'=>Concepto_Retencion::ConceptosFuente()->get(),'conceptosIva'=>Concepto_Retencion::ConceptosIva()->get(),'centros'=>Centro_Consumo::CentroConsumos()->get(),'bodegas'=>Bodega::bodegasSucursal($punto)->get(),'sustentos'=>Sustento_Tributario::Sustentos()->get(),'comprobantes'=>Tipo_Comprobante::tipos()->get(),'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(),'proveedores'=>Proveedor::proveedores()->get(),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
                }
                return view('admin.compras.transaccionCompra.nuevo',['caduca'=>$data['validTo_time_t'],'cajaAbierta'=>$cajaAbierta,'rangoDocumento'=>$rangoDocumento,'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9),'conceptosFuente'=>Concepto_Retencion::ConceptosFuente()->get(),'conceptosIva'=>Concepto_Retencion::ConceptosIva()->get(),'centros'=>Centro_Consumo::CentroConsumos()->get(),'bodegas'=>Bodega::bodegasSucursal($punto)->get(),'sustentos'=>Sustento_Tributario::Sustentos()->get(),'comprobantes'=>Tipo_Comprobante::tipos()->get(),'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(),'proveedores'=>Proveedor::proveedores()->get(),'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
                
            }else{
                return redirect('inicio')->with('error','No tiene configurado, un punto de emisión o un rango de documentos para emitir retenciones, configueros y vuelva a intentar');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
