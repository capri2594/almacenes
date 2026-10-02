<div class="container">
  <form id="formEditar" action="<?= base_url('adquisicion_producto/add_producto') ?>" method="post" autocomplete="off" novalidate="novalidate">
    <?= csrf_field();?>
    <div class="row">
      <div class="col-md-12">
        <label class="text-normal text-dark form-label">PRODUCTO: <span class="text-primary"><?=$producto['nombre_producto']?></span> </label>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">CANTIDAD A INGRESAR:</label>
        <input id="cant_ingreso" name="cant_ingreso" type="number" class="form-control" placeholder="10">
        <input id="id_nro_adquisicion" name="id_nro_adquisicion" type="hidden" class="form-control" value="<?=$id_nro_adquisicion?>">
      </div>
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">PRECIO UNITARIO:</label>
        <input id="precio_adquisicion" name="precio_adquisicion" type="number" class="form-control" placeholder="15.5">
        <input id="id_producto" name="id_producto" type="hidden" class="form-control" value="<?=$producto['id_producto']?>">
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
	const validator2 = new JustValidate('#formEditar');
	validator2
	.addField('#cant_ingreso', [
		{
		rule: 'required',
		}
	])
	.addField('#precio_adquisicion', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
