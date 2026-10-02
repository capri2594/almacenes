<div class="container">
  <form id="formEstado" action="<?= base_url('ingreso_salida/actualizar_estado/'.$ingreso_salida['id_ingreso_salida']) ?>" method="post" autocomplete="off" novalidate="novalidate">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-12">
        <label class="text-normal text-dark form-label">ESTADO:</label>
        <?php
          $estados_nro_adq = [
            0 => 'En curso',
            1 => 'Finalizado',
            2 => 'Anulado',
          ];
          $jsEstadoNro='id="estado_ingreso_salida" class="form-control"';
          echo form_dropdown('estado_ingreso_salida', $estados_nro_adq, $ingreso_salida['estado_ingreso_salida'].'', $jsEstadoNro);
        ?>
      </div>
    </div>

    <div class="mt-3">
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
	const validator2 = new JustValidate('#formEstado');
	validator2
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
