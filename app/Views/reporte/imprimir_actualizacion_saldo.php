<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ACTUALIZACION DE SALDOS DE MATERIALES</title>
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
                    ACTUALIZACION DE SALDOS DE MATERIALES EN ALMACENES POR ITEM<br>
                  </p>
                </div>
              </td>
            </tr>
        </table>
    </div>    

    <div class="contenido">
        <table width="100%">
          <caption>
            <?php echo $bodegas[0]['nombre_bodega'].' - ';//var_dump($bodegas);?>
            AL 31 DE DICIEMBRE DE 2025
          </caption>
            <tr>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">N°</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">DESCRIPCIÓN ITEM</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">UNIDAD</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">FISICO</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">VALORADO</td>
                <td colspan="3" class="negrita" style="background:#ccc; text-align:center;">ACTUALIZACIÓN DE DATOS</td>
            </tr>
            <tr>
                <td class="negrita" style="background:#ccc; text-align:center;">INV. INICIAL</td>
                <td class="negrita" style="background:#ccc; text-align:center;">INGRESOS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALIDAS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDOS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO INICIAL</td>
                <td class="negrita" style="background:#ccc; text-align:center;">TOTAL DÉBITOS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">TOTAL CRÉDITOS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDOS FINALES</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO ACTUAL</td>
                <td class="negrita" style="background:#ccc; text-align:center;">DIFERENCIA</td>
                <td class="negrita" style="background:#ccc; text-align:center;">COSTO</td>
            </tr>
            <?php
              $contador=1;
              $total_inicial_fisico = 0;
              $total_ingreso_fisico = 0;
              $total_salida_fisico = 0;
              $total_saldo_fisico = 0;
              $total_inicial_saldo_valorado = 0;
              $total_credito = 0;
              $total_debito = 0;
              $total_saldo_valorado = 0;
              $total_costo = 0;

              foreach ($dataSalida as $key => $value):
                $total_inicial_fisico += $value['saldo_inicial_fisico'];
                $total_ingreso_fisico += $value['total_cant_ingreso'];
                $total_salida_fisico += $value['total_cant_salida'];
                $total_saldo_fisico += $value['total_saldo_fisico'];
                $total_inicial_saldo_valorado += $value['saldo_inicial_valorado'];
                $total_credito += $value['credito'];
                $total_debito += $value['debito'];
                $total_saldo_valorado += $value['saldo_valorado'];
                $total_costo += $value['precio_promedio'];
            ?>
            <tr>
              <td><?php echo $contador++;?></td>
              <td><?php echo $value['nombre_producto'];?></td>
              <td><?php echo $value['nombre_unidad_medida'];?></td>
              <td style="text-align:right;"><?php echo number_format($value['saldo_inicial_fisico'],2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format($value['total_cant_ingreso'],2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format($value['total_cant_salida'],2,',','.');?></td>
              <td style="text-align:right; font-weight: bold;"><?php echo number_format($value['total_saldo_fisico'],2,',','.');?></td>
              

              <td style="text-align:right;"><?php echo number_format($value['saldo_inicial_valorado'],2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format($value['credito'],2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format($value['debito'],2,',','.');?></td>
              <td style="text-align:right; font-weight: bold;"><?php echo number_format($value['saldo_valorado'],2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format($value['saldo_valorado'],2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format(0,2,',','.');?></td>
              <td style="text-align:right;"><?php echo number_format($value['precio_promedio'],2,',','.');?></td>
            </tr>
            <?php
              endforeach;
            ?>
            <tr>
              <td colspan="3" class="negrita" style="background:#ccc; text-align:center;">TOTAL</td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_inicial_fisico,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_ingreso_fisico,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_salida_fisico,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_saldo_fisico,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_inicial_saldo_valorado,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_credito,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_debito,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_saldo_valorado,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format($total_saldo_valorado,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"><?php echo number_format(0,2,',','.');?></td>
              <td style="background:#ccc; text-align:right; font-weight: bold;"></td>
            </tr>
            </table>
    </div>
</body>
</html>