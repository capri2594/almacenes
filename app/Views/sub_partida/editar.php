<form id="formEditar" action="<?= base_url('sub_partida/actualizar/'.$sub_partida['id_rangos_partida']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <div class="mb-3">
    <label class="text-normal text-dark form-label">DESCRIPCIÓN SUB PARTIDA:</label>
    <input id="descripcion_editar" name="descripcion_editar" type="text" class="form-control" value="<?=$sub_partida['descripcion'];?>" placeholder="Alimentos para Animales Gastos destinados a la adquisición de forrajes y otros alimentos para animales de propiedad de instituciones públicas; alimentación de los animales de propiedad del Ejército y de la Policía Boliviana, parques zoológicos, laboratorios de experimentación y otros.">
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
