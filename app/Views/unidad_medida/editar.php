<form id="formEditar" action="<?= base_url('unidad_medida/actualizar/'.$unidad_medida['id_unidad_medida']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php $estados = estados_acceso();?>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">NOMBRE BODEGA:</label>
    <input id="nombre_unidad_medida_editar" name="nombre_unidad_medida_editar" type="text" class="form-control" value="<?=$unidad_medida['nombre_unidad_medida'];?>" placeholder="CAJA">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">ESTADO:</label>
    <?php
      $js='class="form-control"';
      echo form_dropdown('estado_unidad_medida_editar', $estados, $unidad_medida['estado_unidad_medida'], $js);
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
	.addField('#nombre_unidad_medida_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
