<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consolidado</title>
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
          <caption>
            CONSOLIDADO <br>
            <?php
              $mes_txt = '';
              switch ($mes) {
                case '0':$mes_txt='ENERO';break;                
                case '1':$mes_txt='FEBRERO';break;                
                case '2':$mes_txt='MARZO';break;                
                case '3':$mes_txt='ABRIL';break;                
                case '4':$mes_txt='MAYO';break;                
                case '5':$mes_txt='JUNIO';break;                
                case '6':$mes_txt='JULIO';break;                
                case '7':$mes_txt='AGOSTO';break;                
                case '8':$mes_txt='SEPTIEMBRE';break;                
                case '9':$mes_txt='OCTUBRE';break;                
                case '10':$mes_txt='NOVIEMBRE';break;                
                case '11':$mes_txt='DICIEMBRE';break;                
              }
             echo 'DEL PERIODO '.$mes_txt;
             ?>
          </caption>
            <tr>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">N°</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">CÓDIGO</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">DESCRIPCIÓN</td>
                <td rowspan="2" class="negrita" style="background:#ccc; text-align:center;">UNIDAD</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">FISICO</td>
                <td colspan="4" class="negrita" style="background:#ccc; text-align:center;">VALORADO</td>
            </tr>
            <tr>
                <td class="negrita" style="background:#ccc; text-align:center;">INICIO FISICO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">INGRESO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALIDA</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">INICIO VALORADO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">DÉBITO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">CRÉDITO</td>
                <td class="negrita" style="background:#ccc; text-align:center;">SALDO</td>
            </tr>
            <?php
              $i=1;
              $total_inicio=0;
              $total_inicio_valorado=0;
              $total_saldo_valorado=0;
              $total_ingresos=0;
              $total_salidas=0;
              $total_saldo_fisico=0;
              $total_ing_valorado=0;
              $total_sal_valorado=0;

              foreach ($datos as $key => $value):
                $total_inicio+=$value['cant_inicial'];
                $total_inicio_valorado+=$value['cant_inicial_valorado'];
                $total_ingresos+=$value['cant_ingreso'];
                $total_salidas+=$value['cant_salida'];
                $saldo_fisico = ($value['cant_inicial'] + $value['cant_ingreso'] - $value['cant_salida']);
                $total_saldo_fisico+=$saldo_fisico;
                $saldo_valorado = ($value['cant_inicial_valorado'] + $value['ing_valorado'] - $value['sal_valorado']);
                $total_saldo_valorado+=$saldo_valorado;
                
                $total_ing_valorado+= $value['ing_valorado'];
                $total_sal_valorado+=$value['sal_valorado'];
            ?>
            <tr>
                <td style="text-align: right;"><?=$i++?></td>
                <td style="text-align: right;"><?=$value['codigo']?></td>
                <td style="text-align: left;"><?=$value['nombre_producto']?></td>
                <td style="text-align: left;"><?=$value['unidad']?></td>
                <td style="text-align: right;"><?=number_format($value['cant_inicial'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['cant_ingreso'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($value['cant_salida'],2,',','.')?></td>
                <td style="text-align: right;"><?=number_format($saldo_fisico,2,',','.')?></td><!-- saldo -->
                <td style="text-align: right;"><?=number_format($value['cant_inicial_valorado'],2,',','.')?></td><!-- inicio valorado -->
                <td style="text-align: right;"><?=number_format($value['ing_valorado'],2,',','.')?></td><!-- ingreso credito -->
                <td style="text-align: right;"><?=number_format($value['sal_valorado'],2,',','.')?></td><!-- salida debito -->
                <td style="text-align: right;"><?=number_format($saldo_valorado,2,',','.')?></td><!-- saldo valorado -->
            </tr>
            <?php
          endforeach;?>
              <tr>
                <td style="background:#ccc; text-align: center;" colspan="4"><strong>TOTAL</strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_inicio,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_ingresos,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_salidas,2,',','.')?></strong></td><!-- repite -->
                <td style="background:#ccc; text-align: right;"><strong><?=number_format(($total_inicio+$total_ingresos-$total_salidas),2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_inicio_valorado,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_ing_valorado,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format($total_sal_valorado,2,',','.')?></strong></td>
                <td style="background:#ccc; text-align: right;"><strong><?=number_format(($total_inicio_valorado+$total_ing_valorado-$total_sal_valorado),2,',','.')?></strong></td>
              </tr>
            </table>
    </div>
</body>
</html>