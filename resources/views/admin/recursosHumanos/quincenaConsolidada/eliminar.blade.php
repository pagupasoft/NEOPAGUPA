@extends ('admin.layouts.admin')
@section('principal')
<form class="form-horizontal" method="POST"  action="{{ url("eliminarquincena") }} "> 
    @csrf 
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Eliminar Quincena Consolidada</h3>
            <div class="float-right">
                <button type="submit" id="generar" name="generar" class="btn btn-success btn-sm ">Eliminar Quincena</button>
                <button onclick='window.location = "{{ url("eliminarquincena") }}";' class="btn btn-default btn-sm"><i class="fa fa-times"></i>&nbsp;Cancelar</button>
            </div>
        </div>
        <div class="card"> 
            <br>     
            <div class="form-group row">
                <label for="fecha" class="col-sm-1 col-form-label"><center>Fecha:</center></label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="fecha" name="fecha"   value='{{$quincenas->quincena_fecha}}' readonly>
                    </div>
                    <label for="idsucursal" class="col-sm-1 col-form-label">Sucursal</label>   
                   
                    <div class="col-sm-2">
                        <input  class="form-control" value="{{ $quincenas->rango->puntoEmision->sucursal->sucursal_nombre }}"
                        type="text" readonly>
                    </div>
                    
                <br>
            </div>
    
            <div class="card-body">
                <div class="table-responsive">
                    @include ('admin.recursosHumanos.rolPagoConsolidado.items')
                    <table id="example5" name="example5" class="table table-bordered table-hover table-responsive sin-salto">
                        <thead>
                            
                            <tr >
            
                               
                                <th  class="text-center-encabesado">Cédula </th>
                                <th class="text-center-encabesado">Nombre </th>
                                
                                <th class="text-center-encabesado">Quincena </th>
                                
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($quincena))
                            @foreach($quincena as $x)  
                            <tr>
    
                                <td width="150">{{$x->empleado->empleado_cedula}} </td>
                                <td width="150">{{$x->empleado->empleado_nombre}} <input class="invisible" name="idquincena[]" value="{{$x->quincena_id}}" /></td>
                                
                                <td width="150">{{$x->quincena_valor}}</td>
                               
                                
                            </tr>
                            @endforeach
                        @endif   
                        </tbody>
                    </table>
                </div>
            </div>
        </div> 
       
        <h5 class="form-control" style="color:#fff; background:#17a2b8;">Forma de Pago</h5>   
            <div class="card-body">
            <div class="form-group row">
                    <label for="idTipo" class="col-sm-2 col-form-label">Tipo de Pago</label>
                    <div class="col-sm-10">
                    <label class="form-control" id="idTipo" name="idTipo"  >{{$quincenas->quincena_tipo}}</label>   
                            
                       
                    </div>
                </div>
               
                    <div class="form-group row">
                            <label for="banco_id" class="col-sm-2 col-form-label">Banco</label>
                            <div class="col-sm-10">
                            <label class="form-control" >
                                @if($quincenas->quincena_tipo=="Cheque")
                                   {{$cheque->cuentaBancaria->banco->bancoLista->banco_lista_nombre}}
                                @endif
                                @if($quincenas->quincena_tipo=="Transferencia")
                                   {{$transferencia->cuentaBancaria->banco->bancoLista->banco_lista_nombre}}
                                @endif
                            </label>   
                    
                                
                            </div>
                    </div>
                    <div class="form-group row">
                            <label for="cuenta_id" class="col-sm-2 col-form-label"># de Cuenta</label>
                            <div class="col-sm-10">
                            <label class="form-control" id="ncuenta" name="ncuenta"  >
                                @if($quincenas->quincena_tipo=="Cheque")
                                   {{$cheque->cuentaBancaria->cuenta_bancaria_numero}}
                                @endif
                                @if($quincenas->quincena_tipo=="Transferencia")
                                   {{$transferencia->cuentaBancaria->cuenta_bancaria_numero}}
                                @endif
                            </label>   
                            </div>
                    </div> 
                    <div class="form-group row">
                            <label for="idCuentaContable" class="col-sm-2 col-form-label">Cuenta Contable</label>
                            <div class="col-sm-10">
                            <label class="form-control" id="idCuentaContable" name="idCuentaContable"  >           
                                @if($quincenas->quincena_tipo=="Cheque")
                                   {{$cheque->cuentaBancaria->cuenta->cuenta_numero}}-{{$cheque->cuentaBancaria->cuenta->cuenta_nombre}}
                                   cuenta_nombre
                                @endif
                                @if($quincenas->quincena_tipo=="Transferencia")
                                   {{$transferencia->cuentaBancaria->cuenta->cuenta_numero}}-{{$transferencia->cuentaBancaria->cuenta->cuenta_nombre}}
                                @endif
                            </label>   
                    
                              
                            </div>
                    </div>         
                            
                           
                 
            </div>  
            <h5 class="form-control" style="color:#fff; background:#17a2b8;">Datos del Cheque</h5>
            <div class="card-body">
                <div class="form-group row">
                            <label for="idFechaCheque" class="col-sm-2 col-form-label">Fecha</label>
                            <div class="col-sm-10">
                            <label class="form-control" id="idFechaCheque" name="idFechaCheque"  >
                            @if($quincenas->quincena_tipo=="Cheque")
                                   {{$cheque->cheque_fecha_emision}}
                                @endif
                                @if($quincenas->quincena_tipo=="Transferencia")
                                   {{$transferencia->transferencia_fecha}}
                                @endif
                            </label>   
                    
                            </div>
                </div>                
                <div class="form-group row">
                    <label for="idNcheque" class="col-sm-2 col-form-label"># de Cheque</label>
                    <div class="col-sm-10">
                    <label class="form-control" >
                            @if($quincenas->quincena_tipo=="Cheque")
                                   {{$cheque->cheque_numero}}
                                @endif
                    </label>   
                    
                    </div>
                </div>
                <div class="form-group row">
                    <label for="idBeneficiario" class="col-sm-2 col-form-label">Beneficiario</label>
                    <div class="col-sm-10">
                    <label class="form-control" >
                                @if($quincenas->quincena_tipo=="Cheque")
                                   {{$cheque->cheque_beneficiario}}
                                @endif
                                @if($quincenas->quincena_tipo=="Transferencia")
                                   {{$transferencia->transferencia_beneficiario}}
                                @endif
                    </label>   
                   
                    </div>
                </div>             
                 
            </div>    
    </div>
</form>
@endsection

