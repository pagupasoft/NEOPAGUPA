<?php

namespace App\Http\Controllers;

use App\Models\Medico;
use App\Models\Medico_Especialidad;
use App\Models\Empleado;
use App\Models\Proveedor;
use App\Models\Signos_Vitales;
use App\Http\Controllers\Controller;
use App\Models\Aseguradora_Procedimiento;
use App\Models\Bodega;
use App\Models\Cliente;
use App\Models\Configuracion_Especialidad;
use App\Models\Detalle_Diagnostico;
use App\Models\Detalle_Examen;
use App\Models\Detalle_Expediente;
use App\Models\Detalle_Imagen;
use App\Models\Detalle_OFactura;
use App\Models\Diagnostico;
use App\Models\Enfermedad;
use App\Models\Especialidad;
use App\Models\Examen;
use App\Models\Imagen;
use App\Models\Medicamento;
use App\Models\Movimiento_Producto;
use App\Models\Orden_Atencion;
use App\Models\Orden_Examen;
use App\Models\Orden_Factura;
use App\Models\Orden_Imagen;
use App\Models\Paciente;
use App\Models\Prescripcion;
use App\Models\Prescripcion_Medicamento;
use App\Models\Procedimiento_Especialidad;
use App\Models\Producto;
use Illuminate\Http\Request;
use App\Models\Punto_Emision;
use App\Models\Sucursal;
use App\Models\Tipo_Examen;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class atencionCitasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medicos = Medico::medicos()->get();
            $id = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $id = $medico->medico_id;
                }
            }
            
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();
            $ordenesAtencion = Orden_Atencion::OrdenesHoy()->get();

            return view('admin.citasMedicas.atencionCitas.index',['medico'=>$medico, 'mespecialidadM'=>$mespecialidadM,'ordenesAtencion'=>$ordenesAtencion, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
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
        try {
            DB::beginTransaction();
            $auditoria = new generalController();
            $atencion=Orden_Atencion::findOrFail($request->get('orden_id'));
            $bodega=Bodega::SucursalBodega($atencion->sucursal_id)->first();
            /**************Diagnostico****************/

            $laboratorio = $request->get('laboratorio');
           
            $DenfermedadId = $request->get('DenfermedadId');
            $DobservacionEnfer = $request->get('DobservacionEnfer');  
            $DcasoN = $request->get('DcboxCasoN');        
            $Ddefinitivo = $request->get('DcboxDefinitivo');   
            $DcasoNEstado = $request->get('DcboxCasoNEstado');        
            $DdefinitivoEstado = $request->get('DcboxDefinitivoEstado');          
            /**************Prescripcion****************/
            $PmedicinaId = $request->get('PmedicinaId');
            $Pcantidad = $request->get('Pcantidad');  
            $Pindicaciones = $request->get('Pindicaciones');  
            $Pproducto = $request->get('Pproducto');  
            /**************Examenes****************/
            
            /**************Imagenes****************/
            $ImagenId = $request->get('ImagenId');
            $Iobservacion = $request->get('Iobservacion');
            /**************Facturacion****************/
            $FprocedimientoAId = $request->get('FprocedimientoAId');
            $FproductoId = $request->get('FproductoId');
            $Fobservacion = $request->get('Fobservacion');
            $Fcosto = $request->get('Fcosto');

            ///Signos Viatles
            $sig_valor = $request->get('svalor');
            $sig_id = $request->get('side');

            ///CEspecialidades
            $c_nombre = $request->get('nombre');
            $c_tipo = $request->get('tipo');
            $c_medida = $request->get('medida');
            $c_id = $request->get('ide');
            $c_valor = $request->get('valor');

            

            for ($i = 1; $i < count($c_nombre); ++$i) {
                $detalle = new Detalle_Expediente();
                $detalle->detallee_nombre=$c_nombre[$i];
                $detalle->detallee_tipo=$c_tipo[$i];
                $detalle->detallee_medida=$c_medida[$i];
                $detalle->detallee_url=' ';
                $detalle->detallee_multiple=' ';
                $detalle->detallee_valor=$c_valor[$i];
                $detalle->detallee_estado='1';
                $detalle->expediente_id=$request->get('expediente_id');
                $detalle->save();
                $auditoria->registrarAuditoria('Ingreso de Detalle de expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, 'Con Nombre '.$c_nombre[$i].' Con Valor'.$c_valor[$i] );
            }
            for ($i = 1; $i < count($sig_valor); ++$i) {
                $signos=Signos_Vitales::findOrFail($sig_id[$i]);
                $signos->signo_valor=$sig_valor[$i];
                $signos->save();
                
            }
            if ($DenfermedadId) {
                if (count($DenfermedadId)>0) {
                    $diagnosticos = new Diagnostico();
                    $diagnosticos->expediente_id = $request->get('expediente_id');
                    $diagnosticos->diagnostico_estado ="1";
                    if ($request->get('diagnostico_observacion')) {
                        $diagnosticos->diagnostico_observacion = $request->get('diagnostico_observacion');
                    } else {
                        $diagnosticos->diagnostico_observacion =' ';
                    }
                    $diagnosticos->save();
                    $auditoria->registrarAuditoria('Ingreso de Diagnostico con expediente -> ' .  $request->get('expediente_id'), $atencion->orden_id, '');
                    for ($i = 1; $i < count($DenfermedadId); ++$i) {
                        $Detalle=new Detalle_Diagnostico();
                        $Detalle->detalled_estado = 1;
                        $Detalle->enfermedad_id = $DenfermedadId[$i];
                        $diagnosticos->detallediagnostico()->save($Detalle);
                        $auditoria->registrarAuditoria('Ingreso de Detalle de Diagnostico con expediente -> ' .  $request->get('expediente_id'), $atencion->orden_id, '');
                    }
                }
            }
            if (count($PmedicinaId)>1) {
                $prescripcion = new Prescripcion();
                if ($request->get('recomendacion_prescripcion')) {
                    $prescripcion->prescripcion_recomendacion = $request->get('recomendacion_prescripcion');
                } 
                if ($request->get('observacion_prescripcion')) {
                    $prescripcion->prescripcion_observacion = $request->get('observacion_prescripcion');
                } 
                $prescripcion->prescripcion_estado = 1;
                $prescripcion->expediente_id = $request->get('expediente_id');
                $prescripcion->save();
                $auditoria->registrarAuditoria('Ingreso de Medicina con expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, '');
                for ($i = 1; $i < count($PmedicinaId); ++$i) {
                    $prescripcionM = new Prescripcion_Medicamento();

                    /******************registro de movimiento de producto******************/
                    $movimientoProducto = new Movimiento_Producto();
                    $movimientoProducto->movimiento_fecha=$request->get('factura_fecha');
                    $movimientoProducto->movimiento_cantidad=$Pcantidad[$i];
                    $movimientoProducto->movimiento_precio=0;
                    $movimientoProducto->movimiento_iva=0;
                    $movimientoProducto->movimiento_total=0;
                    $movimientoProducto->movimiento_stock_actual=0;
                    $movimientoProducto->movimiento_costo_promedio=0;
                    $movimientoProducto->movimiento_documento='FARMACIA';
                    $movimientoProducto->movimiento_motivo='PENDIENTE DE DESAPACHAR';
                    $movimientoProducto->movimiento_tipo='SALIDA';
                    $movimientoProducto->movimiento_descripcion='PENDIENTE DE DESAPACHAR EN FARMACIA POR LA ORDEN DE ATENCION No. '.$atencion->orden_numero;
                    $movimientoProducto->movimiento_estado='0';
                    $movimientoProducto->producto_id=$Pproducto[$i];
                    $movimientoProducto->bodega_id=$bodega->bodega_id;
                    $movimientoProducto->empresa_id=Auth::user()->empresa_id;
                    $movimientoProducto->save();
                    $auditoria->registrarAuditoria('Registro de movimiento de producto por pendiente de despachar en farmacion con la orden de atencion numero -> '.$atencion->orden_numero,$atencion->orden_numero,'Registro de movimiento de producto de farmacia por la orden de atencion -> '.$atencion->orden_numero.' producto id -> '.$Pproducto[$i].' con la cantidad de -> '.$Pcantidad[$i].' con un stock actual de -> '.$movimientoProducto->movimiento_stock_actual);
                    /*********************************************************************/
                    

                    $prescripcionM->prescripcionm_cantidad = $Pcantidad[$i];
                    $prescripcionM->prescripcionm_indicacion = $Pindicaciones[$i];
                    $prescripcionM->prescripcionm_estado = 1;
                    $prescripcionM->medicamento_id = $PmedicinaId[$i];
                    $prescripcionM->movimiento()->associate($movimientoProducto);
                    $prescripcionM->prescripcion()->associate($prescripcion);
                    $prescripcionM->save();

                  
                    $auditoria->registrarAuditoria('Ingreso de Detalle Medicina con expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, 'Con Medicina Id '.$PmedicinaId[$i].' Con Cantidad '.$Pcantidad[$i]);
                }
            }
            if (isset($laboratorio)) {
                $ordenExamen = new Orden_Examen();                
                if($request->get('otros_examenes')){ 
                    $ordenExamen->orden_otros = $request->get('otros_examenes');
                }
                $ordenExamen->orden_estado = 1; 
                $ordenExamen->expediente_id = $request->get('expediente_id'); 
                $ordenExamen->save();  
                $auditoria->registrarAuditoria('Ingreso de Examenes con expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, '');
            
                for ($i = 1; $i < count($laboratorio); ++$i) {
                    $detalleExamen = new Detalle_Examen();
                    $detalleExamen->detalle_estado="1";
                    $detalleExamen->examen_id=$laboratorio[$i];
                    $detalleExamen->orden_id=$ordenExamen->orden_id;
                    $detalleExamen->save();
                    $auditoria->registrarAuditoria('Ingreso de Detalle Examenes con expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, 'Con examen Id '.$laboratorio[$i]);
                }
            }
            if (count($ImagenId)>1) {
                $ordenImagen = new Orden_Imagen();
                $ordenImagen->orden_estado = 1;
                if ($request->get('otros_imagen')) {
                    $ordenImagen->orden_observacion=$request->get('otros_imagen');
                }
                $ordenImagen->expediente_id = $request->get('expediente_id');
                $ordenImagen->save();
                $auditoria->registrarAuditoria('Ingreso de Imagenes con expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, '');
                
               
                for ($i = 1; $i < count($ImagenId); ++$i) {
                    $detalleImagen = new Detalle_Imagen();
                    if ($Iobservacion[$i]) {
                        $detalleImagen->detalle_indicacion = $Iobservacion[$i];
                    }
                    $detalleImagen->detalle_estado = 1;
                    $detalleImagen->imagen_id = $ImagenId[$i];

                    $ordenImagen->detalleImagen()->save($detalleImagen);
                   
                    $auditoria->registrarAuditoria('Ingreso de Detalle Imagenes con expediente -> ' .  $request->get('expediente_id'),$atencion->orden_id, 'Las imagenes asignadas fueron -> ' .$Iobservacion[$i]);
                }
            }
            
            $atencion->orden_estado='4';
            $atencion->save();
            $auditoria->registrarAuditoria('Actualizacion de Examen a estado Atendido Numero'.$atencion->orden_numero.' Con Expediente '.$request->get('expediente_id'),$atencion->orden_id, '');
            /*Inicio de registro de auditoria */
            
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('atencionCitas')->with('success', 'Datos guardados exitosamente');
        } catch (\Exception $ex) {
            DB::rollBack();
            return redirect('atencionCitas')->with('error', 'Ocurrio un error en el procedimiento. Vuelva a intentar.('.$ex->getMessage().')');
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
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $medico = Medico::medico($id)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($id)->get();
            $ordenesAtencion = Orden_Atencion::Ordenes()->get();
            if($medico){
                return view('admin.citasMedicas.atencionCitas.ver',['medico'=>$medico,'ordenesAtencion'=>$ordenesAtencion,'mespecialidadM'=>$mespecialidadM,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }catch(\Exception $ex){
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function atender($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $diagnosticos = Diagnostico::Diagnosticos()->get();
            $medicamentos = Medicamento::Medicamentos()->get();
            $enfermedades = Enfermedad::Enfermedades()->get();
           
            $imagenes = Imagen::Imagenes()->get();
            $sucursales = Sucursal::Sucursales()->get();
            $especialidades = Especialidad::Especialidades()->get();
            $examenes = Examen::Examenes()->get();
            $tipoExamenes = Tipo_Examen::TipoExamenes()->get();
            $productos = Producto::Productos()->get();
            

            $medicos = Medico::medicos()->get();
            $medicoId = 0;

            foreach($medicos as $medico){
                if($medico->user_id == Auth::user()->user_id){
                    $medicoId = $medico->medico_id;
                }
            }
            $medico = Medico::medico($medicoId)->first();
            $mespecialidadM = Medico_Especialidad::mespecialidadM($medicoId)->first();
            $ordenAtencion = Orden_Atencion::Orden($id)->first();
            $cespecialidad=Configuracion_Especialidad::ConfiEspecialidades($ordenAtencion->especialidad_id)->get();
     
            $signoVital=Signos_Vitales::SignoVitalOrdenId($ordenAtencion->orden_id)->get();
             
            if($medico){
                return view('admin.citasMedicas.atencionCitas.atender',['cespecialidad'=>$cespecialidad,'medico'=>$medico,'examenes'=>$examenes,'tipoExamenes'=>$tipoExamenes,'especialidades'=>$especialidades,'productos'=>$productos,'imagenes'=>$imagenes,'sucursales'=>$sucursales,'enfermedades'=>$enfermedades,'medicamentos'=>$medicamentos,'diagnosticos'=>$diagnosticos,'signoVital'=>$signoVital,'ordenAtencion'=>$ordenAtencion,'mespecialidadM'=>$mespecialidadM,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
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
