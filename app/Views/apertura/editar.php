<form id="formEditar" action="<?= base_url('apertura/actualizar/'.$apertura['id_apertura']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php $estados = estados_acceso();?>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">CÓDIGO:</label>
    <input id="codigo_apertura_editar" name="codigo_apertura_editar" type="text" class="form-control" value="<?=$apertura['codigo_apertura'];?>" placeholder="000 0 001">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">DESCRIPCIÓN:</label>
    <input id="descripcion_apertura_editar" name="descripcion_apertura_editar" type="text" class="form-control" value="<?=$apertura['descripcion_apertura'];?>" placeholder="DIRECCION SUPERIOR">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">ESTADO:</label>
    <?php
      $js='class="form-control"';
      echo form_dropdown('estado_apertura_editar', $estados, $apertura['estado_apertura'], $js);
    ?>
  </div>
  <div class="">
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
	.addField('#codigo_apertura_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#descripcion_apertura_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
