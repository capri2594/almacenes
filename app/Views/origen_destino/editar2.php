<form id="formEditar" action="<?= base_url('origen_destino/actualizar2/'.$orig_dest['id_origen_destino']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php $estados = estados_acceso();?>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">DESCRIPCIÓN:</label>
    <input id="descripcion_editar" name="descripcion_editar" type="text" class="form-control" value="<?=$orig_dest['descripcion'];?>" placeholder="SECRETARIA DEPARTAMENTAL DE ADMINISTRACIÓNN Y FINANZAS PUPBLICAS">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">ESTADO:</label>
    <?php
      $js='class="form-control"';
      echo form_dropdown('estado_origen_destino_editar', $estados, $orig_dest['estado_origen_destino'], $js);
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
	.addField('#descripcion_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
