<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventario inicial</title>
    <link rel="stylesheet" href="<?=base_url()?>reportes.css">
</head>
<body>
    <?php
      if($bodega_id==0 || ($id_bodega==$bodega_id)){
       ; 
      }else{
        echo '<script>alert("No tienes permiso para acceder a esta bodega");</script>';
        return ;
      }
    ?>
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
          <caption>INVENTARIO INICIAL</caption>
            <tr>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">N°</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">CÓDIGO</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">DESCRIPCIÓN</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">UNIDAD</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">INICIO</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">PRECIO</td>
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
              $total_saldo_valorado=0;
              $total_ingresos=0;
              $total_salidas=0;
              $total_saldo_fisico=0;
              $total_ing_valorado=0;
              $total_sal_valorado=0;

              foreach ($datos as $key => $value):
                $total_saldo_valorado+=$value['saldo_valorado'];
                $total_ingresos+=$value['cant_ingreso'];
                $total_salidas+=$value['cant_salida'];
                $total_saldo_fisico+=$value['saldo_fisico'];
                $total_ing_valorado+=$value['ing_valorado'];
                $total_sal_valorado+=$value['sal_valorado'];
            ?>
            <tr>
                <td style="text-align: right;"><?=$i++?></td>
                <td style="text-align: right;"><?=$value['codigo']?></td>
                <td style="text-align: left;"><?=$value['nombre_producto']?></td>
                <td style="text-align: left;"><?=$value['unidad']?></td>
                <td style="text-align: right;"><?=number_format($value['cant_inicial'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['precio_adquisicion'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['cant_ingreso'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['cant_salida'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['saldo_fisico'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['ing_valorado'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['sal_valorado'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['saldo_valorado'],2,',','.')?></td>
            </tr>
            <?php
          endforeach;?>
              <tr>
                <td style="background:#ccc; text-align: center;" colspan="4"><strong>TOTAL</strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_ingresos,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_ingresos,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_salidas,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_saldo_fisico,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_ing_valorado,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_sal_valorado,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_saldo_valorado,2,',','.')?></strong></td>
              </tr>
            </table>
    </div>
</body>
</html>