<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria_Producto;
use App\Models\Centro_Consumo;
use App\Models\Detalle_Diario;
use App\Models\Diario;
use App\Models\Empleado;
use App\Models\Punto_Emision;
use App\Models\Rango_Documento;
use App\Models\Rol_Consolidado;
use App\Models\Tipo_Empleado;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class contabilizacionMensualController extends Controller
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
            return view('admin.recursosHumanos.contabilizacionMensual.index',['consumo'=>Centro_Consumo::CentroConsumos()->get(),'categoria'=>Categoria_Producto::Categorias()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){

            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }
    }
    public function buscarrol($fechadesde,$fechahasta){       
        return Rol_Consolidado::buscarrolContabilisado($fechadesde,$fechahasta)->groupBy('empleado.empleado_id')->selectRaw('sum(detalle_rol.detalle_rol_total_dias) as sueldo,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos, empleado.empleado_id,empleado.empleado_nombre')->get(); 
    }
    public function buscartiporol($fechadesde,$fechahasta){       
        return Rol_Consolidado::buscarrolContabilisadotipo($fechadesde,$fechahasta)->groupBy('empleado.tipo_id')->selectRaw('sum(detalle_rol.detalle_rol_total_dias) as sueldo,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos, empleado.tipo_id,tipo_empleado.tipo_descripcion')->get(); 
    }
    public function tipopempleado()
    {
        return Tipo_Empleado::Tipos()->get();
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
    public function extraer(Request $request)
    {   
        if (isset($_POST['guardarID'])){
            return $this->guardarId($request);
        }
        if (isset($_POST['extraerID'])){
          
            return $this->extraerId($request);
 
        }
        
    }
    public function guardarId(Request $request){
       
       
        setlocale(LC_ALL, 'es_ES');
        $general = new generalController();
        $idempleado = $request->get('empleadoid');
        $idtipo = $request->get('idtipo');
        $tipo = $request->get('tipo');

        $vsueldo = $request->get('vsueldo');
        $vextsalud = $request->get('vextsalud');
        $vpatronal = $request->get('vpatronal');
        $vextras = $request->get('vextras');
        $vleysal = $request->get('vleysal');
        $vvacaciones = $request->get('vvacaciones');
        $vtransporte = $request->get('vtransporte');
        $vppqq = $request->get('vppqq');
       
        $votrabonifi = $request->get('votrabonifi');
        $vhipoteca = $request->get('vhipoteca');
       
        $votrosingresos = $request->get('votrosingresos');
        $vcomisariato = $request->get('vcomisariato');
        
        $vasumido = $request->get('vasumido');

        $vcuartoacu = $request->get('vcuartoacu');
        $vterceroacu = $request->get('vterceroacu');
        $vfondoacumula = $request->get('vfondoacumula');

        $vfondo_reserva = $request->get('vfondo_reserva');
        $vcuarto = $request->get('vcuarto');
        $vtercero = $request->get('vtercero');

        $vingresos = $request->get('vingresos');
        $vpersonal = $request->get('vpersonal');
        $viecesecap = $request->get('viecesecap');
        $vanticipo = $request->get('vanticipo');
        $vliquido_pagar = $request->get('vliquido_pagar');
        $vimpu_renta = $request->get('vimpu_renta');
        $vmultas = $request->get('vmultas');
        $votrosegre = $request->get('votrosegre');
        $vegresos = $request->get('vegresos');

       
        $diariocontabilizado = new Diario();
        $diariocontabilizado->diario_codigo = $general->generarCodigoDiario($request->get('fecha_hasta'), 'CCMR');
        $diariocontabilizado->diario_fecha = $request->get('fecha_hasta');
        $diariocontabilizado->diario_referencia = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLES';
        $diariocontabilizado->diario_tipo_documento = 'CONTABILIZACION MENSUAL';
        $diariocontabilizado->diario_tipo = 'CCMR';
        $diariocontabilizado->diario_secuencial = substr($diariocontabilizado->diario_codigo, 8);
        $diariocontabilizado->diario_mes = DateTime::createFromFormat('Y-m-d', $request->get('fecha_hasta'))->format('m');
        $diariocontabilizado->diario_ano = DateTime::createFromFormat('Y-m-d', $request->get('fecha_hasta'))->format('Y');
        $monthName = strftime('%B', $request->get('fecha_hasta')->getTimestamp());
        $temp1 = new DateTime($request->get('fecha_hasta'));
        $anio = $temp1->format('Y');
        $diariocontabilizado->diario_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLES DE EMPLEADOS DEL MES DE '.$monthName.' '.$anio;
        $diariocontabilizado->diario_numero_documento = 0;
        $diariocontabilizado->diario_beneficiario ="COMPROBANTE DE CONTABILIZACION MENSUAL DE EMPLEADOS";
        $diariocontabilizado->diario_cierre = '0';
        $diariocontabilizado->diario_estado = '1';
        
        $diariocontabilizado->empresa_id = Auth::user()->empresa_id;
        $diariocontabilizado->sucursal_id =  $tipo->sucursal_id;
        $diariocontabilizado->save();
        $general->registrarAuditoria('Registro de diario Contabilizado de rol de Empleado', '0', '');
        $matriz=null;
        $activador=true;
        $count=1;
        for ($i = 0; $i < count($tipo); ++$i) {
            if (floatval($vsueldo[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'sueldos')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($vsueldo[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0){
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($vsueldo[$i]);
                            $activador=false;
                        }
                    }
                    if($activador==true){
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($vsueldo[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }    
            }
            if (floatval($votrosingresos[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'otrosIngresos')->first();
                if ($matriz==null) {
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($votrosingresos[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                } else {
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($votrosingresos[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($votrosingresos[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($vtercero[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'decimoTercero')->first();
                if ($matriz==null) {
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($vtercero[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                } else {
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($vtercero[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($vtercero[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($vcuartoacu[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'decimoCuarto')->first();
                if ($matriz==null) {
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($vcuartoacu[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                } else {
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($vcuartoacu[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($vcuartoacu[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($vfondoacumula[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'fondoReserva')->first();
                if ($matriz==null) {
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($vfondoacumula[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                } else {
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($vfondoacumula[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($vfondoacumula[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($vextras[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'horas_suplementarias')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($vextras[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($vextras[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($vextras[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($vtransporte[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'viaticos')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($vtransporte[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($vtransporte[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($vtransporte[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($votrabonifi[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'otrosBonificaciones')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                    $matriz[$count]["debe"]= floatval($votrabonifi[$i]);
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=0;
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_debe && $matriz[$k]["debe"]>0) {
                            $matriz[$k]["debe"]=  $matriz[$k]["debe"]+floatval($votrabonifi[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_debe;
                        $matriz[$count]["debe"]= floatval($votrabonifi[$i]);
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=0;
                        $count++;
                    }
                }
            }
            if (floatval($vextsalud[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'extSalud')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["haber"]= floatval($vextsalud[$i]);
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=0;
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vextsalud[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'HABER';
                        $matriz[$count]["haber"]=floatval($vextsalud[$i]);
                        $count++;
                    }
                }
            }
            if (floatval($vleysal[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'leysalud')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vleysal[$i]);
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vleysal[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($vleysal[$i]);
                        $count++;
                    }
                }
            }
            if (floatval($vppqq[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'prestamosQuirografarios')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vppqq[$i]);
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vppqq[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($vppqq[$i]);
                        $count++;
                    }
                }
            }
            if (floatval($vhipoteca[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'prestamosHipotecarios')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vhipoteca[$i]);
                    $count++;
                }
                else{
                $activador=true;
                for ($k = 1; $k <= count($matriz); ++$k) {
                    if($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0){
                        $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vhipoteca[$i]);
                        $activador=false;
                    }
                }
                if($activador==true){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'DEBE';
                    $matriz[$count]["haber"]=floatval($vhipoteca[$i]);
                    $count++;
                }
            
                }
            }
            if (floatval($vcomisariato[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'comisariato')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vcomisariato[$i]);
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vcomisariato[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($vcomisariato[$i]);
                        $count++;
                    }
                }
                
            }
            
            if (floatval($vmultas[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'multas')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vmultas[$i]);
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vmultas[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($vmultas[$i]);
                        $count++;
                    }
                }
            }
            if (floatval($vasumido[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'iessAsumido')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vasumido[$i]);
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vasumido[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($vasumido[$i]);
                        $count++;
                    }
                }
               
            }
            if (floatval($vanticipo[$i])>0) {
                
                
               
               
            }
            if (floatval($vimpu_renta[$i])>0) {
                
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'impuestoRenta')->first();
                if($matriz==null){
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($vimpu_renta[$i]);
                    $count++;
                }
                else{
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vimpu_renta[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($vimpu_renta[$i]);
                        $count++;
                    }
                }
            }
            if (floatval($votrosegre[$i])>0) {
                $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'otrosEgresos')->first();
                if ($matriz==null) {
                    $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                    $matriz[$count]["debe"]= 0;
                    $matriz[$count]["tipo"]= 'HABER';
                    $matriz[$count]["haber"]=floatval($votrosegre[$i]);
                    $count++;
                } else {
                    $activador=true;
                    for ($k = 1; $k <= count($matriz); ++$k) {
                        if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                            $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($votrosegre[$i]);
                            $activador=false;
                        }
                    }
                    if ($activador==true) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'DEBE';
                        $matriz[$count]["haber"]=floatval($votrosegre[$i]);
                        $count++;
                    }
                }
            }
    
                if (floatval($vpersonal[$i])>0) {
                    $tipo=Tipo_Empleado::TipoEmpleadoBusquedaCuenta($idtipo[$i], 'aportePersonal')->first();
                    if ($matriz==null) {
                        $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                        $matriz[$count]["debe"]= 0;
                        $matriz[$count]["tipo"]= 'HABER';
                        $matriz[$count]["haber"]=floatval($vpersonal[$i]);
                        $count++;
                    } else {
                        $activador=true;
                        for ($k = 1; $k <= count($matriz); ++$k) {
                            if ($matriz[$k]["idcuenta"]==$tipo->cuenta_haber && $matriz[$k]["haber"]>0) {
                                $matriz[$k]["haber"]=  $matriz[$k]["haber"]+floatval($vpersonal[$i]);
                                $activador=false;
                            }
                        }
                        if ($activador==true) {
                            $matriz[$count]["idcuenta"]= $tipo->cuenta_haber;
                            $matriz[$count]["debe"]= 0;
                            $matriz[$count]["tipo"]= 'DEBE';
                            $matriz[$count]["haber"]=floatval($vpersonal[$i]);
                            $count++;
                        }
                    }
                }
            
        

                ///////////////////////////Egresos///////////////////////////////
        


                ///////////////////////////Provisiones///////////////////////////////
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'aportePatronal')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));
   

                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'aportePatronal')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'vacacion')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));
  

                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'vacacion')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'decimoTercero')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));
 

                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($$request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'decimoTercero')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'decimoCuarto')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));
       

                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'decimoCuarto')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'fondoReserva')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));


                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'fondoReserva')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'fondoReservaAcumulada')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));
       

                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'fondoReservaAcumulada')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
                if (floatval($request->get('fecha_hasta'))>0) {
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'sueldos')->first();
                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_haber = 0.00;
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'iece')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_debe;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_debe.' con el valor de: -> '.$request->get('fecha_hasta'));


                    $detalleDiario = new Detalle_Diario();
                    $detalleDiario->detalle_debe = 0.00;
                    $detalleDiario->detalle_haber = floatval($request->get('fecha_hasta'));
                    $detalleDiario->detalle_comentario = 'COMPROBANTE DE CONTABILIZACION MENSUAL DE ROLESCON MES Y AÑO '.date('Y', strtotime($request->get('fecha_hasta'))).'/'.date('F', strtotime($request->get('fecha_hasta')));
                    $detalleDiario->detalle_tipo_documento = 'CONTABILIZACION MENSUAL';
                    $detalleDiario->detalle_numero_documento = $diariocontabilizado->diario_numero_documento;
                    $detalleDiario->detalle_conciliacion = '0';
                    $detalleDiario->detalle_estado = '1';
                    $tipo=Empleado::EmpleadoBusquedaCuenta($idempleado[0], 'iece')->first();
                    $detalleDiario->cuenta_id = $tipo->cuenta_haber;
                    $detalleDiario->empleado_id = $idempleado[0];
                    $diariocontabilizado->detalles()->save($detalleDiario);
                    $general->registrarAuditoria('Registro de Detalle de Diario codigo: -> '.$diariocontabilizado->diario_codigo, '0', 'En la cuenta del Haber -> '.$tipo->cuenta_haber.' con el valor de: -> '.$request->get('fecha_hasta'));
                }
            }
             

    }
    public function extraerId(Request $request){
      
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();   
            $datos=null;
            $datos[1]["tipo"]="";
            $existe=0;
            $rol=Rol_Consolidado::buscarrolContabilisado($request->get('fecha_desde'),$request->get('fecha_hasta'))->groupBy('cabecera_rol_total_anticipos')->groupBy('empleado.empleado_id')->groupBy('cabecera_rol_iesspatronal')->groupBy('cabecera_rol_iesspersonal')->groupBy('cabecera_rol.cabecera_rol_fr_acumula')->selectRaw('sum(detalle_rol.detalle_rol_liquido_pagar) as liquido_pagar,sum(detalle_rol.detalle_rol_aporte_iecesecap) as iecesecap,sum(detalle_rol.detalle_rol_fondo_reserva) as fondo_reserva,sum(detalle_rol.detalle_rol_decimo_cuarto) as cuarto,sum(detalle_rol.detalle_rol_decimo_tercero) as tercero,sum(detalle_rol.detalle_rol_decimo_terceroacum) as terceroacum,sum(detalle_rol.detalle_rol_decimo_cuartoacum) as cuartoacum,sum(detalle_rol.detalle_rol_iess_asumido) as asumido,sum(detalle_rol.detalle_rol_aporte_patronal) as aporte,sum(detalle_rol.detalle_rol_total_anticipo) as anticipo,sum(detalle_rol.detalle_rol_impuesto_renta) as impu_renta,sum(detalle_rol.detalle_rol_total_dias) as sueldos,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_total_comisariato) as comisariato,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos, empleado.empleado_id,empleado.empleado_nombre,cabecera_rol_iesspatronal as patronal,cabecera_rol_iesspersonal as personal,cabecera_rol_total_anticipos as anticipos_total,cabecera_rol.cabecera_rol_fr_acumula as fondoacumula')->get(); 
            $tipo=Rol_Consolidado::buscarrolContabilisadotipo($request->get('fecha_desde'),$request->get('fecha_hasta'))->groupBy('cabecera_rol_total_anticipos')->groupBy('tipo_empleado.tipo_id')->groupBy('cabecera_rol_iesspatronal')->groupBy('cabecera_rol_iesspersonal')->groupBy('cabecera_rol.cabecera_rol_fr_acumula')->selectRaw('sum(detalle_rol.detalle_rol_liquido_pagar) as liquido_pagar,sum(detalle_rol.detalle_rol_aporte_iecesecap) as iecesecap,sum(detalle_rol.detalle_rol_fondo_reserva) as fondo_reserva,sum(detalle_rol.detalle_rol_decimo_cuarto) as cuarto,sum(detalle_rol.detalle_rol_decimo_tercero) as tercero,sum(detalle_rol.detalle_rol_decimo_terceroacum) as terceroacum,sum(detalle_rol.detalle_rol_decimo_cuartoacum) as cuartoacum,sum(detalle_rol.detalle_rol_iess_asumido) as asumido,sum(detalle_rol.detalle_rol_iess_asumido) as asumido,sum(detalle_rol.detalle_rol_aporte_patronal) as aporte,sum(detalle_rol.detalle_rol_total_anticipo) as anticipo,sum(detalle_rol.detalle_rol_impuesto_renta) as impu_renta,sum(detalle_rol.detalle_rol_total_dias) as sueldos,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_total_comisariato) as comisariato,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos,tipo_empleado.tipo_id,tipo_empleado.tipo_descripcion,cabecera_rol_iesspatronal as patronal,cabecera_rol_iesspersonal as personal,cabecera_rol_total_anticipos as anticipos_total,cabecera_rol.cabecera_rol_fr_acumula as fondoacumula')->get(); 
            $tipos=Tipo_Empleado::Tipos()->get();
            $count=1;
            
            foreach($tipo as $roles){
                foreach ($tipos as $tiposroles) {    
                    if($tiposroles->tipo_id==$roles->tipo_id){       
                        for ($i = 1; $i <= count($datos); $i++)  {                           
                            if($datos[$i]["tipo"]==$tiposroles->tipo_descripcion){
                                $datos[$i]["sueldos"]=$datos[$i]["sueldos"]+ $roles->sueldos;
                                $datos[$i]["otrosingresos"]=$datos[$i]["otrosingresos"]+ $roles->otrosingresos;
                                $datos[$i]["transporte"]=$datos[$i]["transporte"]+ $roles->transporte;
                                $datos[$i]["extras"]=$datos[$i]["extras"]+ $roles->extras;
                                $datos[$i]["otrabonifi"]=$datos[$i]["otrabonifi"]+ $roles->otrabonifi;
                                $datos[$i]["ingresos"]=$datos[$i]["ingresos"]+ $roles->ingresos;
                                $datos[$i]["extsalud"]=$datos[$i]["extsalud"]+ $roles->extsalud;
                                $datos[$i]["leysal"]=$datos[$i]["leysal"]+ $roles->leysal;
                                $datos[$i]["vacaciones"]=$datos[$i]["vacaciones"]+ $roles->vacaciones;
                                $datos[$i]["comisariato"]=$datos[$i]["comisariato"]+ $roles->comisariato;
                                $datos[$i]["ppqq"]=$datos[$i]["ppqq"]+ $roles->ppqq;
                                $datos[$i]["hipoteca"]=$datos[$i]["hipoteca"]+ $roles->hipoteca;
                                $datos[$i]["multas"]=$datos[$i]["multas"]+ $roles->multas;
                                $datos[$i]["asumido"]=$datos[$i]["asumido"]+ $roles->asumido;
                                $datos[$i]["personal"]=$datos[$i]["personal"]+ $roles->personal;
                                $datos[$i]["patronal"]=$datos[$i]["patronal"]+ $roles->patronal;
                                $datos[$i]["anticipo"]=$datos[$i]["anticipo"]+ $roles->anticipo;
                                $datos[$i]["impu_renta"]=$datos[$i]["impu_renta"]+ $roles->impu_renta;
                                $datos[$i]["otrosegre"]=$datos[$i]["otrosegre"]+ $roles->otrosegre;
                                $datos[$i]["egresos"]=$datos[$i]["egresos"]+ $roles->egresos;
                                $datos[$i]["terceroACU"]=$datos[$i]["terceroACU"]+ $roles->terceroacum;
                                $datos[$i]["tercero"]=$datos[$i]["tercero"]+ $roles->tercero;
                                $datos[$i]["cuarto"]=$datos[$i]["cuarto"]+ $roles->cuarto;
                                $datos[$i]["cuartoACU"]=$datos[$i]["cuartoACU"]+ $roles->cuartoacum;
                                $datos[$i]["fondo_reservaACU"]=$datos[$i]["fondo_reservaACU"]+ $roles->fondoacumula;
                                $datos[$i]["fondo_reserva"]=$datos[$i]["fondo_reserva"]+ $roles->fondo_reserva;
                                $datos[$i]["iecesecap"]=$datos[$i]["iecesecap"]+ $roles->iecesecap;
                                $datos[$i]["liquido_pagar"]=$datos[$i]["liquido_pagar"]+ $roles->liquido_pagar;
                                $existe=1;
                            }

                        }
                        
                        if($existe==0){
                            $datos[$count]["idtipo"]=$tiposroles->tipo_id;
                            $datos[$count]["tipo"]=$tiposroles->tipo_descripcion;
                            $datos[$count]["sueldos"]=$roles->sueldos;
                            $datos[$count]["otrosingresos"]=$roles->otrosingresos;
                            
                            $datos[$count]["transporte"]=$roles->transporte;
                            $datos[$count]["extras"]=$roles->extras;
                            $datos[$count]["otrabonifi"]= $roles->otrabonifi;
                            $datos[$count]["ingresos"]=$roles->ingresos;
                            $datos[$count]["extsalud"]= $roles->extsalud;
                            $datos[$count]["leysal"]=$roles->leysal;
                            $datos[$count]["vacaciones"]= $roles->vacaciones;
                            $datos[$count]["comisariato"]= $roles->comisariato;
                            $datos[$count]["ppqq"]= $roles->ppqq;
                            $datos[$count]["hipoteca"]=$roles->hipoteca;
                            $datos[$count]["multas"]=$roles->multas;
                            $datos[$count]["asumido"]=$roles->asumido;
                            $datos[$count]["aporte"]=$roles->aporte;
                            $datos[$count]["personal"]=$roles->personal;
                            $datos[$count]["patronal"]=$roles->patronal;
                            $datos[$count]["anticipo"]=$roles->anticipo;
                            $datos[$count]["impu_renta"]=$roles->impu_renta;
                            $datos[$count]["otrosegre"]=$roles->otrosegre;
                            $datos[$count]["egresos"]=$roles->egresos;
                            $datos[$count]["tercero"]=$roles->tercero;
                            $datos[$count]["cuarto"]=$roles->cuarto;
                            $datos[$count]["terceroACU"]=$roles->terceroacum;
                            $datos[$count]["cuartoACU"]=$roles->cuartoacum;
                            $datos[$count]["fondo_reservaACU"]=$roles->fondoacumula;
                            $datos[$count]["fondo_reserva"]=$roles->fondo_reserva;
                            $datos[$count]["iecesecap"]=$roles->iecesecap;
                            $datos[$count]["liquido_pagar"]=$roles->liquido_pagar;
                            $count++;
                        }
                        
                    }
                }    
            }
           
            return view('admin.recursosHumanos.contabilizacionMensual.index',['datos'=>$datos,'rol'=>$rol,'consumo'=>Centro_Consumo::CentroConsumos()->get(),'categoria'=>Categoria_Producto::Categorias()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
       

    }
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
