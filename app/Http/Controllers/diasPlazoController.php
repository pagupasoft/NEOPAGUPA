<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cuenta_Cobrar;
use App\Models\Factura_Venta;
use App\Models\Punto_Emision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class diasPlazoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->join('tipo_grupo','tipo_grupo.grupo_id','=','grupo_permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $tipoPermiso=DB::table('usuario_rol')->select('tipo_grupo.grupo_id','tipo_grupo.tipo_id', 'tipo_nombre','tipo_icono','tipo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('tipo_grupo','tipo_grupo.tipo_id','=','permiso.tipo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('tipo_orden','asc')->distinct()->get();
    $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'tipo_id', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            
            return view('admin.ventas.diasPlazo.index',['PE'=>Punto_Emision::puntos()->get(),'tipoPermiso'=>$tipoPermiso,'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }catch(\Exception $ex){
            return redirect('inicio')->with('error','Ocurrio un error vuelva a intentarlo('.$ex->getMessage().')');
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
            $general = new generalController();
            
                DB::beginTransaction();
              
                $factura=Factura_Venta::findOrFail($request->get('factura_id'));
                $date=$factura->factura_fecha;
                $dia="+ ".$request->get('factura_dias_plazo')." days";
                $fecha =date("d-m-Y",strtotime($date.$dia)); 
                $cierre = $general->cierre($fecha);
                if ($cierre) {
                    return redirect('cambioPlazo')->with('error2', 'No puede realizar la operacion por que pertenece a un mes bloqueado');
                }
                $factura->factura_fecha_pago=$fecha;
                $factura->factura_dias_plazo=$request->get('factura_dias_plazo');
                $factura->save();
                $general->registrarAuditoria('Actualizacion de factura fecha de pago N° '.$factura->factura_numero,$factura->factura_id,'Con fecha de pago Pago '.$fecha.' Dias de pago'.$factura->factura_dias_plazo );
                           
                $cuenta=Cuenta_Cobrar::findOrFail($factura->cuentaCobrar->cuenta_id);
                $cuenta->cuenta_fecha_fin=$fecha;
                $cuenta->save();
                $general->registrarAuditoria('Actualizacion de fecha fin de Cuenta Cobrar con Id  '.$cuenta->cuenta_id,$cuenta->cuenta_id,'Con fecha de pago Pago '.$fecha.' Dias de pago'.$factura->factura_dias_plazo);
                           
               DB::commit();
                return redirect('cambioPlazo')->with('success','Documento anulado exitosamente');
            

           
        }catch(\Exception $ex){
           DB::rollBack();
            return redirect('cambioPlazo')->with('error','Ocurrio un error vuelva a intentarlo('.$ex->getMessage().')');
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
