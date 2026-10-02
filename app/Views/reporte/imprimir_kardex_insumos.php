<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir kardex insumos</title>
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
                  <?php
                  $mes_texto='';
                    switch ($mes) {
                      case '1': $mes_texto='ENERO'; break;
                      case '2': $mes_texto='FEBRERO'; break;
                      case '3': $mes_texto='MARZO'; break;
                      case '4': $mes_texto='ABRIL'; break;
                      case '5': $mes_texto='MAYO'; break;
                      case '6': $mes_texto='JUNIO'; break;
                      case '7': $mes_texto='JULIO'; break;
                      case '8': $mes_texto='AGOSTO'; break;
                      case '9': $mes_texto='SEPTIEMBRE'; break;
                      case '10': $mes_texto='OCTUBRE'; break;
                      case '11': $mes_texto='NOVIEMBRE'; break;
                      case '12': $mes_texto='DICIEMBRE'; break;
                    }
                  ?>
                  <p>
                    KARDEX INSUMOS MES DE <?=$mes_texto.' DE 2026';?> 
                  </p>
                </div>        
              </td>
            </tr>
        </table>
    </div>    
    <?php
      use App\Models\BodegasModel;
    ?>

    <div class="contenido">
        <table width="100%">
          <caption>LISTA DE ITEMS</caption>
            <tr>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">BODEGA</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">FISICO</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">VALORADO</td>
            </tr>
            <tr>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO FISICO INICIAL</td>
                <td class="negrita" style="background:#ccc; text-align:center;">INGRESO FISICO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALIDA FISICO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO FISICO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO VALORADO INICIAL</td>
                <td class="negrita" style="background:#ccc; text-align:center;">INGRESO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">EGRESO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO VALORADO</td>
            </tr>

            <?php
              $total_ingreso=0;
              $total_egreso=0;
              $total_saldo=0;
              $total_saldo_inicial=0;
              $total_fisico_saldo_inicial=0;
              $total_fisico_ingreso=0;
              $total_fisico_egreso=0;
              $total_fisico_saldo=0;
              foreach ($bodegas as $key => $prod):
                $objBodega = new BodegasModel();
                $bodega = $objBodega->getBodega($prod['id_bodega']);
                $total_fisico_saldo_inicial += $salidaBodegas[$prod['id_bodega']]['saldo_fisico_inicial'];;
                $total_fisico_ingreso += $salidaBodegas[$prod['id_bodega']]['ingreso_fisico'];
                $total_fisico_egreso += $salidaBodegas[$prod['id_bodega']]['salida_fisico'];
                $total_fisico_saldo += $salidaBodegas[$prod['id_bodega']]['saldo_fisico'];

                $total_saldo_inicial += $salidaBodegas[$prod['id_bodega']]['saldo_valorado_inicial'];
                $total_ingreso += $salidaBodegas[$prod['id_bodega']]['ingreso'];
                $total_egreso += $salidaBodegas[$prod['id_bodega']]['egreso'];
                $total_saldo += $salidaBodegas[$prod['id_bodega']]['saldo_valorado'];

                
            ?>
            <tr>
                <td style="text-align:left"><?=$bodega['nombre_bodega']?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['saldo_fisico_inicial'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['ingreso_fisico'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['salida_fisico'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['saldo_fisico'], 2, ',', '.')?></td>
                
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['saldo_valorado_inicial'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['ingreso'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['egreso'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['saldo_valorado'], 2, ',', '.')?></td>
            </tr>
            <?php endforeach;?>
            <tr>
              <td class="negrita" style="background:#ccc; text-align:right;">TOTAL</td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_fisico_saldo_inicial, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_fisico_ingreso, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_fisico_egreso, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_fisico_saldo_inicial+$total_fisico_ingreso-$total_fisico_egreso, 2, ',', '.')?></td>

              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_saldo_inicial, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_ingreso, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_egreso, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_saldo_inicial+$total_ingreso-$total_egreso, 2, ',', '.')?></td>
            </tr>
            </table>
    </div>
  
</body>
</html>