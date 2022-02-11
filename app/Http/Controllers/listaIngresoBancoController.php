<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Deposito;
use App\Models\Diario;
use App\Models\Ingreso_Banco;
use App\Models\Punto_Emision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class listaIngresoBancoController extends Controller
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
            return view('admin.bancos.listaIngresoBanco.index',['gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
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
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $ingresoBancos=Ingreso_Banco::ReporteingresoBancos()
            ->where('ingreso_fecha','>=',$request->get('fecha_desde'))
            ->where('ingreso_fecha','<=',$request->get('fecha_hasta'))        
            ->where('ingreso_estado','=','1')->get();
            return view('admin.bancos.listaIngresoBanco.index',['fecha_desde'=>$request->get('fecha_desde'),'fecha_hasta'=>$request->get('fecha_hasta'),'ingresoBancos'=>$ingresoBancos,'gruposPermiso'=>$gruposPermiso, 'PE'=>Punto_Emision::puntos()->get(),'permisosAdmin'=>$permisosAdmin]);
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
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $ingresoBanco=Ingreso_Banco::ingresoBanco($id)->first();
            if($ingresoBanco){
                return view('admin.bancos.listaIngresoBanco.ver',['ingresoBanco'=>$ingresoBanco, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function delete($id)
    {
        try{
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $ingresoBanco=Ingreso_Banco::ingresoBanco($id)->first();
            if($ingresoBanco){
                return view('admin.bancos.listaIngresoBanco.eliminar',['ingresoBanco'=>$ingresoBanco,'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
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
        try{
            DB::beginTransaction();
            $ingresoBanco = Ingreso_Banco::findOrFail($id);
            $general = new generalController();
           
            $cierre = $general->cierre($ingresoBanco->ingreso_fecha);          
            if($cierre){
                return redirect('listaIngresoBanco')->with('error2','No puede realizar la operacion por que pertenece a un mes bloqueado');
            }
            if(isset($ingresoBanco->deposito)){
                $ingreso= Ingreso_Banco::findOrFail($id);
                $ingreso->deposito_id=null;    
                $ingreso->save();

                $deposito=Deposito::findOrFail($ingresoBanco->deposito_id);
            }
            foreach($ingresoBanco->diario->detalles as $detalle){                            
                $detalle->delete();
                $general->registrarAuditoria('Eliminacion de detalles de Ingreso de banco numero: -> '.$ingresoBanco->ingreso_numero, $id, '');
            } 
            if(isset($deposito)){
                $deposito->delete();
                $general->registrarAuditoria('Eliminacion de Deposito: -> '.$ingresoBanco->deposito->deposito_numero, $id, 'Con Tipo -> '.$ingresoBanco->deposito->deposito_tipo.'Con valor de -> '.$ingresoBanco->deposito->deposito_valor);
            }
            $ingreso= Ingreso_Banco::findOrFail($id);
            $ingreso->diario_id=null;
            $ingreso->save();   
            
            $diario = Diario::findOrFail($ingresoBanco->diario->diario_id);
            $ingresoBanco->diario->delete();                        
            $general->registrarAuditoria('Eliminacion de Diario de Ingreso de banco numero: -> '.$ingresoBanco->ingreso_numero, $id, 'Con el diario-> '.$diario->diario_codigo.'Comentario -> '.$diario->diario_comentario);
                                    
            $aux=$ingresoBanco;
            $general->registrarAuditoria('Eliminacion de Ingreso de banco numero: -> '.$aux->ingreso_numero, $id, 'Con Valor -> '.$aux->ingreso_valor.' Descripcion -> '.$aux->ingreso_descripcion);
            DB::commit();
            return redirect('listaIngresoBanco')->with('success','Datos Eliminados exitosamente');            
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('listaIngresoBanco')->with('error','El registro no pudo ser borrado, tiene resgitros adjuntos.');
        }    
    }
}
