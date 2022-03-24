<table>
    <tr>
        <td colspan="32" style="text-align: center">NEOPAGUPA | Sistema Contable</td>
    </tr>
</table>
<table>
    <thead>
        <tr>
            <th></th>
            <th style="font-weight: bold">Anexo 1</th>
            <th></th>
            <th></th>
            <th style="font-weight: bold">Anexo 2</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="font-weight: bold">Anexo 3</th>
            <th style="font-weight: bold">Anexo 4</th>
            <th></th>
            <th style="font-weight: bold">Anexo 5</th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th style="font-weight: bold">Anexo 6</th>
            <th></th>
            <th></th>
            <th style="font-weight: bold">Anexo 7</th>
            <th></th>
            <th style="font-weight: bold">Anexo 8</th>
        </tr>
        <tr>
            <th style="font-weight: bold">DEPENDENCIA</th>
            <th style="font-weight: bold">SECUENCIA</th>
            <th style="font-weight: bold">FECHA ATENCION</th>
            <th style="font-weight: bold">TIPO SECURO</th>
            <th style="font-weight: bold">CEDULA PACIENTE</th>
            <th style="font-weight: bold">NOMBRE PACIENTE</th>
            <th style="font-weight: bold">SEXO</th>
            <th style="font-weight: bold">FECHA NAC PAC</th>
            <th style="font-weight: bold">EDAD PACIENTE</th>
            <th style="font-weight: bold">TIPO EXAMEN</th>
            <th style="font-weight: bold">CODIGO TARIF</th>
            <th style="font-weight: bold">DESCRIPCION PROCE TAR</th>
            <th style="font-weight: bold">DIAG PRI - CIE10</th>
            <th style="font-weight: bold">DIAG 2</th>
            <th style="font-weight: bold">DIAG 3</th>
            <th style="font-weight: bold">CANTIDAD</th>
            <th style="font-weight: bold">PRECIO UNITARIO</th>
            <th style="font-weight: bold">TIEMPO</th>
            <th style="font-weight: bold">PARENTESCO</th>
            <th style="font-weight: bold">CEDULA AFILIADO</th>
            <th style="font-weight: bold">NOMBRE AFILIADO</th>
            <th style="font-weight: bold">TIPO DERIVACION</th>
            <th style="font-weight: bold">SECUENCIA</th>
            <th style="font-weight: bold"></th>
            <th style="font-weight: bold">TIPO ADIG</th>
            <th style="font-weight: bold">DIASG 4</th>
            <th style="font-weight: bold">DIASG 5</th>
            <th style="font-weight: bold">DIASG 6</th>
            <th style="font-weight: bold"></th>
            <th style="font-weight: bold">IVA</th>
            <th style="font-weight: bold">% IVA</th>
            <th style="font-weight: bold">LETRA</th>
            
            
        </tr>
    </thead>
    <tbody>
        @if(isset($datos))
            @foreach($datos['ordenes'] as $orden)
                <?php //dd($orden) ?>

                <?php
                    $nacimiento = new DateTime($orden->paciente->paciente_fecha_nacimiento);
                    $ahora = new DateTime(date("Y-m-d"));
                    $diferencia = $ahora->diff($nacimiento);
                    $edad= $diferencia->format("%y");
                ?>

                <tr>
                    <td>{{ $orden->orden_dependencia }}</td>
                    <td>{{ $orden->orden_secuencia }}</td>
                    <td>{{ $orden->orden_fecha }}</td>
                    <td>{{--$tipo_seguro--}}</td>

                    <td>{{ $orden->paciente->paciente_cedula }}</td>
                    <td>{{ $orden->paciente->paciente_nombres }}</td>
                    <td>{{ $orden->paciente->paciente_sexo }}</td>
                    <td>{{ $orden->paciente->paciente_fecha_nacimiento }}</td>
                    <td>{{ $edad }}</td>

                    <td>{{--$tipo_examen--}}</td>
                    <td>{{--$codigo_tar--}}</td>
                    <td>{{ $orden->orden_observacion }}</td>

                    <?php 
                        $expediente=$orden->expediente;

                        if($expediente){
                            $diagnostico=$expediente->diagnostico;
                    
                            if($diagnostico){
                                $diagDetalle=$diagnostico->detallediagnostico;
                                $c=0;

                                foreach($diagDetalle as $detalle){
                                    $c++;

                                    $detalle->enfermedad;
                                    echo '<td>'.$detalle->enfermedad->enfermedad_codigo.'</td>';
                                    
                                    if($c==3)  break;
                                }
                            }
                        }
                        else{
                            echo "
                                <td></td>
                                <td></td>
                                <td></td>";
                        }
                    ?>
            
            
                    <td>{{--$diag_pri_cie10--}}</td>
                    <td>{{--$diag2--}}</td>
                    <td>{{--$diag3--}}</td>


                    <td>{{--$cantidad--}}</td>
                    <td>{{--$precio_unitario--}}</td>
                    <td>{{--$tiempo--}}</td>

                    <td>{{--$parentezco--}}</td>
                    <td>{{ $orden->orden_cedula_afiliado }}</td>
                    <td>{{ $orden->orden_nombre_afiliado }}</td>

                    <td>{{--$tipo derivacion }}</td>
                    <td>{{--$secuencia }}</td>
                    <td></td>

                    <td>{{--$tipo_adig--}}</td>
                    <td>{{--$diasg4--}}</td>
                    <td>{{--$diasg5--}}</td>
                    <td>{{--$diasg6--}}</td>
                    <td></td>


                    <td>{{--$iva--}}</td>
                    <td>{{--$por_iva--}}</td>
                    <td>{{--$letra--}}</td>
                </tr>

            @endforeach
        @else
            <tr>
                <td>sdasdas</td>
            </tr>
        @endif
    </tbody>
</table>