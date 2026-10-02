<script>
 $(document).ready(function() {
    $('#id_partida_editar').select2({dropdownParent: $('#modal_estatico_editar')});
    $('#id_partida_editar').val('<?=$producto['id_partida']; ?>');
    $('#id_partida_editar').trigger('change');
});
</script>

<div class="container">
  <form id="formEditar" action="<?= base_url('producto/actualizar/'.$producto['id_producto']) ?>" method="post" autocomplete="off" novalidate="novalidate">
    <?= csrf_field() ?>
    <?php
      $estados = estados_acceso();
    ?>
    <div class="row">
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">NOMBRE PRODUCTO:</label>
        <input id="nombre_producto_editar" name="nombre_producto_editar" type="text" class="form-control" value="<?=$producto['nombre_producto'];?>" placeholder="BOLIGRAFO">
      </div>
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">UNIDAD MEDIDA:</label>
        <?php        
        foreach ($unidades_medida as $key => $value)
          $dataUnidades[$value['id_unidad_medida']] = $value['nombre_unidad_medida'];
        
          $js="class='form-control'";
          echo form_dropdown('id_unidad_medida_editar',$dataUnidades,$producto['id_unidad_medida'],$js);
        ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">ESTADO:</label>
        <?php
          $js='class="form-control"';
          echo form_dropdown('estado_producto_editar', $estados, $producto['estado_producto'], $js);
        ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-3">
        <label class="text-normal text-dark form-label">PARTIDA:</label>
        <select id="id_partida_editar" name="id_partida_editar" class="form-control" style="width: 100%;">
          <?php
          foreach ($referenciaPartida as $key => $value):
          ?>
          <option value="<?=$value['id_partida']?>"> <?=$value['id_partida']?> | <?=$value['descripcion']?></option>
          <?php endforeach;?>
        </select>        
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
	.addField('#nombre_producto_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
