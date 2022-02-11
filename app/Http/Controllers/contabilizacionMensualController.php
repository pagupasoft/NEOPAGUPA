<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria_Producto;
use App\Models\Centro_Consumo;
use App\Models\Rol_Consolidado;
use App\Models\Tipo_Empleado;
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
        return Rol_Consolidado::buscarrolContabilisado($fechadesde,$fechahasta)->groupBy('empleado.empleado_id')->selectRaw('sum(detalle_rol.detalle_rol_total_dias) as sueldo,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_bonificacion_valor) as bonificaciones,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos, empleado.empleado_id,empleado.empleado_nombre')->get(); 
    }
    public function buscartiporol($fechadesde,$fechahasta){       
        return Rol_Consolidado::buscarrolContabilisadotipo($fechadesde,$fechahasta)->groupBy('empleado.tipo_id')->selectRaw('sum(detalle_rol.detalle_rol_total_dias) as sueldo,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_bonificacion_valor) as bonificaciones,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos, empleado.tipo_id,tipo_empleado.tipo_descripcion')->get(); 
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
        

    }
    public function extraerId(Request $request){
        try{  
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();   
            $datos=null;
            $datos[1]["tipo"]="";
            $existe=0;
            $rol=Rol_Consolidado::buscarrolContabilisado($request->get('fecha_desde'),$request->get('fecha_hasta'))->groupBy('cabecera_rol_total_anticipos')->groupBy('empleado.empleado_id')->groupBy('cabecera_rol_iesspatronal')->groupBy('cabecera_rol_iesspersonal')->groupBy('cabecera_rol.cabecera_rol_fr_acumula')->selectRaw('sum(detalle_rol.detalle_rol_liquido_pagar) as liquido_pagar,sum(detalle_rol.detalle_rol_aporte_iecesecap) as iecesecap,sum(detalle_rol.detalle_rol_fondo_reserva) as fondo_reserva,sum(detalle_rol.detalle_rol_decimo_cuarto) as cuarto,sum(detalle_rol.detalle_rol_decimo_tercero) as tercero,sum(detalle_rol.detalle_rol_decimo_terceroacum) as terceroacum,sum(detalle_rol.detalle_rol_decimo_cuartoacum) as cuartoacum,sum(detalle_rol.detalle_rol_iess_asumido) as asumido,sum(detalle_rol.detalle_rol_aporte_patronal) as aporte,sum(detalle_rol.detalle_rol_total_anticipo) as anticipo,sum(detalle_rol.detalle_rol_impuesto_renta) as impu_renta,sum(detalle_rol.detalle_rol_total_dias) as sueldos,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_total_comisariato) as comisariato,sum(detalle_rol.detalle_rol_bonificacion_valor) as bonificaciones,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos, empleado.empleado_id,empleado.empleado_nombre,cabecera_rol_iesspatronal as patronal,cabecera_rol_iesspersonal as personal,cabecera_rol_total_anticipos as anticipos_total,cabecera_rol.cabecera_rol_fr_acumula as acumula')->get(); 
            $tipo=Rol_Consolidado::buscarrolContabilisadotipo($request->get('fecha_desde'),$request->get('fecha_hasta'))->groupBy('cabecera_rol_total_anticipos')->groupBy('tipo_empleado.tipo_id')->groupBy('cabecera_rol_iesspatronal')->groupBy('cabecera_rol_iesspersonal')->groupBy('cabecera_rol.cabecera_rol_fr_acumula')->selectRaw('sum(detalle_rol.detalle_rol_liquido_pagar) as liquido_pagar,sum(detalle_rol.detalle_rol_aporte_iecesecap) as iecesecap,sum(detalle_rol.detalle_rol_fondo_reserva) as fondo_reserva,sum(detalle_rol.detalle_rol_decimo_cuarto) as cuarto,sum(detalle_rol.detalle_rol_decimo_tercero) as tercero,sum(detalle_rol.detalle_rol_decimo_terceroacum) as terceroacum,sum(detalle_rol.detalle_rol_decimo_cuartoacum) as cuartoacum,sum(detalle_rol.detalle_rol_iess_asumido) as asumido,sum(detalle_rol.detalle_rol_iess_asumido) as asumido,sum(detalle_rol.detalle_rol_aporte_patronal) as aporte,sum(detalle_rol.detalle_rol_total_anticipo) as anticipo,sum(detalle_rol.detalle_rol_impuesto_renta) as impu_renta,sum(detalle_rol.detalle_rol_total_dias) as sueldos,sum(detalle_rol.detalle_rol_otros_ingresos) as otrosingresos,sum(detalle_rol.detalle_rol_total_comisariato) as comisariato,sum(detalle_rol.detalle_rol_bonificacion_valor) as bonificaciones,sum(detalle_rol.detalle_rol_valor_he) as extras,sum(detalle_rol.detalle_rol_transporte) as transporte,sum(detalle_rol.detalle_rol_otra_bonificacion) as otrabonifi,sum(detalle_rol.detalle_rol_total_ingreso) as ingresos,sum(detalle_rol.detalle_rol_ext_salud) as extsalud,sum(detalle_rol.detalle_rol_ley_sol) as leysal,sum(detalle_rol.detalle_rol_vacaciones) as vacaciones,sum(detalle_rol.detalle_rol_prestamo_quirografario) as ppqq,sum(detalle_rol.detalle_rol_prestamo_hipotecario) as hipoteca,sum(detalle_rol.detalle_rol_prestamo) as prestamos,sum(detalle_rol.detalle_rol_multa) as multas,sum(detalle_rol.detalle_rol_otros_egresos) as otrosegre,sum(detalle_rol.detalle_rol_total_egreso) as egresos,tipo_empleado.tipo_id,tipo_empleado.tipo_descripcion,cabecera_rol_iesspatronal as patronal,cabecera_rol_iesspersonal as personal,cabecera_rol_total_anticipos as anticipos_total,cabecera_rol.cabecera_rol_fr_acumula as acumula')->get(); 
            $tipos=Tipo_Empleado::Tipos()->get();
            $count=1;
            
            foreach($tipo as $roles){
                foreach ($tipos as $tiposroles) {    
                    if($tiposroles->tipo_id==$roles->tipo_id){   
                         
                        for ($i = 1; $i <= count($datos); $i++)  {                           
                            if($datos[$i]["tipo"]==$tiposroles->tipo_descripcion){
                                $datos[$i]["sueldos"]=$datos[$i]["sueldos"]+ $roles->sueldos;
                                $datos[$i]["otrosingresos"]=$datos[$i]["otrosingresos"]+ $roles->otrosingresos;
                                $datos[$i]["bonificaciones"]=$datos[$i]["bonificaciones"]+ $roles->bonificaciones;
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
                                $datos[$i]["tercero"]=$datos[$i]["tercero"]+ $roles->tercero;
                                $datos[$i]["cuarto"]=$datos[$i]["cuarto"]+ $roles->cuarto;
                                $datos[$i]["acumula"]=$datos[$i]["acumula"]+ $roles->acumula;
                                $datos[$i]["fondo_reserva"]=$datos[$i]["fondo_reserva"]+ $roles->fondo_reserva;
                                $datos[$i]["iecesecap"]=$datos[$i]["iecesecap"]+ $roles->iecesecap;
                                $datos[$i]["liquido_pagar"]=$datos[$i]["liquido_pagar"]+ $roles->liquido_pagar;
                                $existe=1;
                            }

                        }
                        
                        if($existe==0){

                            $datos[$count]["tipo"]=$tiposroles->tipo_descripcion;
                            $datos[$count]["sueldos"]=$roles->sueldos;
                            $datos[$count]["otrosingresos"]=$roles->otrosingresos;
                            $datos[$count]["bonificaciones"]=$roles->bonificaciones;
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
                            $datos[$count]["acumula"]=$roles->acumula;
                            $datos[$count]["fondo_reserva"]=$roles->fondo_reserva;
                            $datos[$count]["iecesecap"]=$roles->iecesecap;
                            $datos[$count]["liquido_pagar"]=$roles->liquido_pagar;
                            $count++;
                        }
                        
                    }
                }    
            }
           
            return view('admin.recursosHumanos.contabilizacionMensual.index',['datos'=>$datos,'rol'=>$rol,'consumo'=>Centro_Consumo::CentroConsumos()->get(),'categoria'=>Categoria_Producto::Categorias()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo');
        }

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
