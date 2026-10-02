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
    
    <div>
      <strong>Apertura: </strong>
      <?php
        echo $apertura['descripcion_apertura'];
      ?><br>      
      <strong>Código apertura: </strong>
      <?php
        echo $apertura['codigo_apertura'];
      ?><br>
      
      <strong>Sub apertura: </strong>
      <?php
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
                <th scope="col">CODIGO</th>
                <th scope="col">UNIDAD</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">CANTIDAD</th>
              </tr>
            </thead>
              <tbody>
                <?php
                use App\Models\UnidadesMedidaModel;
                use App\Models\ProductoModel;

                $i=1;
                $total=0;
                foreach ($adquisicion_producto_tiene_app as $key => $value):
                  $miProducto = new ProductoModel();
                  $prod = $miProducto->getProducto($value['id_producto']);

                  $miUnidadMedida = new UnidadesMedidaModel();
                  $unidad_medida = $miUnidadMedida->getUnidadMedida($prod['id_unidad_medida']);
              ?>
              <tr>
                  <td><?=$i?></td>
                  <td><?=$prod['codigo']?></td>
                  <td><?=$unidad_medida['nombre_unidad_medida']?></td>
                  <td><?=$prod['nombre_producto']?></td>
                  <td style="text-align:right"><input type="number" name="cant_i_<?=$i?>" id="cant_i_<?=$i?>" value="<?=$value['cantidad']?>"></td>
                  <td><input type="hidden" name="id_i_<?=$i?>" id="id_i_<?=$i?>" value="<?=$value['id_adquisicion_producto_tiene_app']?>"></td>
                  <?php $i++;?>
              </tr>
              <?php endforeach;?>
              <input type="hidden" name="total_items" id="total_items" value="<?=($i-1)?>">
              </tbody>
            </table>
          </div>
        </div>
      </div><!-- fin row-->
      <div class="row mt-3">
        <div class="col-md-12">
          <button id="btn_aprobar" type="submit" class="btn btn-success text-white">Actualizar</button>
        </div>
      </div>
    </form>


  </div>

</main>
<script>
  let url = '<?=base_url()?>adquisicion_producto_tiene_app/modificar_saldos/<?=$id_bodega?>';
$("#formulario" ).on( "submit", function( event ) {
  if(confirm("¿Esta seguro de realizar los cambios?")){
      $("#btn_aprobar").prop('disabled', true);
      $("#btn_aprobar").text("Aprobando...");
      
      $('#formulario').attr('action', url);
      return ;      
    }    
  event.preventDefault();
});
  function aprobar(id_orden){
  }
    
</script>

<?= $this->endSection();?>