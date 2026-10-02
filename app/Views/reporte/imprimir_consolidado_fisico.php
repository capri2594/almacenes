<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidado fisico</title>
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
                    <?=$bodega['nombre_bodega']?>
                  </p>
                </div>
              </td>
            </tr>
        </table>
    </div>    

    <div class="contenido">
        <table width="100%">
          <caption>CONSOLIDADO FISICO AL <?php echo date('d/m/Y');?></caption>
            <tr>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">N°</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">CÓDIGO</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">PARTIDA</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">DESCRIPCIÓN</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">UNIDAD</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">FISICO</td>
            </tr>
            <tr>
                <td class="negrita" style="background:#ccc; text-align:center;">INGRESO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALIDA</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">RECUENTO</td>
            </tr>
            <?php
              $i=1;
              $total=0;
              foreach ($datos as $key => $value):
            ?>
            <tr>
                <td style="text-align: right;"><?=$i++?></td>
                <td style="text-align: right;"><?=$value['codigo']?></td>
                <td style="text-align: right;"><?=$value['id_partida']?></td>
                <td style="text-align: left;"><?=$value['nombre_producto']?></td>
                <td style="text-align: left;"><?=$value['unidad']?></td>
                <td style="text-align: right;"><?=$value['cant_ingreso']?></td>
                <td style="text-align: right;"><?=$value['cant_salida']?></td>
                <td style="text-align: right;"><?=$value['saldo_fisico']?></td>
                <td style="text-align: right;"></td>
            </tr>
            <?php endforeach;?>
            </table>
    </div>
</body>
</html>