<div class="container">
  <form id="formItems" action="<?= base_url('contenido_transferencia/add_producto') ?>" method="post" autocomplete="off">
    <?= csrf_field();?>
    <div class="row">
      <div class="col-md-12">
        <label class="text-normal text-dark form-label">PRODUCTO: <span class="text-primary"><?=$producto['nombre_producto']?></span> </label>
      </div>
    </div>

    <div class="row mt-3">
    <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD A TRANSFERIR</label>
        <input id="cantidad_transferencia" name="cantidad_transferencia" type="number" class="form-control" placeholder="10" min="1" max="<?=$cant_existente?>" required>
        <input id="id_transferencia" name="id_transferencia" type="hidden" class="form-control" value="<?=$id_transferencia?>">
        <input id="id_producto" name="id_producto" type="hidden" class="form-control" value="<?=$producto['id_producto']?>">
      </div>
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD MAXIMA DISPONIBLE:</label>
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
