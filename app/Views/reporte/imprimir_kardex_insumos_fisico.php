<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir kardex insumos fisico</title>
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
                    KARDEX INSUMOS FISICOS MES DE <?=$mes_texto.' DE '.date('Y');?> 
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
                <td class="negrita" style="background:#ccc">#</td>
                <td class="negrita" style="background:#ccc">BODEGA</td>
                <td class="negrita" style="background:#ccc">SALDO INICIAL</td>
                <td class="negrita" style="background:#ccc">INGRESO</td>
                <td class="negrita" style="background:#ccc">EGRESO</td>
                <td class="negrita" style="background:#ccc">SALDO</td>
            </tr>
            <?php
              $i=1;
              $total_ingreso=0;
              $total_egreso=0;
              $total_saldo=0;
              $total_saldo_inicial=0;
              foreach ($bodegas as $key => $prod):
                $objBodega = new BodegasModel();
                $bodega = $objBodega->getBodega($prod['id_bodega']);
                $total_saldo_inicial += $salidaBodegas[$prod['id_bodega']]['suma_total_saldo_fisico'];
                $total_ingreso += $salidaBodegas[$prod['id_bodega']]['suma_total_ingresado_fisico'];
                $total_egreso += $salidaBodegas[$prod['id_bodega']]['suma_total_salida_fisico'];
                $total_saldo += $salidaBodegas[$prod['id_bodega']]['suma_total_ingresado_fisico']-$salidaBodegas[$prod['id_bodega']]['suma_total_salida_fisico'];
            ?>
            <tr>
                <td style="text-align:center"><?=$i++?></td>
                <td style="text-align:left"><?=$bodega['nombre_bodega']?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_total_saldo_fisico'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_total_ingresado_fisico'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_total_salida_fisico'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format(($salidaBodegas[$prod['id_bodega']]['suma_total_ingresado_fisico']+$salidaBodegas[$prod['id_bodega']]['suma_total_saldo_fisico'])-$salidaBodegas[$prod['id_bodega']]['suma_total_salida_fisico'], 2, ',', '.')?></td>
            </tr>
            <?php endforeach;?>
            <tr>
              <td colspan="2" style="text-align:right">TOTAL</td>
              <td style="text-align:right"><?=number_format($total_saldo_inicial, 2, ',', '.')?></td>
              <td style="text-align:right"><?=number_format($total_ingreso, 2, ',', '.')?></td>
              <td style="text-align:right"><?=number_format($total_egreso, 2, ',', '.')?></td>
              <td style="text-align:right"><?=number_format($total_saldo_inicial+$total_ingreso-$total_egreso, 2, ',', '.')?></td>
            </tr>
            </table>
    </div>
  
</body>
</html>