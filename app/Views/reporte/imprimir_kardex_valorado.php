<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimir kardex valorado</title>
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
                    KARDEX VALORADO
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
                <td class="negrita" style="background:#ccc">SALDO SIGEP</td>
                <td class="negrita" style="background:#ccc">SALDO INICIAL</td>
                <td class="negrita" style="background:#ccc">DIFERENCIA APERTURA</td>
                <td class="negrita" style="background:#ccc">TOTAL DEBITO</td>
                <td class="negrita" style="background:#ccc">TOTAL CREDITO</td>
                <td class="negrita" style="background:#ccc">SALDO FINAL</td>
                <td class="negrita" style="background:#ccc">VALOR ACTUALIZADO</td>
            </tr>
            <?php
              $i=1;
              $total_saldo_inicial=0;
              foreach ($bodegas as $key => $prod):
                $objBodega = new BodegasModel();
                $bodega = $objBodega->getBodega($prod['id_bodega']);
                $total_saldo_inicial += $salidaBodegas[$prod['id_bodega']]['suma_inventario_inicial'];
            ?>
            <tr>
                <td style="text-align:center"><?=$i++?></td>
                <td style="text-align:left"><?=$bodega['nombre_bodega']?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_inventario_inicial'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_inventario_inicial'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_ing_valorado'], 2, ',', '.')?></td>
                <td style="text-align:right"><?=number_format($salidaBodegas[$prod['id_bodega']]['suma_sal_valorado'], 2, ',', '.')?></td>
                <td></td>  
                <td></td>  
                <td></td>
            </tr>
            <?php endforeach;?>
            <tr>
              <td colspan="2" style="text-align:right">TOTAL</td>
              <td style="text-align:right"><?=number_format($total_saldo_inicial, 2, ',', '.')?></td>
            </tr>
            </table>
    </div>
  
</body>
</html>