<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KARDEX</title>
    <link rel="stylesheet" href="<?=base_url()?>reportes.css">
</head>
<body>
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
                    <img src="<?php echo base_url()?>assets/static/images/logo_bi.jpg" alt="">
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
                    KARDEX - <?=$producto['nombre_producto']?>
                  </p>
                </div>        
              </td>
            </tr>
        </table>
    </div>    
    <?php
      use App\Models\ProductoModel;
      use App\Models\UnidadesMedidaModel;
      use App\Models\NroAdquisicionModel;
      use App\Models\OrdenModel;
    ?>

<div class="contenido">
        <table width="100%">
          <caption>DATOS DEL ITEM/INSUMO</caption>
            <tr>
              <td><strong>GESTIÓN: </strong><?=date('Y')?></td>
              <td><strong>CÓDIGO: </strong><?=$producto['codigo']?></td>
              <?php
                $objUnidadMedida = new UnidadesMedidaModel();
                $unidad_medida = $objUnidadMedida->getUnidadMedida($producto['id_unidad_medida']);
                echo '<td><strong>UNIDAD DE MEDIDA: </strong>'.$unidad_medida['nombre_unidad_medida'].'</td>';
              ?>
            </tr>
        </table>
</div>

    <div class="contenido">
        <table width="100%">
          <caption>MOVIMIENTOS</caption>
            <tr>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">N°</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">FECHA MOVIMIENTO</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">P.ADQ.</td>
                <td colspan="3" class="negrita" style="background:#ccc; text-align:center;">FISICO</td>
                <td colspan="3" class="negrita" style="background:#ccc; text-align:center;">VALORADO</td>
            </tr>
            <tr>
                <td class="negrita" style="background:#ccc; text-align:center;">INGRESO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALIDA</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">DÉBITO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">CRÉDITO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO</td>
            </tr>
            <?php
              $i=1;
              $total=0;
              $total_ingreso = 0;
              $total_salida = 0;
              $total_saldo = 0;
              $total_credito = 0;
              $total_debito = 0;
              use App\Models\AperturasModel;

              $codigo_apertura='sin datos';
              foreach ($adquisiciones as $key => $value):
                $objApertura = new AperturasModel();
                if(is_null($value['id_apertura'])){
                  $apertura=null;  
                }else{
                  $apertura = $objApertura->getApertura($value['id_apertura']);
                }
                if(isset($apertura)){
                  $codigo_apertura = 'AP: '.$apertura['codigo_apertura'];
                }else{
                  $codigo_apertura = 'INVENTARIO INICIAL';
                }
                //echo $codigo_apertura.'<p>------------------------------------'.$i.'----------------------------------------</p>';
                
            ?>
            <tr>
                <td style="text-align: right;"><?=$i++?></td>
                <?php if($value['tipo_movimiento'] == 1){
                        $objNroAdqProd = new NroAdquisicionModel();
                        $nro_adquisicion = $objNroAdqProd->getNroAdquisicion($value['id_nro_adquisicion']);
                        echo '<td>INGRESO ADQ/ '.$nro_adquisicion['nro_correlativo'].': '.date('d/m/Y', strtotime($nro_adquisicion['fecha_adquisicion'])).
                        '- '.$codigo_apertura.'</td>';
                      }else{
                        $total_salida+=$value['cant_salida'];
                        $objOrden = new OrdenModel();
                        $orden = $objOrden->getOrden($value['id_orden']);
                        echo '<td>SALIDA SABS-1/ '.$orden['contador'].': '.(date('d/m/Y', strtotime($orden['fecha_aprobado']))).' - '.$codigo_apertura.'</td>';
                      }
                      $total_ingreso+=$value['cant_ingreso'];
                      $total_saldo+=$value['saldo_fisico'];
                      $total_credito+=$value['ing_valorado'];
                      $total_debito+=$value['sal_valorado'];
                ?>

                <td style="text-align: right;"><?=number_format($value['precio_adquisicion'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['cant_ingreso'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['cant_salida'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['saldo_fisico'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['ing_valorado'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['sal_valorado'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['saldo_valorado'],2,',','.')?></td>
            </tr>
            <?php endforeach;?>
            <tr>
              <td colspan="3"></td>
              <td style="text-align: right;"><?=number_format($total_ingreso,2,',','.')?></td>
              <td style="text-align: right;"><?=number_format($total_salida,2,',','.')?></td>
              <td style="text-align: right;"><?=number_format($value['saldo_fisico'],2,',','.')?></td>
              <td style="text-align: right;"><?=number_format($total_credito,2,',','.')?></td>
              <td style="text-align: right;"><?=number_format($total_debito,2,',','.')?></td>
              <td style="text-align: right;"><?=number_format($value['saldo_valorado'],2,',','.')?></td>
            </tr>
            </table>
    </div>
  
</body>
</html>