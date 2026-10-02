<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir kardex final</title>
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
                    KARDEX VALORADO FINAL
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
          
            <tr>
                <td class="negrita" style="background:#ccc; text-align:center;">N°</td>
                <td class="negrita" style="background:#ccc; text-align:center;">DETALLE</td>
                <!-- <td class="negrita" style="background:#ccc; text-align:center;">SALDO SIGEP 2024</td> -->
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO INICIAL 2025</td>
                <td class="negrita" style="background:#ccc; text-align:center;">DIFERENCIA APERTURA</td>
                <td class="negrita" style="background:#ccc; text-align:center;">TOTAL DEBITOS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">TOTAL CRÉDITOS</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO FINAL</td>
                <td class="negrita" style="background:#ccc; text-align:center;">VALOR ACTUALIZADO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">INCREMENTO POR ACTUALIZACIÓN</td>
                <td class="negrita" style="background:#ccc; text-align:center;">FALTANTES</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SOBRANTES</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO FINAL 2025</td>
            </tr>

            <?php
              $total_saldo_inicial=0;
              $total_debitos=0;
              $total_creditos=0;
              $total_saldo_final=0;
              $total_valor_actualizado=0;
              $total_saldo_final_2025=0;

              $total_ingreso=0;
              $total_egreso=0;
              $total_saldo=0;
              
              $total_fisico_saldo_inicial=0;
              $total_fisico_ingreso=0;
              $total_fisico_egreso=0;
              $total_fisico_saldo=0;

              $i=1;
              foreach ($salidaBodegas as $key => $fila):
                $objBodega = new BodegasModel();
                $bodega = $objBodega->getBodega($fila['id_bodega']);
                $total_saldo_inicial += $fila['iniciales_valorado'];
                $total_debitos += $fila['ing_valorado'];
                $total_creditos += $fila['sal_valorado'];
                $total_saldo_final += $fila['saldo_final'];
                $total_valor_actualizado += $fila['saldo_final'];
                $total_saldo_final_2025 += $fila['saldo_final'];
            ?>
            <tr>
                <td style="text-align:right"><?=$i++?></td>
                <td style="text-align:left"><?=$bodega['nombre_bodega']?></td>
                <!-- <td style="text-align:right"><?=number_format($fila['iniciales_valorado'], 2, ',', '.')?></td> -->
                <td style="text-align:right"><?=number_format($fila['iniciales_valorado'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format(0, 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($fila['ing_valorado'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($fila['sal_valorado'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($fila['saldo_final'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($fila['saldo_final'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format(0, 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format(0, 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format(0, 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($fila['saldo_final'], 2, ',', '.')?></td>
            </tr>
            <?php 
              endforeach;
            ?>
            <tr>
              <td colspan="2" class="negrita" style="background:#ccc; text-align:center;">TOTAL</td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_saldo_inicial, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format(0, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_debitos, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_creditos, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_saldo_final, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_valor_actualizado, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format(0, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format(0, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format(0, 2, ',', '.')?></td>
              <td class="negrita" style="background:#ccc; text-align:right;"><?=number_format($total_saldo_final_2025, 2, ',', '.')?></td>

            </tr>
            </table>
    </div>
  
</body>
</html>