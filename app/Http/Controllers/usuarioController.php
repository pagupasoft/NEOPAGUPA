<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Punto_Emision;
use App\Models\Rol;
use App\Models\User;
use App\Models\Usuario_PuntoE;
use App\Models\Usuario_Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class usuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $usuarios=User::usuarios()->get();
            return view('admin.seguridad.usuario.index',['usuarios'=>$usuarios, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
            DB::beginTransaction();
            $usuario = new User();
            $usuario->user_username = $request->get('idUsername');
            $usuario->user_cedula = $request->get('idCedula');  
            $usuario->user_nombre = $request->get('idNombre');  
            $usuario->user_correo = $request->get('idCorreo');            
            $usuario->user_tipo  = 1;
            $usuario->user_estado  = 1;
            $password=$this->generarPass();
            $usuario->password  = bcrypt($password);
            $usuario->empresa_id = Auth::user()->empresa_id;
            $usuario->save();
            DB::afterCommit(function () use($request,$password){
                $this->enviarCorreoUsuario($request->get('idCorreo'),$request->get('idNombre'),$request->get('idUsername'),$password);
            });
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Registro de usuario -> '.$request->get('idUsername'),'0','');
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('usuario')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('usuario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function restablecePass($id){
        try{
            $usuario=User::usuario($id)->first();
            if(!$usuario){
                return redirect('usuario');
            }
            DB::beginTransaction();
            $usuario= User::findOrFail($id);
            $password=$this->generarPass();
            $usuario->password  = bcrypt($password);
            $usuario->save();
            DB::afterCommit(function () use($usuario,$password){
                $this->enviarCorreoUsuario($usuario->user_correo,$usuario->user_nombre,$usuario->user_username,$password);
            });
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Restablecer contraseña de usuario -> '.$usuario->user_username,'0','');
            /*Fin de registro de auditoria */
             DB::commit();
            return redirect('usuario')->with('success','Contraseña restablecida con exito');
        } catch(\Exception $ex){
            DB::rollBack();
            return redirect('usuario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function enviarCorreoUsuario($correo, $nombre, $username, $password){
        $empresa=Empresa::Empresa()->first();
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP(); // tell to use smtp
            $mail->CharSet = 'utf-8'; // set charset to utf8
            $mail->Host = trim('mail.pagupasoft.com');
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'tls';//$mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
            $mail->Port = trim('587'); // most likely something different for you. This is the mailtrap.io port i use for testing. 
            $mail->Username = trim('neopagupa@pagupasoft.com');
            $mail->Password = trim('PagupaServer07@');
            $mail->setFrom(trim('neopagupa@pagupasoft.com'), 'NEOPAGUPA SISTEMA CONTABLE');
            $mail->Subject = 'NEOPAGUPA-Sistema Contable';
            $mail->MsgHTML('Este es un correo automatico a continuacion se detalle la Empresa: .'.$empresa->empresa_nombreComercial.', su usuario -> '.$username. ' y su contraseña es: '.$password);
            $mail->addAddress(trim($correo), $nombre);
            $mail->SMTPOptions= array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
                )
            );
            $mail->send();
        } catch (Exception $ex) {
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Error al restablecer contraseña de usuario ','0',$ex);
            return($ex);
        }
    }

    public function generarPass(){
        $caracteres='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $longpalabra=8;
        $pass='';
        for($pass='', $n=strlen($caracteres)-1; strlen($pass) < $longpalabra ; ) {
            $x = rand(0,$n);
            $pass.= $caracteres[$x];
        }
        return $pass;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $usuario=User::usuario($id)->first();
            if($usuario){
                return view('admin.seguridad.usuario.ver',['usuario'=>$usuario, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
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
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $usuario=User::usuario($id)->first();
            if($usuario){
                return view('admin.seguridad.usuario.editar',['usuario'=>$usuario, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
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
        try{
            DB::beginTransaction();
            $usuario=User::findOrFail($id);
            $usuario->user_username = $request->get('idUsername');
            $usuario->user_cedula = $request->get('idCedula');  
            $usuario->user_nombre = $request->get('idNombre');  
            $usuario->user_correo = $request->get('idCorreo');         
            if ($request->get('idEstado') == "on"){   
                $usuario->user_estado=1;
            }else{
                $usuario->user_estado=0;
            }
            $usuario->save();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de usuario -> '.$request->get('idUsername'),'0','Usuario con id -> '.$id);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('usuario')->with('success','Datos actualizados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('usuario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            $usuario = User::findOrFail($id);
            $usuario->delete();
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Eliminacion de usuario -> '.$usuario->user_username,'0','Usuario con id -> '.$id);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('usuario')->with('success','Datos eliminados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('usuario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function delete($id)
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $usuario=User::usuario($id)->first();
            if($usuario){
                return view('admin.seguridad.usuario.eliminar',['usuario'=>$usuario, 'PE'=>Punto_Emision::puntos()->get(),'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function roles($id)
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $roles=Rol::roles()->where('rol_nombre','<>','SuperAdministrador')->get();
            $usuario=User::usuario($id)->first();
            if($usuario){
                return view('admin.seguridad.usuario.roles',['usuario'=>$usuario, 'PE'=>Punto_Emision::puntos()->get(),'roles'=>$roles, 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function guardarRoles(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $rolesAsignados='';
            $usuario_rol=Usuario_Rol::where('user_id','=',$id)->delete();
            $roles=Rol::roles()->get();
            foreach ($roles as $rol) {  
                if($request->get($rol->rol_id) == "on"){
                    $usuario_rol= new Usuario_Rol;
                    $usuario_rol->rol_id=$rol->rol_id;
                    $usuario_rol->user_id=$id;
                    $usuario_rol->save();
                    $rolesAsignados=$rolesAsignados.'-'.$rol->rol_nombre;
                }
            }
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de roles de usuario con id -> '.$id,'0','Los roles asignados fueron -> '.$rolesAsignados);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('usuario')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('usuario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function puntosEmisionPermiso($id)
    {
        try {
            $gruposPermiso=DB::table('usuario_rol')->select('grupo_permiso.grupo_id', 'grupo_nombre', 'grupo_icono','grupo_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->join('grupo_permiso','grupo_permiso.grupo_id','=','permiso.grupo_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('grupo_orden','asc')->distinct()->get();
            $permisosAdmin=DB::table('usuario_rol')->select('permiso_ruta', 'permiso_nombre', 'permiso_icono', 'grupo_id', 'permiso_orden')->join('rol_permiso','usuario_rol.rol_id','=','rol_permiso.rol_id')->join('permiso','permiso.permiso_id','=','rol_permiso.permiso_id')->where('permiso_estado','=','1')->where('usuario_rol.user_id','=',Auth::user()->user_id)->orderBy('permiso_orden','asc')->get();
            $puntosE=Punto_Emision::Puntos()->get();
            $usuario=User::usuario($id)->first();
            if($usuario){
                return view('admin.seguridad.usuario.puntosE',['usuario'=>$usuario, 'PE'=>Punto_Emision::puntos()->get(),'puntosE'=>$puntosE, 'gruposPermiso'=>$gruposPermiso, 'permisosAdmin'=>$permisosAdmin]);
            }else{
                return redirect('/denegado');
            }
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function guardarPuntos(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $puntosAsignados='';
            $usuario_puntoE=Usuario_PuntoE::where('user_id','=',$id)->delete();
            $puntosE=Punto_Emision::Puntos()->get();
            foreach ($puntosE as $punto) {  
                if($request->get($punto->punto_id) == "on"){
                    $usuario_puntoE= new Usuario_PuntoE;
                    $usuario_puntoE->punto_id=$punto->punto_id;
                    $usuario_puntoE->user_id=$id;
                    $usuario_puntoE->usuarioP_estado=1;
                    $usuario_puntoE->save();
                    $puntosAsignados=$puntosAsignados.'-'.$punto->punto_serie.'->'.$punto->punto_descripcion;
                }
            }
            /*Inicio de registro de auditoria */
            $auditoria = new generalController();
            $auditoria->registrarAuditoria('Actualizacion de permisos a puntos de emision de usuario con id -> '.$id,'0','Los puntos de emision asignados fueron -> '.$puntosAsignados);
            /*Fin de registro de auditoria */
            DB::commit();
            return redirect('usuario')->with('success','Datos guardados exitosamente');
        }catch(\Exception $ex){
            DB::rollBack();
            return redirect('usuario')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
}
