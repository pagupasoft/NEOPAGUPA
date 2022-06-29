<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Empleado;
use App\Http\Controllers\Controller;
use App\Models\Detalle_Mantenimiento;
use App\Models\Detalle_Orden_Mantenimiento;
use App\Models\Orden_Mantenimiento;
use App\Models\Responsable_Mantenimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ordenMantenimientoController extends Controller
{
    public function index(){
        $ordenes=Orden_Mantenimiento::ordenes()->get();

        $data=[
            'result'=>'OK',
            'cantidad' => count($ordenes),
            'data' => $ordenes
        ];

        return response()->json($data, 200);
    }

    public function listaOrdenes(){
        $ordenes=Orden_Mantenimiento::ordenes()->get();

        foreach($ordenes as $orden){
            $orden->detalles;
            $orden->detallesOrden;
            $orden->responsables;
            $orden->tipo;

            foreach($orden->responsables as $resp){
                $resp->empleado;
            }
        }

        $data=[
            'result'=>'OK',
            'cantidad' => count($ordenes),
            'ordenes' => $ordenes
        ];

        //return $ordenes;

        return view('admin.mantenimiento.index', $data);
    }

    public function comprobarStock($id){
        $orden=Orden_Mantenimiento::findOrFail($id);

        $orden->detallesOrden;
        
        foreach($orden->detallesOrden as $det){
            $det->producto;
        }
        

        $data=[
            'result'=>'OK',
            'orden' => $orden
        ];

        //return $orden;

        return view('admin.mantenimiento.stock', $data);
    }

    public function getOrden($id){
        //$gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=', Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
        //$permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();

        try{
            $orden=Orden_Mantenimiento::findOrFail($id);

            $orden->detalles;
            $orden->detallesOrden;
            $orden->responsables;

            $orden->cliente;

            foreach($orden->responsables as $resp){
                $resp->empleado;
            }

            foreach($orden->detallesOrden as $det){
                $det->producto;
            }

            $data=[
                'result'=>'OK',
                'data' => $orden
            ];

            return response()->json($data, 200);
        }
        catch(\Exception $e){
            $data=[
                'result'=>'OK',
                'data' => []
            ];
            return response()->json($data, 200);
        }
    }

    public function create(){
        
    }
    public function store(Request $request){
        try{
            DB::beginTransaction();
            $ordenes=Orden_Mantenimiento::ordenes()->get();

            $nuevaOrden=new Orden_Mantenimiento();
            $nuevaOrden->orden_numero=count($ordenes);
            $nuevaOrden->orden_serie="ASDDDD";
            $nuevaOrden->orden_secuencial=43;
            
            $nuevaOrden->orden_fecha_inicio=date('Y-m-d');
            $nuevaOrden->orden_prioridad=$request->prioridad;
            $nuevaOrden->orden_lugar=$request->lugar;
            
            $nuevaOrden->orden_descripcion="";
            $nuevaOrden->orden_asignacion=$request->asignacion;
            $nuevaOrden->orden_logistica=$request->logistica;
            $nuevaOrden->orden_observacion="";
            $nuevaOrden->orden_recibido_por="";
            $nuevaOrden->orden_estado=1;

            $nuevaOrden->tipo_id=$request->tipo_id;
            $nuevaOrden->user_id=3;
            $nuevaOrden->cliente_id=$request->cliente;
            $nuevaOrden->sucursal_id=1;
            $nuevaOrden->save();

            for ($i = 0; $i < count($request->responsable); ++$i){
                $responsable=new Responsable_Mantenimiento();
                $responsable->empleado_id=$request->responsable[$i];
                $responsable->orden_id=$nuevaOrden->orden_id;
                $responsable->responsable_estado=1;
                $responsable->save();
            }

            for ($i = 0; $i < count($request->detalle); ++$i){
                $detalle=new Detalle_Mantenimiento();
                $detalle->detalle_fecha_inicio=date('d-m-Y');
                $detalle->detalle_descripcion=$request->detalle[$i];
                $detalle->orden_id=$nuevaOrden->orden_id;
                $detalle->detalle_estado=1;
                $detalle->save();
            }

            $stock=true;
            for ($i = 0; $i < count($request->id_detalle_orden); ++$i){
                $detOrden=new Detalle_Orden_Mantenimiento();
                $detOrden->producto_id=$request->id_detalle_orden[$i];
                $detOrden->detalle_orden_cantidad=$request->cantidad_detalle_orden[$i];
                $detOrden->orden_id=$nuevaOrden->orden_id;
                $detOrden->detalle_orden_estado=1;
                $detOrden->save();

                if($request->stock_detalle_orden[$i] <  $request->cantidad_detalle_orden[$i])  $stock=false;
            }

            if($stock){
                $nuevaOrden->orden_estado=2;
                $nuevaOrden->save();
            }

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de nueva orden numero '.$nuevaOrden->numero, $nuevaOrden->orden_id, '');

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"guardado correctamente"], 200);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo guardar".$e->getMessage()], 202);
        }
    }

    public function actualizarEstadoOrden(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);
            $orden->orden_estado=2;
            $orden->update();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se actualizó la orden '.$orden->numero.' a estado 2', $orden->orden_id, $orden->orden_id);

            DB::commit();
            return redirect('listaMantenimiento')->with('success', 'La Orden actualizada correctamente');;
        }
        catch(\Exception $e){
            DB::rollBack();
            return redirect('listaMantenimiento')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function login(Request $request){
        //$prestamos=DB::table('users')->join('prestamo_banco','prestamo_banco.prestamo_id','=','detalle_prestamo.prestamo_id')
        

        try{
            $userdata = array(
                'user_username' => $request->get('idUsername'),
                'password' => $request->get('idPassword'),
                'user_estado' => 1
            );
            if (Auth::attempt($userdata, true)) {
                $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=', Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
                $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();

                $request->session()->regenerate();

                $user=User::findOrFail(Auth::user()->user_id);
                Auth::login($user); 

                $auditoria = new generalController();
                $auditoria->registrarAuditoria('Inicio de sesion usuario->'.$request->get('idUsername').' Con Id ->'.$user->user_id, $user->user_id, '');

                $data=["result"=>"OK",
                    "user_id"=> $user->user_id,
                    "user_username"=>$user->user_username,
                    "user_usermail"=>$user->user_correo,
                    "rol"=>$user->roles[0]->rol_nombre,
                    "grupos"=>$gruposPermiso,
                    "permisosAdmin"=>$permisosAdmin
                ];

                return response()->json($data, 200);
            }
            return response()->json(["result"=>"FAIL", "message"=>"credenciales incorrectas"], 202);
        }
        catch(\Exception $ex){      
            return response()->json(["result"=>"FAIL"], 202);
        }
    }

    public function actualizarOrden(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);
            $orden->orden_observacion=$request->observacion;
            $orden->orden_resultado=$request->resultado;
            $orden->orden_estado=$request->estado;
            $orden->orden_finalizacion=date('Y-m-d', time());  
            
            if($request->imagen){
                $orden->orden_recibido_por=$request->recibido_por;
                $url = $this->subir_foto($request->imagen, $orden, $request->recibido_por);
            }

            $orden->save();
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se actualizó la orden '.$orden->numero, $orden->orden_id, $orden->orden_id);

            DB::commit();
            return redirect('listaMantenimiento')->with('success', 'La Orden actualizada correctamente');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return redirect('listaMantenimiento')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    private function subir_foto($imagen, $orden, $recibido){
        $imageData = base64_decode($imagen); // <-- **Change is here for variable name only**
        $photo = imagecreatefromstring($imageData);


        $ruta = 'DocumentosMantenimiento/1/'.$orden->orden_id;
        $extension = "jpg";

        if ($imagen) {
            if (!is_dir(public_path().'/'.$ruta))  mkdir(public_path().'/'.$ruta, 0777, true);

            $name='firma_'.$recibido.'.'.$extension;
            //$path=$photo->move(public_path().'/'.$ruta, $name);
            imagejpeg($photo, public_path().'/'.$ruta.'/'.$name);
        
            return $ruta.'/'.$name;
        }
        else
            return null;
    }


    public function agregarResponsable(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);

            $responsable=new Responsable_Mantenimiento();
            $responsable->empleado_id=$request->empleado_id;
            $responsable->orden_id=$orden->orden_id;
            $responsable->responsable_estado=1;
            $responsable->save();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se agregó un responsable a la orden '.$orden->numero, $orden->orden_id, 'responsable con id '.$responsable->responsable_id.' empleado: '.$request->empleado_id);

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"agregado correctamente"], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo agregar al Responsable".$e->getMessage(),], 410);
        }
    }

    public function quitarResponsable(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);
            $responsable=Responsable_Mantenimiento::findOrFail($request->responsable_id);
            $responsable->delete();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se eliminó un responsable a la orden '.$orden->numero, $orden->orden_id, 'id :'.$request->responsable_id);

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"responable eliminado correctamente"], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo eliminar al Responsable".$e->getMessage(),], 410);
        }
    }

    public function agregarDetalle(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);

            $detalle=new Detalle_Mantenimiento();
            $detalle->detalle_fecha_inicio=date('d-m-Y');
            $detalle->detalle_descripcion=$request->descripcion;
            $detalle->orden_id=$request->orden_id;
            $detalle->detalle_estado=1;
            $detalle->save();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se agregó un detalle a la orden '.$orden->numero, $orden->orden_id, '');

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"agregado correctamente"], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo agregar al Responsable".$e->getMessage(),], 410);
        }
    }

    public function quitarDetalle(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);
            $detalle=Detalle_Mantenimiento::findOrFail($request->detalle_id);
            $detalle->delete();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se eliminó un detalle a la orden '.$orden->numero, $orden->orden_id, 'id :'.$request->detalle_id);

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"detalle eliminado correctamente"], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo eliminar detalle".$e->getMessage(),], 410);
        }
    }

    public function agregarDetalleMantenimiento(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);

            $detalle=new Detalle_Mantenimiento();
            $detalle->detalle_cantidad=$request->cantidad;
            $detalle->detalle_descripcion=$request->descripcion;
            $detalle->orden_id=$request->orden_id;
            $detalle->detalle_estado=1;
            $detalle->save();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se agregó un detalle a la orden '.$orden->numero, $orden->orden_id, '');

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"agregado correctamente"], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo agregar al Responsable".$e->getMessage(),], 410);
        }
    }

    public function quitarDetalleMantenimiento(Request $request){
        try{
            DB::beginTransaction();

            $orden=Orden_Mantenimiento::findOrFail($request->orden_id);
            $detalle=Detalle_Mantenimiento::findOrFail($request->detalle_id);
            $detalle->delete();

            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Se eliminó un detalle a la orden '.$orden->numero, $orden->orden_id, 'id :'.$request->detalle_id);

            DB::commit();
            return response()->json(["result"=>"OK", "mensaje"=>"detalle eliminado correctamente"], 201);
        }
        catch(\Exception $e){
            DB::rollBack();
            return response()->json(["result"=>"FAIL", "mensaje"=>"no se pudo eliminar detalle".$e->getMessage(),], 410);
        }
    }


    public function show(){
        
    }

    public function edit(){
        
    }

    public function update(){
        
    }

    public function editar(){

    }

    public function getProductos(){
        //$productos
    }
}
