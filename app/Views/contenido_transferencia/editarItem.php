<form id="formEditar" action="<?= base_url('items_orden/actualizar_item/'.$items_orden['id_items_orden']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php //$partidas = partidasPresupuestarias();?>
    <div class="row">
      <div class="col-md-12">
        <label class="text-normal text-dark form-label">PRODUCTO: <span class="text-primary"><?=$producto['nombre_producto']?></span> </label>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD A INGRESAR:</label>
        <input id="cant_requerida_editar" name="cant_requerida_editar" type="number" class="form-control" value="<?=$items_orden['cant_requerida']?>" placeholder="10">
      </div>
    </div>
  <div class="mt-4">
    <div class="peers ai-c jc-sb fxw-nw">
      <div class="peer">
        <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
        <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
      </div>
    </div>
  </div>
</form>
<script>  
	const validator2 = new JustValidate('#formEditar');
	validator2
	.addField('#cant_requerida_editar', [
		{
		rule: 'required',
		},
    {
      rule: 'number',
    },
    {
      rule: 'minNumber',
      value: 1,
    },
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
