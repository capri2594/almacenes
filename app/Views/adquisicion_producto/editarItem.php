<form id="formEditar" action="<?= base_url('adquisicion_producto/actualizar_item/'.$adquisicion_producto['id_adquisicion_producto']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-12">
        <label class="text-normal text-dark form-label">PRODUCTO: <span class="text-primary"><?=$producto['nombre_producto']?></span> </label>
      </div>
    </div>
    <div class="row mt-3">
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD A INGRESAR:</label>
        <input id="cant_ingreso_editar" name="cant_ingreso_editar" type="number" class="form-control" value="<?=$adquisicion_producto['cant_ingreso']?>" placeholder="10">
      </div>
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">PRECIO UNITARIO:</label>
        <input id="precio_adquisicion_editar" name="precio_adquisicion_editar" type="number" class="form-control" value="<?=$adquisicion_producto['precio_adquisicion']?>" placeholder="15.5">
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
	.addField('#cant_ingreso_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#precio_adquisicion_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
