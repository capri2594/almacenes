<div class="container">
  <form id="formItems" action="<?= base_url('items_orden_cargar/add_producto') ?>" method="post" autocomplete="off">
    <?= csrf_field();?>
    <div class="row">
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">PRODUCTO: <span class="text-primary"><?=$producto['nombre_producto']?></span> </label>
      </div>
      <div class="col-md-6">
        <?php
          use App\Models\UnidadesMedidaModel;
          $objUnidadMediada = new UnidadesMedidaModel();
          $unidadMedida = $objUnidadMediada->find($producto['id_unidad_medida']);
        ?>
        <label class="text-normal text-dark form-label">UNIDAD MEDIDA: <span class="text-primary"><?=$unidadMedida['nombre_unidad_medida']?></span> </label>
      </div>
    </div>

    <div class="row mt-3">
    <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD REQUERIDA:</label>
        <input id="cant_requerida" name="cant_requerida" type="number" class="form-control" placeholder="10" min="1" max="<?=$cant_existente?>" required>
        <input id="id_orden" name="id_orden" type="hidden" class="form-control" value="<?=$id_orden?>">
        <input id="id_producto" name="id_producto" type="hidden" class="form-control" value="<?=$producto['id_producto']?>">
      </div>
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD EXISTENTE:</label>
        <input id="cant_existente" name="cant_existente" type="number" class="form-control text-success readonly" value="<?=$cant_existente?>" required>
      </div>
    </div>
    
    <div class="mt-5">
      <div class="peers ai-c jc-sb fxw-nw">
        <div class="peer">
          <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
          <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>  
  $(".readonly").on('keydown paste focus mousedown', function(e){
      if(e.keyCode != 9) // ignore tab
          e.preventDefault();
  });
</script>
