<form id="formEditar" action="<?= base_url('sub_apertura/actualizar/'.$sub_apertura['id_sub_apertura']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php $estados = estados_acceso();?>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">CÓDIGO SUB APERTURA:</label>
    <input id="codigo_sub_apertura_editar" name="codigo_sub_apertura_editar" type="text" class="form-control" value="<?=$sub_apertura['codigo_sub_apertura'];?>" placeholder="000 0 001">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">DESCRIPCIÓN SUB APERTURA:</label>
    <input id="descripcion_sub_apertura_editar" name="descripcion_sub_apertura_editar" type="text" class="form-control" value="<?=$sub_apertura['descripcion_sub_apertura'];?>" placeholder="GABINETE DESPACHO">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">ESTADO:</label>
    <?php
      $js='class="form-control"';
      echo form_dropdown('estado_sub_apertura_editar', $estados, $sub_apertura['estado_sub_apertura'], $js);
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
	.addField('#codigo_sub_apertura_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#descripcion_sub_apertura_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
