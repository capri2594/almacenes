<div class="container">
  <form id="formEditar" action="<?= base_url('orden/actualizar/'.$orden['id_orden']) ?>" method="post" autocomplete="off" novalidate="novalidate">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-12 mt-3">
        <label class="text-normal text-dark form-label">OBJETO:</label>
        <input class="form-control" type="text" id="obj_glosa_editar" name="obj_glosa_editar" value="<?=$orden['obj_glosa']?>" placeholder="Material para 5 personas / 123-abc (placa)">
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-3">
        <label class="text-normal text-dark form-label">JUSTIFICACIÓN:</label>
        <textarea class="form-control" name="glosa_editar" id="glosa_editar"><?=$orden['glosa']?></textarea>
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-12 mt-3">
        <label class="text-normal text-dark form-label">CELULAR / TELÉFONO DE CONTACTO:</label>
        <input class="form-control" type="text" id="celular_editar" name="celular_editar" value="<?= esc($orden['celular'] ?? '') ?>" placeholder="Ej: 71234567">
      </div>
    </div>
    
    <div class="row">
      <div class="col-md-12 mt-3">
        <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
        <?php
          use App\Models\BodegasModel;
          use App\Models\ItemsOrdenModel;
          
          $objItemsOrden = new ItemsOrdenModel();
          $items_orden = $objItemsOrden->getItemsOrdenByIdOrden($orden['id_orden']);
          if(count ($items_orden)==0){
            foreach ($us_bo as $key => $value){
              $miBodega3 = new BodegasModel();
              $bodega3 = $miBodega3->getBodega($value['id_bodega']);
              $dataBodegas[$value['id_bodega']] = $bodega3['nombre_bodega'];
            } 
            $js3='id="id_bodega_editar" class="form-control" ';
            echo form_dropdown('id_bodega_editar',$dataBodegas, $orden['id_bodega'], $js3); 
          }else{
           echo '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>¡Advertencia!</strong> No es posible cambiar de bodega mientras tenga items en su pedido.
            </div>
            <input type="hidden" name="id_bodega_editar" value="'.$orden['id_bodega'].'">
           '; 
          }
        ?>
      </div>
    </div>
    
    <div class="mt-4">
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
	.addField('#glosa_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#obj_glosa_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
