<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PEDIDO Y ENTREGA DE MATERIALES</title>
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
                      <small>D.C.Q.</small>
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
                    PEDIDO Y ENTREGA DE MATERIALES
                  </p>
                </div>                  
              </td>
            </tr>
        </table>
    </div>    
    <?php
      use App\Models\ProductoModel;
      use App\Models\UnidadesMedidaModel;
      use App\Models\RecursosModel;
      use App\Models\UsuarioAperturaModel;
      use App\Models\AperturasModel;
      use App\Models\SubAperturasModel;
      $estados_orden = estados_orden();
    ?>
    <div class="contenido">
        <table width="100%">
            <tr>
                <td class="negrita" width="23%">ESTADO: </td>
                <td width="23%"><?=$estados_orden[$orden['estado_orden']];?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">FORM.:</td>
                <td width="23%">SABS-1</td>
            </tr>
            <tr>
                <td class="negrita" width="23%">BODEGA:</td>
                <td width="23%"><?=$bodega['nombre_bodega']?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">N° CORRELATIVO: </td>
                <td width="23%"><?=$orden['contador']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">FECHA APROBADO:</td>
                <td width="23%"><?=date('d/m/Y', strtotime($orden['fecha_aprobado']))?></td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">OBJETO:</td>
                <td width="23%"><?=$orden['obj_glosa']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">APERTURA PROGRAMATICA:</td>
                <td width="23%">
                  <?php
                    $objsUserApertura = new UsuarioAperturaModel();
                    $ususario_apertura = $objsUserApertura->getAperturaByUsername($orden['recurso_username']);
                    if(isset($ususario_apertura)){
                      $objApertura = new AperturasModel();
                      $apertura = $objApertura->getApertura($ususario_apertura[0]['id_apertura']);
                      echo $apertura['codigo_apertura'];
                    }else{
                      echo "No se encontró apertura programática para este usuario";
                    }                    
                  ?>
                </td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">ID ORDEN:</td>
                <td width="23%"><?=$orden['id_orden']?></td>
            </tr>
            <tr>
                <td class="negrita" width="23%">SOLICITANTE:</td>
                <td width="23%">
                  <?php
                    $objRecurso = new RecursosModel();
                    $recurso_solicitante = $objRecurso->getRecurso($orden['recurso_username']);
                    echo $recurso_solicitante['nombre'] ?? $orden['recurso_username'];
                  ?>
                </td>
                <td class="negrita" width="8%">&nbsp;</td>
                <td class="negrita" width="23%">CELULAR CONTACTO:</td>
                <td width="23%">
                  <?php
                    $celular_contacto = !empty($orden['celular']) ? $orden['celular'] : ($recurso_solicitante['celular'] ?? 'S/N');
                    echo '<strong>' . esc($celular_contacto) . '</strong>';
                  ?>
                </td>
            </tr>
        </table>
    </div>

<p class="destino">
  DESTINO/PROYECTO: 
  <?php
    if(isset($apertura)){
      echo $apertura['descripcion_apertura'];
    }
    if (!empty($ususario_apertura[0]['id_sub_apertura'])) {
      $objSub = new SubAperturasModel();
      $sub = $objSub->find($ususario_apertura[0]['id_sub_apertura']);
      if ($sub) {
        echo ' - <strong>' . $sub['descripcion_sub_apertura'] . '</strong>';
      }
    }
  ?>
</p>

    <div class="contenido">
        <table width="100%">
          <caption>LISTA DE ITEMS</caption>
            <tr>
                <td class="negrita" style="background:#ccc">ITEM</td>
                <td class="negrita" style="background:#ccc">CÓDIGO</td>
                <td class="negrita" style="background:#ccc">SOLICITADO</td>
                <td class="negrita" style="background:#ccc">RECIBIDO</td>
                <td class="negrita" style="background:#ccc">UNIDAD</td>
                <td class="negrita" style="background:#ccc">DESCRIPCIÓN</td>
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
                <td><?=$prod['codigo']?></td>
                <td class="derecha"><?=$value['cant_requerida']?></td>
                <td class="derecha"><?=$value['cant_aprobada']?></td>
                <td><?=$unidad_medida['nombre_unidad_medida']?></td>
                <td><?=$prod['nombre_producto']?></td>
            </tr>
            <?php endforeach;?>
            <tr>
                <td colspan="8">JUSTIFICACIÓN: <?=$orden['glosa']?></td>
            </tr>
            </table>
    </div>
  
    <p></p>

    <div class="contenido">
      <table width="100%">
      <tr>
          <td width="33%" style="text-align:center; background:#ccc"><strong>FIRMA AREA SOLICITANTE</strong></td>
          <td width="33%" style="text-align:center; background:#ccc"><strong>ALMACENES</strong></td>
          <td width="33%" style="text-align:center; background:#ccc"><strong>AUTORIZACIÓN</strong></td>
        </tr>
        <tr>
          <td width="33%"  height="80" style="text-align:center"><p></p></td>
          <td width="33%"  height="80" style="text-align:center"><p></p></td>
          <td width="33%"  height="80" style="text-align:center"><p></p></td>
        </tr>
      </table>
    </div>
    <div class="contenido">
      <table width="100%">
        <tr>
          <td width="50%" style="text-align:center; background:#ccc"><strong>BODEGA</strong></td>
          <td width="50%" style="text-align:center; background:#ccc"><strong>AREA SOLICITANTE</strong></td>
        </tr>
        <tr>
          <td width="50%" height="80" style="text-align:center"><p></p></td>
          <td width="50%" height="80" style="text-align:center"><p></p></td>
        </tr>

      </table>
    </div>

</body>
</html>