<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso/Salida de materiales</title>
    <link rel="stylesheet" href="<?=base_url()?>reportes.css">
<style>
  .anulado{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 500px;
    background-color: rgba(255, 255, 255, 0);
    opacity: 0.5;
    transform: rotate(-45deg); 
    color: #000;
    text-align: center;
    font-size: 48px;
    font-weight: bold;
    line-height: 500px;
    z-index: 1000;
  }
</style>
</head>
<body>
    <!-- <?php if($ingreso_salida['estado_ingreso_salida']==2):?> -->
      <div class="anulado">ANULADO</div>
    <!-- <?php endif;?> -->

    <div class="encabezado">
        <table>
            <tr>
                <td width="50%">
                  <div class="container">
                    <div class="image-container">
                      <img src="<?=base_url()?>assets/static/images/escudo_oruro.jpg" alt="logo oruro">
                    </div>
                    <div class="text-container">
                        <p>
                          GOBIERNO AUTÓNOMO DEPARTAMENTAL DE ORURO<br>
                          SECRETARÍA DEPARTAMENTAL DE ADMINISTRACIÓN Y FINANZAS PÚBLICAS<br>
                          SECCIÓN ALMACENES<br>
                        </p>
                    </div>
                  </div>  
                </td>
                <td width="50%">
                  <div class="container-qr">
                    <div class="image-qr-container">
                      <img src="<?=base_url()?>assets/static/images/qr_ejemplo.png" alt="qr"><br>
                      <small>D.C.Q.</small>
                    </div>
                  </div>  
                </td>
            </tr>
        </table>
    </div>

    <div class="encabezado">
        <table>
            <tr>
              <td width="100%">
                <div class="container-title">
                  <p>
                    INGRESO/SALIDA DE MATERIALES
                  </p>
                </div>                  
              </td>
            </tr>
        </table>
    </div>    
    <?php
      use App\Models\ProductoModel;
      use App\Models\UnidadesMedidaModel;
      use App\Libraries\Numeroaletras;
      $docConstancia = documentoConstancia();
      $tipoDoc = tipoDocumento()
    ?>
    <div class="contenido">
        <table width="100%">
          <tr>
                <td class="negrita" width="23%">N° INGRESO CORRELATIVO</td>
                <td width="23%"><?=$ingreso_salida['nro_correlativo']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">FECHA</td>
                <td width="23%"><?=datetime_to_es($ingreso_salida['fecha_ingreso_salida'])?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">BODEGA</td>
                <td width="23%"><?=$bodega['nombre_bodega']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">RESPONSABLE</td>
                <td width="23%"><?=$bodega['recurso_username']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">DOC. - <?=$docConstancia[$ingreso_salida['tipo_documento']]?></td>
                <td width="23%"><?=$ingreso_salida['nro_doc_constancia']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">PROVEEDOR</td>
                <td width="23%"><?=$proveedor['razon_social']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">APERTURA PROG.</td>
                <td width="23%">
                  <?php
                      use App\Models\AperturasModel;
                      $ojbApertura = new AperturasModel();
                      $apertura_programatica = $ojbApertura->getApertura($ingreso_salida['id_apertura']);
                      echo $apertura_programatica['codigo_apertura'];
                  ?>
                </td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">INGRESO POR: </td>
                <td width="23%">
                <?php
                  $tipo_origen_destino = tiposOrigenDestino();
                  echo $tipo_origen_destino[$ingreso_salida['tipo_adquisicion']];
                ?>                  
                </td>
            </tr>
            <tr>
                <td class="negrita" width="23%"><?=$tipoDoc[$ingreso_salida['tipo_documento']]?></td>
                <td width="23%"><?=$ingreso_salida['nro_tipo_documento']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">HOJA DE RUTA</td>
                <td width="23%"><?=$ingreso_salida['hoja_ruta']?></td>
            </tr>
            <tr>
              <td colspan="5"><strong>DESTINO: </strong> <?=$apertura_programatica['descripcion_apertura'];?></td>
            </tr>
        </table>
    </div>

    <div class="contenido">
        <table width="100%">
          <caption>LISTA DE ITEMS</caption>
            <tr>
                <td style="background:#ccc; font-weight:bold;">ITEM</td>
                <td style="background:#ccc; font-weight:bold;">PARTIDA</td>
                <td style="background:#ccc; font-weight:bold;">UNIDAD</td>
                <td style="background:#ccc; font-weight:bold;">DESCRIPCIÓN</td>
                <td style="background:#ccc; font-weight:bold;">CANT.</td>
                <td style="background:#ccc; font-weight:bold;">P. UNID.</td>
                <td style="background:#ccc; font-weight:bold;">SUB TOTAL</td>
            </tr>
            <?php
              $i=1;
              $total=0;
              foreach ($items as $key => $value):
                $miUnidadMedida = new UnidadesMedidaModel();
                $unidad_medida = $miUnidadMedida->getUnidadMedida($value['id_unidad_medida']);
                $total += ($value['cantidad'] * $value['precio_unitario']);
            ?>
            <tr>
                <td><?=$i++?></td>
                <td><?=$value['id_partida']?></td>
                <td><?=$unidad_medida['nombre_unidad_medida']?></td>
                <td><?=$value['nombre_producto']?></td>
                <td class="derecha"><?=number_format($value['cantidad'],2,',','.')?></td>
                <td class="derecha"><?=number_format($value['precio_unitario'],2,',','.')?></td>
                <td class="derecha"><?=number_format(($value['cantidad'] * $value['precio_unitario']),2,',','.')?></td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td class="negrita" colspan="6">
                <?php
                  $numerosLetras = new NumeroALetras();
                  $con_letra= $numerosLetras->convertir($total);

                  $aux = number_format($total,2);
                  $decimal = substr( $aux, strpos($aux, ".")+1 );
                  echo 'SON: '.$con_letra.$decimal.'/100 BOLIVIANO(S)';
                  ?>

                </td>
                <td class="derecha negrita"><?=number_format($total,2,',','.')?></td>
            </tr>
            <tr>
                <td colspan="8">OBSERVACIONES: <?=$ingreso_salida['observaciones']?></td>
            </tr>
            </table>
    </div>
    
    
    <p></p>

    <div class="contenido">
      <table width="100%">
      <tr>
          <td width="33%" style="text-align:center; background:#ccc"><strong>UNIDAD SOLICITANTE</strong></td>
          <td width="33%" style="text-align:center; background:#ccc"><strong>INMEDIATO SUPERIOR</strong></td>
          <td width="33%" style="text-align:center; background:#ccc"><strong>RESPONSABLE BODEGA</strong></td>
        </tr>
        <tr>
          <td width="33%"  height="80" style="text-align:center"><p></p></td>
          <td width="33%"  height="80" style="text-align:center"><p></p></td>
          <td width="33%"  height="80" style="text-align:center"><p></p></td>
        </tr>
      </table>
    </div>
    <div class="contenido">
      <table width="100%">
        <tr>
          <td width="50%" style="text-align:center; background:#ccc"><strong>ENCARGADO ALMACENES</strong></td>
          <td width="50%" style="text-align:center; background:#ccc"><strong>ENCARGADO DE AREA</strong></td>
        </tr>
        <tr>
          <td width="50%" height="80" style="text-align:center"><p></p></td>
          <td width="50%" height="80" style="text-align:center"><p></p></td>
        </tr>

      </table>
    </div>

</body>
</html>