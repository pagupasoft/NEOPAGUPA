<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cabecera_Rol_CM;
use App\Models\Empresa;
use App\Models\Rubro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use App\NEOPAGUPA\ViewExcel;
use Excel;
class RolReporteDetalladoController extends Controller
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
            $empleado=Cabecera_Rol_CM::EmpleadosRol()->select('empleado.empleado_id','empleado.empleado_nombre')->distinct()->get();
            $rubros=Rubro::Rubrostipos()->get();
            return view('admin.RHCostaMarket.reportesRol.indexdetalle',['rubros'=>$rubros,'empleado'=>$empleado,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);   
        }
        catch(\Exception $ex){      
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
        if (isset($_POST['pdf'])){
            return $this->pdf($request);
        }
        if (isset($_POST['excel'])){
            return $this->excel($request);
        }
        if (isset($_POST['buscar'])){
            return $this->buscar($request);
        }
    }
    public function excel(Request $request){
        try{ 
            $rol=Cabecera_Rol_CM::Buscar($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('nombre_empleado'))->get();
            $datos=null;
            $count=1;  
            $rubros=Rubro::Rubrostipos()->get();
            $resultado=null;

            foreach ($rol as $roles) {
                $datos[$count]["cedula"]=$roles->empleado->empleado_cedula;
                $datos[$count]["nombre"]=$roles->empleado->empleado_nombre;
                foreach ($rubros as $rubro) {
                    $datos[$count]["tipo"]=$rubro->rubro_tipo;
                    $nombre=$rubro->rubro_nombre;
                    $datos[$count][$nombre]=0;
                    foreach ($roles->detalles as $detalle) {
                        if ($detalle->rubro_id==$rubro->rubro_id) {
                            $datos[$count][$nombre]=$detalle->detalle_rol_valor;
                        }
                    }

                }
                $datos[$count]["totalingresos"]=$roles->cabecera_rol_total_ingresos;
                $datos[$count]["totalegresos"]=$roles->cabecera_rol_total_egresos;
                $datos[$count]["ReservaPagado"]=$roles->cabecera_rol_fr_acumula;
                $datos[$count]["TerceroPagado"]=$roles->cabecera_rol_decimotercero_acumula;
                $datos[$count]["CuartoPagado"]=$roles->cabecera_rol_decimocuarto_acumula;
                $datos[$count]["decimoTercero"]=$roles->cabecera_rol_decimotercero;
                $datos[$count]["decimoCuarto"]=$roles->cabecera_rol_decimocuarto;
                $datos[$count]["fondoReserva"]=$roles->cabecera_rol_fondo_reserva;
                $datos[$count]["iece"]=$roles->cabecera_rol_iece_secap;
                $datos[$count]["aportePatronal"]=$roles->cabecera_rol_aporte_patronal;
                $datos[$count]["vacacion"]=$roles->cabecera_rol_vacaciones;
                $datos[$count]["total"]=$roles->cabecera_rol_pago;
                $count++;
            } 
            $resultado[1]=$rubros;
            $resultado[2]=$datos;
            
            return Excel::download(new ViewExcel('admin.formatosExcel.roldetallado',$resultado),'NEOPAGUPA  Sistema Contable.xls');
        }catch(\Exception $ex){
            return redirect('rolreporteDetallado')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function pdf(Request $request){
        try{ 
            $rol=Cabecera_Rol_CM::Buscar($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('nombre_empleado'))->get();
            $datos=null;
            $count=1;  
            $rubros=Rubro::Rubrostipos()->get();
            foreach ($rol as $roles) {
                $datos[$count]["cedula"]=$roles->empleado->empleado_cedula;
                $datos[$count]["nombre"]=$roles->empleado->empleado_nombre;
                foreach ($rubros as $rubro) {
                    $datos[$count]["tipo"]=$rubro->rubro_tipo;
                    $nombre=$rubro->rubro_nombre;
                    $datos[$count][$nombre]=0;
                    foreach ($roles->detalles as $detalle) {
                        if ($detalle->rubro_id==$rubro->rubro_id) {
                            $datos[$count][$nombre]=$detalle->detalle_rol_valor;
                        }
                    }

                }
                $datos[$count]["totalingresos"]=$roles->cabecera_rol_total_ingresos;
                $datos[$count]["totalegresos"]=$roles->cabecera_rol_total_egresos;
                $datos[$count]["ReservaPagado"]=$roles->cabecera_rol_fr_acumula;
                $datos[$count]["TerceroPagado"]=$roles->cabecera_rol_decimotercero_acumula;
                $datos[$count]["CuartoPagado"]=$roles->cabecera_rol_decimocuarto_acumula;
                $datos[$count]["decimoTercero"]=$roles->cabecera_rol_decimotercero;
                $datos[$count]["decimoCuarto"]=$roles->cabecera_rol_decimocuarto;
                $datos[$count]["fondoReserva"]=$roles->cabecera_rol_fondo_reserva;
                $datos[$count]["iece"]=$roles->cabecera_rol_iece_secap;
                $datos[$count]["aportePatronal"]=$roles->cabecera_rol_aporte_patronal;
                $datos[$count]["vacacion"]=$roles->cabecera_rol_vacaciones;
                $datos[$count]["total"]=$roles->cabecera_rol_pago;
                $count++;
            } 
            $empresa =  Empresa::empresa()->first();
            $ruta = public_path().'/PDF/'.$empresa->empresa_ruc;
            if (!is_dir($ruta)) {
                mkdir($ruta, 0777, true);
            }
            $view =  \View::make('admin.formatosPDF.rolesCM.roldetallado', ['rubros'=>$rubros,'datos'=>$datos,'desde'=>$request->get('fecha_desde'),'hasta'=>$request->get('fecha_hasta'),'empresa'=>$empresa]);
            $nombreArchivo = 'reporteroldetallado';
            return PDF::loadHTML($view)->setPaper('a4', 'landscape')->save('PDF/'.$empresa->empresa_ruc.'/'.$nombreArchivo.'.pdf')->download($nombreArchivo.'.pdf');

        }catch(\Exception $ex){
            return redirect('rolreporteDetallado')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    
    public function buscar(Request $request)
    { 
        $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
        $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();        
        
        $rol=Cabecera_Rol_CM::Buscar($request->get('fecha_desde'),$request->get('fecha_hasta'),$request->get('nombre_empleado'))->get();
        $datos=null;
        $count=1;
        $empleado=Cabecera_Rol_CM::EmpleadosRol()->select('empleado.empleado_id','empleado.empleado_nombre')->distinct()->get();
       
        
        $rubros=Rubro::Rubrostipos()->get();
        foreach ($rol as $roles) {
            $datos[$count]["cedula"]=$roles->empleado->empleado_cedula;
            $datos[$count]["nombre"]=$roles->empleado->empleado_nombre;
            foreach ($rubros as $rubro) {
                $datos[$count]["tipo"]=$rubro->rubro_tipo;
                $nombre=$rubro->rubro_nombre;
                $datos[$count][$nombre]=0;
                foreach ($roles->detalles as $detalle) {
                    if ($detalle->rubro_id==$rubro->rubro_id) {
                        $datos[$count][$nombre]=$detalle->detalle_rol_valor;
                    }
                }

            }
            $datos[$count]["totalingresos"]=$roles->cabecera_rol_total_ingresos;
            $datos[$count]["totalegresos"]=$roles->cabecera_rol_total_egresos;
            $datos[$count]["ReservaPagado"]=$roles->cabecera_rol_fr_acumula;
            $datos[$count]["TerceroPagado"]=$roles->cabecera_rol_decimotercero_acumula;
            $datos[$count]["CuartoPagado"]=$roles->cabecera_rol_decimocuarto_acumula;
            $datos[$count]["decimoTercero"]=$roles->cabecera_rol_decimotercero;
            $datos[$count]["decimoCuarto"]=$roles->cabecera_rol_decimocuarto;
            $datos[$count]["fondoReserva"]=$roles->cabecera_rol_fondo_reserva;
            $datos[$count]["iece"]=$roles->cabecera_rol_iece_secap;
            $datos[$count]["aportePatronal"]=$roles->cabecera_rol_aporte_patronal;
            $datos[$count]["vacacion"]=$roles->cabecera_rol_vacaciones;
            $datos[$count]["total"]=$roles->cabecera_rol_pago;
            $count++;
        } 
        
        return view('admin.RHCostaMarket.reportesRol.indexdetalle',['datos'=>$datos,'rubros'=>$rubros,'fechadesde'=>$request->get('fecha_desde'),'fechahasta'=>$request->get('fecha_hasta'),'nombre_empleado'=>$request->get('nombre_empleado'),'empleado'=>$empleado,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);   
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
