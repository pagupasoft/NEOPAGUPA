<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;

class loginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function index()
    {
        try{

            return view('admin.seguridad.auth.login');
        }
        catch(\Exception $ex){      
            return redirect('inicio')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }
    public function authenticate(Request $request)
    {
        try{
            $empresa = Empresa::where('empresa_ruc', $request->get('idRuc'))->first();
            $id = 0;
            if ($empresa != null) {
                $id = $empresa->empresa_id;
            }
            $userdata = array(
                'empresa_id' => $id,
                'user_username' => $request->get('idUsername'),
                'password' => $request->get('idPassword'),
                'user_estado' => 1
            );
            if (Auth::attempt($userdata, true)) {
                $request->session()->regenerate();
                Auth::login(User::findOrFail(Auth::user()->user_id));
                $auditoria = new generalController();
                $auditoria->registrarAuditoria('Inicio de sesion usuario->'.$request->get('idUsername').' Con Id ->'.Auth::user()->user_id,Auth::user()->user_id,'');
                return redirect()->intended('principal');
            }
            return back()->withErrors([
                'user_username' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
            ]);
        }
        catch(\Exception $ex){      
            return redirect('principal')->with('error2','Ocurrio un error en el procedimiento. Vuelva a intentar. ('.$ex->getMessage().')');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}