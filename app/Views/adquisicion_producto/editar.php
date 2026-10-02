<form id="formEditar" action="<?= base_url('bodega/actualizar/'.$bodega['id_bodega']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php $estados = estados_acceso();?>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">NOMBRE BODEGA:</label>
    <input id="nombre_bodega_editar" name="nombre_bodega_editar" type="text" class="form-control" value="<?=$bodega['nombre_bodega'];?>" placeholder="BODEGA 1">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">DIRECCIÓN BODEGA:</label>
    <input id="direccion_bodega_editar" name="direccion_bodega_editar" type="text" class="form-control" value="<?=$bodega['direccion_bodega'];?>" placeholder="AV. SIEMPRE VIVA N°123">
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">RESPONSABLE BODEGA:</label>
    <?php
      foreach ($usuarios as $key => $value)
        $dataUsuarios[$value['username']] = $value['nombre'];
      $jsUsuarios = 'class="form-control"';
      echo form_dropdown('recurso_username_editar', $dataUsuarios, $bodega['recurso_username'], $jsUsuarios);
    ?>
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">CONTROL EXPIRACIÓN:</label>
    <?php
      $ctrl=[0=>'NO', 1=>'SI'];
      $js_ctrl='class="form-control"';
      echo form_dropdown('control_expiracion_editar', $ctrl, $bodega['control_expiracion'], $js_ctrl);
    ?>
  </div>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">ESTADO:</label>
    <?php
      $js='class="form-control"';
      echo form_dropdown('estado_bodega_editar', $estados, $bodega['estado_bodega'], $js);
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
	.addField('#nombre_bodega_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#direccion_bodega_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
