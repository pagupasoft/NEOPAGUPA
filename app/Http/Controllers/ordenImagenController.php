<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Analisis_Laboratorio;
use App\Models\Arqueo_Caja;
use App\Models\Aseguradora_Procedimiento;
use App\Models\Bodega;
use App\Models\Cliente;
use App\Models\Detalle_Imagen;
use App\Models\Entidad_Procedimiento;
use App\Models\Especialidad;
use App\Models\Factura_Venta;
use App\Models\Forma_Pago;
use App\Models\Orden_Examen;
use App\Models\Orden_Imagen;
use App\Models\Paciente;
use App\Models\Procedimiento_Especialidad;
use App\Models\Punto_Emision;
use App\Models\Rango_Documento;
use App\Models\Sucursal;
use App\Models\Tarifa_Iva;
use App\Models\Tipo_Seguro;
use App\Models\Vendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use DateTime;
use App\Models\Empresa;

class ordenImagenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();         
            $ordenesAtencion=Orden_Examen::OrdenExamenesHOY()->select('orden_examen.orden_id as orden_examen_id', 'orden_atencion.orden_id', 'orden_fecha','orden_codigo','orden_numero', 'paciente_apellidos','paciente_nombres','orden_otros','orden_examen.orden_estado')->get();
            $ordenesImagen=Orden_Imagen::ordenImagenes()->get();

            //return $ordenesImagen;

            foreach($ordenesImagen as $ordenImagen){
                if($ordenImagen->expediente){
                    $expediente=$ordenImagen->expediente;

                    if($expediente){
                        $ordenesAtencion=$expediente->ordenAtencion;

                        if($ordenesAtencion){
                            $paciente = $ordenesAtencion->paciente;
                        }
                    }
                }
            }

            //return $ordenesImagen;

            return view('admin.laboratorio.ordenesImagen.index',['sucursales'=>Sucursal::Sucursales()->get(),'ordenesAtencion'=>$ordenesImagen,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        //}
        //catch(\Exception $ex){      
            //return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        //}
    }

    public function subirImagenes($id)
    {
        //try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            
            
            $orden = Orden_Imagen::findOrFail($id);

            $detalle=$orden->detalleImagen;

            if($detalle){
                foreach($detalle as $det){
                    $d=$det->imagen->producto;
                }
            }
            

            //return $orden;

            return view('admin.laboratorio.ordenesImagen.subirImagen',['orden'=>$orden,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        //}catch(\Exception $ex){
        //    return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        //}
    }

    public function guardarImagenes(Request $request){
        try{
            DB::beginTransaction();

            $orden_imagen=Orden_Imagen::findOrFail($request->orden_id);
            $orden_atencion=$orden_imagen->expediente->ordenAtencion;

            $imagenes=[];
            $empresa = Empresa::empresa()->first();
            $fecha = (new DateTime("$orden_atencion->orden_fecha"))->format('d-m-Y');

            $ruta = 'DocumentosOrdenAtencion/'.$empresa->empresa_ruc.'/'.$fecha.'/'.$orden_atencion->orden_numero.'/Documentos/Imagenes';

            $actualizados=[];

            if ($request->files) {
                if (!is_dir(public_path().'/'.$ruta)) {
                    mkdir(public_path().'/'.$ruta, 0777, true);
                }
                
                foreach($request->files as $key => $file){
                    $detalleId=explode("_",$key)[1];
                    
                    $c=0;
                    foreach($file as $documento){
                        $c++;
                        $name = 'imagen_resultado'.$detalleId.'_'.$c.'.pdf';

                        $path = $documento->move(public_path().'/'.$ruta, $name);
                        $temp = [
                            'ruta'=>$ruta,
                            'nombre'=>$name,
                            'path'=>$path
                        ];

                        $imagenes[] = $temp;

                        $actualizados[]=$detalleId;
                        $detalleImagen=Detalle_Imagen::findOrFail($detalleId);
                        $detalleImagen->detalle_estado=2;
                        $detalleImagen->save();
                    }
                }
                
                $detalleImagen=$orden_imagen->detalleImagen;

                $listo=true;
                foreach($detalleImagen as $detalle){
                    if($detalle->detalle_estado!=2){
                        $listo=false;
                        
                    }
                }
                
                if($listo){
                    $orden_imagen->orden_estado=3;
                    $orden_imagen->save();
                }
                else{
                    return $detalleImagen;
                }
            }

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de orden de Imagen #'.$orden_imagen->orden_id.' con Expediente '.$orden_imagen->expediente,$orden_imagen->orden_id, json_encode($actualizados));

            DB::commit();
            return redirect('ordenImagen')->with('success', 'Datos guardados exitosamente');
        }
        catch(\Exception $e){
            DB::rollBack();
            return redirect('ordenImagen')->with('success', 'Datos guardados exitosamente');
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $ordenesAtencion = Orden_Examen::OrdenesByFechaSuc($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('sucursal_id'))->select('orden_examen.orden_id as orden_examen_id', 'orden_atencion.orden_id', 'orden_fecha','orden_codigo','orden_numero', 'paciente_apellidos','paciente_nombres','orden_otros','orden_examen.orden_estado')->orderBy('orden_numero','asc')->get();
            return view('admin.laboratorio.ordenesExamen.index',['fecI'=>$request->get('fecha_desde'),'fecF'=>$request->get('fecha_hasta'),'sucurslaC'=>$request->get('sucursal_id'),'sucursales'=>Sucursal::Sucursales()->get(),'ordenesAtencion'=>$ordenesAtencion,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $orden = Orden_Examen::OrdenExamen($id)->first();
            $orden->orden_estado='2';
            $orden->save();
            $analisis=new Analisis_Laboratorio();
            $puntoEmision = Punto_Emision::PuntoSucursalUser($orden->sucursal_id,Auth::user()->user_id)->first();
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $rangoDocumento=Rango_Documento::PuntoRango($puntoEmision->punto_id, 'laboratorio')->first();
            $secuencial=1;
            if($rangoDocumento){
                $secuencial=$rangoDocumento->rango_inicio;
                $secuencialAux=Analisis_Laboratorio::secuencial($rangoDocumento->rango_id)->max('analisis_secuencial');
                if($secuencialAux){$secuencial=$secuencialAux+1;}
                return view('admin.laboratorio.ordenesExamen.facturarOrden',['ordenAtencion'=>$orden,'clienteO'=>Cliente::ClientesByCedula($orden->paciente->paciente_cedula)->first(),'vendedores'=>Vendedor::Vendedores()->get(),'tarifasIva'=>Tarifa_Iva::TarifaIvas()->get(),'secuencial'=>substr(str_repeat(0, 9).$secuencial, - 9), 'bodegas'=>Bodega::bodegasSucursal($puntoEmision->punto_id)->get(),'formasPago'=>Forma_Pago::formaPagos()->get(), 'rangoDocumento'=>$rangoDocumento,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('inicio')->with('error','No tiene configurado, un punto de emisión o un rango de documentos para emitir facturas de venta, configueros y vuelva a intentar');
            }
            $general = new generalController();
            $url = $general->LaboratorioAnalisis($orden);
            return redirect('ordenesExamen')->with('success','Analisis Preatendido exitosamente')->with('diario',$url);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
