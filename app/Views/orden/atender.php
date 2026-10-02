<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
 $(document).ready(function() {
    $('#producto_elegido').select2();
});

</script>

<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">SABS-1</h4>
    <small>Código id:<?=$id_orden?></small>
    <div>
      <strong>Usuario solicitante: </strong> <?=$orden['recurso_username']?><br>
      <strong>Apertura: </strong>
      <?php
        use App\Models\AperturasModel;
        $objApertura = new AperturasModel();
        $apertura = $objApertura->find($usuario_apertura[0]['id_apertura']);
        echo $apertura['descripcion_apertura'];
      ?><br>
      
      <strong>Código apertura: </strong>
      <?php
        echo $apertura['codigo_apertura'];
      ?><br>
      
      <strong>Sub apertura: </strong>
      <?php
        use App\Models\SubAperturasModel;
        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->find($usuario_apertura[0]['id_sub_apertura']);
        if(is_null($sub_apertura)){
          echo 'SIN SUB APERTURA';
        }else{
          echo $sub_apertura['descripcion_sub_apertura'];
        }
      ?><br>
      
    </div>
    <h4 class="c-grey-900 mt-3">ITEMS</h4>
    <form id="formulario" action="#" method="post" autocomplete="off">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">UNIDAD MEDIDA</th>
                <th scope="col">CANT. EXISTENTE</th>
                <th scope="col">SOLICITADO</th>
              </tr>
            </thead>
            <tbody>
                <?php
                use App\Models\UnidadesMedidaModel;
                use App\Models\AdquisicionProductoTieneAppModel;
                //$estados = estados_acceso();
                $i=1;
                $total = 1;

                foreach ($items_orden as $key => $value):
                  
                  $objAdqProd = new AdquisicionProductoTieneAppModel();
                  $cant_existente = $objAdqProd->getCantExistente($orden['id_bodega'], $usuario_apertura[0]['id_apertura'], $usuario_apertura[0]['id_sub_apertura'], $value['id_producto']);
                  if ((empty($cant_existente) || $cant_existente['cantidad'] == 0) && !empty($usuario_apertura) && $usuario_apertura[0]['id_sub_apertura'] != 0) {
                      $cant_existente = $objAdqProd->getCantExistente($orden['id_bodega'], $usuario_apertura[0]['id_apertura'], 0, $value['id_producto']);
                  }
                  $prod = $miProducto->find($value['id_producto']);

                  $objUnidadMedida = new UnidadesMedidaModel();
                  $unidad_medida = $objUnidadMedida->find($prod['id_unidad_medida']);
                ?>
                  <tr>
                    <td><?=$i;?></td>
                    <td><?=$prod['nombre_producto'];?></td>
                    <td width="10%"><?php echo $unidad_medida['nombre_unidad_medida'];?></td>
                    <td width="10%" class="text-end"><?php echo $cant_existente['cantidad'];?></td>
                    <td width="10%" class="text-end">
                      <input id="cant_aprobada_<?=$i?>" name="cant_aprobada_<?=$i?>" type="number" class="form-control text-end" value="<?=$value['cant_requerida'];?>" max="<?php echo $cant_existente['cantidad'];?>" min="0" required>
                      <input id="id_producto_<?=$i?>" name="id_producto_<?=$i?>" type="hidden" class="form-control text-end" value="<?=$value['id_producto'];?>">
                    </td>
                  </tr>
                <?php
                  $i++;
                  endforeach;
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- fin row-->
      <div class="row mt-3">
        <div class="col-md-12">
          <button id="btn_aprobar" type="submit" class="btn btn-success text-white">Aprobar</button>
        </div>
      </div>
    </form>


  </div>

</main>
<script>
$("#formulario" ).on( "submit", function( event ) {
  if(confirm("¿Esta seguro de aprobar la solicitud?")){
      $("#btn_aprobar").prop('disabled', true);
      $("#btn_aprobar").text("Aprobando...");
      let url = '<?=base_url()?>orden/ejecutar_solicitud/<?=$id_orden?>';
      $('#formulario').attr('action', url);
      return ;      
    }    
  event.preventDefault();
});
  function aprobar(id_orden){
  }
    
</script>

<?= $this->endSection();?>
