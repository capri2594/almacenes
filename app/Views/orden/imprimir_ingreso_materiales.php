<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso de materiales</title>
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
                      <img src="<?=base_url()?>assets/static/images/qr_ejemplo.png" alt="qr"><br>
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
                    INGRESO DE MATERIALES
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
      $tipo_documento = tiposDocumentos();
    ?>
    <div class="contenido">
        <table width="100%">
          <tr>
                <td class="negrita" width="23%">N° INGRESO</td>
                <td width="23%"><?=$nro_adquisicion['nro_correlativo']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">FECHA</td>
                <td width="23%"><?=$nro_adquisicion['fecha_adquisicion']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">BODEGA</td>
                <td width="23%"><?=$bodega['nombre_bodega']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">RESPONSABLE</td>
                <td width="23%"><?=$bodega['recurso_username']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%"><?=$tipo_documento[$nro_adquisicion['tipo_documento']]?></td>
                <td width="23%"><?=$nro_adquisicion['nro_documento']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">PROVEEDOR</td>
                <td width="23%"><?=$proveedor['razon_social']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">APERTURA PROG.</td>
                <td width="23%"><?=$nro_adquisicion['id_apertura_general']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">PEDIDO</td>
                <td width="23%"><?=$nro_adquisicion['pedido']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">ORDEN DE COMPRA</td>
                <td width="23%"><?=$nro_adquisicion['orden_compra']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">HOJA DE RUTA</td>
                <td width="23%"><?=$nro_adquisicion['hoja_ruta']?></td>
            </tr>
        </table>
    </div>

<p class="destino">
  ORIGEN: <?=$origen['descripcion']?>
</p>

<p class="destino">
  DESTINO: <?=$destino['descripcion']?>
</p>

    <div class="contenido">
        <table width="100%">
          <caption>LISTA DE ITEMS</caption>
            <tr>
                <td class="negrita">ITEM</td>
                <td class="negrita">CÓDIGO</td>
                <td class="negrita">PARTIDA</td>
                <td class="negrita">CANT.</td>
                <td class="negrita">UNIDAD</td>
                <td class="negrita">DESCRIPCIÓN</td>
                <td class="negrita">P. UNID.</td>
                <td class="negrita">SUB TOTAL</td>
            </tr>
            <?php
              $i=1;
              $total=0;
              foreach ($items as $key => $value):
                $miProducto = new ProductoModel();
                $prod = $miProducto->getProducto($value['id_producto']);

                $miUnidadMedida = new UnidadesMedidaModel();
                $unidad_medida = $miUnidadMedida->getUnidadMedida($prod['id_unidad_medida']);
            ?>
            <tr>
                <td><?=$i++?></td>                
                <td><?=$prod['id_producto']?></td>                
                <td><?=$value['partida_presupuestaria']?></td>                
                <td class="derecha"><?=number_format($value['cant_ingreso'],2,',','.')?></td>                
                <td><?=$unidad_medida['nombre_unidad_medida']?></td>                
                <td><?=$prod['nombre_producto']?></td>                
                <td class="derecha"><?=number_format($value['precio_adquisicion'],2,',','.')?></td>                
                <td class="derecha"><?=number_format($value['ingreso_valorado'],2,',','.')?></td>
                <?php
                  $total+=$value['ingreso_valorado'];
                ?>
            </tr>
            <?php endforeach;?>
            <tr>
                <td class="negrita" colspan="7">
                <?php
                  $numerosLetras = new NumeroALetras();
                  $con_letra= $numerosLetras->convertir(floor($total));

                  $aux = number_format($total,2);
                  $decimal = substr( $aux, strpos($aux, ".")+1 );
                  echo 'SON: '.$con_letra.$decimal.'/100 BOLIVIANO(S)';
                  ?>

                </td>
                <td class="derecha negrita"><?=number_format($total,2,',','.')?></td>
            </tr>
            <tr>
                <td colspan="8">OBSERVACIONES: <?=$nro_adquisicion['observaciones']?></td>
            </tr>
            </table>
    </div>
</body>
</html>