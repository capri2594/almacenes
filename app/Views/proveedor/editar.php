<div class="container">
  <form id="formEditar" action="<?= base_url('proveedor/actualizar/'.$proveedor['id_proveedor']) ?>" method="post" autocomplete="off" novalidate="novalidate">
    <?= csrf_field() ?>
    <?php
      $estados = estados_acceso();
      $tiposProveedores = tiposProveedores();
    ?>
    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">RAZÓN SOCIAL:</label>
        <input id="razon_social_editar" name="razon_social_editar" type="text" class="form-control" value="<?=$proveedor['razon_social'];?>" placeholder="CARTONBOL">
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">CI/NIT:</label>
        <input id="ci_nit_editar" name="ci_nit_editar" type="text" class="form-control" value="<?=$proveedor['ci_nit'];?>" placeholder="4145241010">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">DIRECCIÓN:</label>
        <input id="direccion_proveedor_editar" name="direccion_proveedor_editar" type="text" class="form-control" value="<?=$proveedor['direccion_proveedor'];?>" placeholder="AV 24 DE JULIO">
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">TELF/CEL:</label>
        <input id="telefono_proveedor_editar" name="telefono_proveedor_editar" type="text" class="form-control" value="<?=$proveedor['telefono_proveedor'];?>" placeholder="25212452">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">TIPO:</label>
        <?php
          $jsTipo='class="form-control"';
          echo form_dropdown('tipo_proveedor_editar', $tiposProveedores, $proveedor['tipo_proveedor'], $jsTipo);
        ?>        
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">NOTA/OBS:</label>
        <input id="nota_proveedor_editar" name="nota_proveedor_editar" type="text" class="form-control" value="<?=$proveedor['nota_proveedor'];?>" placeholder="EMPRESA DE CONFIANZA">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">PERSONA CONTACTO:</label>
        <input id="nombre_contacto_editar" name="nombre_contacto_editar" type="text" class="form-control" value="<?=$proveedor['nombre_contacto'];?>" placeholder="JUAN PEREZ">
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">CEL/CONTACTO:</label>
        <input id="celular_contacto_editar" name="celular_contacto_editar" type="text" class="form-control" value="<?=$proveedor['celular_contacto'];?>" placeholder="77145478">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">ESTADO:</label>
        <?php
          $js='class="form-control"';
          echo form_dropdown('estado_proveedor_editar', $estados, $proveedor['estado_proveedor'], $js);
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
	const validator2 = new JustValidate('#formEditar');
	validator2
	.addField('#razon_social_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#ci_nit_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#direccion_proveedor_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
