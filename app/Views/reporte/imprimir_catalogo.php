<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de productos</title>
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
                    CATALOGO - <?=$bodega['nombre_bodega']?>
                  </p>
                </div>        
              </td>
            </tr>
        </table>
    </div>    
    <?php
      use App\Models\UnidadesMedidaModel;
      use App\Models\PartidaModel;
    ?>

    <div class="contenido">
        <table width="100%">
          <caption>LISTA DE ITEMS</caption>
            <tr>
                <td class="negrita" style="background:#ccc">ITEM</td>
                <td class="negrita" style="background:#ccc">CÓDIGO</td>
                <td class="negrita" style="background:#ccc">PARTIDA</td>
                <td class="negrita" style="background:#ccc">UNIDAD</td>
                <td class="negrita" style="background:#ccc">DESCRIPCIÓN</td>
            </tr>
            <?php
              $i=1;
              $total=0;
              foreach ($productos as $key => $prod):

                $miUnidadMedida = new UnidadesMedidaModel();
                $unidad_medida = $miUnidadMedida->getUnidadMedida($prod['id_unidad_medida']);
                $miPartida = new PartidaModel();
                $partida = $miPartida->getPartida($prod['id_partida']);
            ?>
            <tr>
                <td><?=$i++?></td>
                <td><?=$prod['codigo']?></td>
                <td><?=$partida['id_partida']?></td>
                <td><?=$unidad_medida['nombre_unidad_medida']?></td>
                <td><?=$prod['nombre_producto']?></td>
            </tr>
            <?php endforeach;?>
            </table>
    </div>
  
</body>
</html>