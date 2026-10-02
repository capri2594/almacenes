<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
 $(document).ready(function() {
    $('#id_partida').select2();
});
</script>

<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('ingreso_salida/'.$id_bodega)?>'">Atras</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>
    <h4 class="c-grey-900 mT-10 mB-30">BODEGA: <?=$bodega['nombre_bodega']?></h4>
    <form action="<?=base_url('ingreso_salida_items/add/'.$id_bodega.'/'.$id_ingreso_salida)?>" method="post" id="formItems" autocomplete="off" novalidate="novalidate">
      <?=csrf_field() ?>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label class="text-normal text-dark form-label">PARTIDA:</label>
              <select id="id_partida" name="id_partida" class="form-control" style="width: 100%;">
                <?php
                  foreach ($referenciaPartida as $key => $value):
                ?>
                <option value="<?=$value['id_partida']?>"> <?=$value['id_partida']?> | <?=$value['descripcion']?></option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="text-normal text-dark form-label">PRODUCTO/ITEM:</label>
              <input type="text" class="form-control" id="nombre_producto" name="nombre_producto">
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-6">
            <div class="form-group">
              <label class="text-normal text-dark form-label">CANTIDAD:</label>
              <input type="number" class="form-control" id="cantidad" name="cantidad">
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="text-normal text-dark form-label">PRECIO ADQUISICIÓN:</label>
              <input type="number" class="form-control" id="precio_unitario" name="precio_unitario">
            </div>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-md-6">
            <div class="form-group">
              <label class="text-normal text-dark form-label">UNIDAD MEDIDA:</label>
              <select id="id_unidad_medida" name="id_unidad_medida" class="form-control" style="width: 100%;">
                <?php
                  foreach ($unidades_medida as $key => $value):
                ?>
                <option value="<?=$value['id_unidad_medida']?>"> <?=$value['nombre_unidad_medida']?></option>
                <?php endforeach;?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label class="text-normal text-dark form-label">AGREGAR A LA LISTA:</label><br>
              <button type="submit" class="form-control btn btn-success btn-sm text-white" style="width: 200px;">AGREGAR</button>
            </div>
          </div>
        </div>

    </form>

    <div class="row mt-3">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
          <caption>LISTA DE PRODUCTOS/ITEMS INGRESO Y SALIDA</caption>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">PARTIDA</th>
                <th scope="col">NOMBRE PRODUCTO</th>
                <th scope="col">UNIDAD MEDIDA</th>
                <th scope="col">CANTIDAD</th>
                <th scope="col">PRECIO UNITARIO</th>
                <th scope="col">SUBTOTAL</th>
                <th scope="col">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              use App\Models\UnidadesMedidaModel;
              $objUnidadesMedida = new UnidadesMedidaModel();
              $i=1;
              $total = 0;
                foreach ($ingreso_salida_items as $key => $value):
                  $total += $value['cantidad']*$value['precio_unitario'];
              ?>
                <tr>
                  <td><?=($i++)?></td>
                  <td><?=$value['id_partida']?></td>
                  <td><?=$value['nombre_producto']?></td>
                  <td><?=$objUnidadesMedida->getUnidadMedida($value['id_unidad_medida'])['nombre_unidad_medida']?></td>
                  <td style='text-align: right;'><?=number_format($value['cantidad'], 2, ',', '.')?></td>
                  <td style='text-align: right;'><?=number_format($value['precio_unitario'], 2, ',', '.')?></td>
                  <td style='text-align: right;'><?=number_format($value['cantidad']*$value['precio_unitario'], 2, ',', '.')?></td>
                  <?php if ($ing_salida['estado_ingreso_salida']==0):?>
                    <td><button type="button" onclick="eliminar('<?=$value['id_ingreso_salida_items']?>')" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                  <?php else:?>
                    <td></td>
                  <?php endif;?>
                </tr>
              <?php endforeach?>
              <tr>
                <td colspan="6" style='text-align: right; font-weight: bold;'>TOTAL:</td>
                <td style='text-align: right; font-weight: bold;'><?=number_format($total, 2, ',', '.')?></td>
                <td></td>
              </tr>
            </tbody>
          </table>
        </div>
        
      </div>
    </div>
  </div>
</main>
<script>

  function eliminar(id){
    if(confirm('¿Esta seguro de eliminar el item?'))
      location.href = "<?=base_url('ingreso_salida_items/eliminar/')?>"+id;
  }  

	const validator = new JustValidate('#formItems');
	validator
	.addField('#nombre_producto', [
		{
		rule: 'required',
		}
	])
	.addField('#cantidad', [
		{rule: 'required'},
		{rule: 'minNumber', value: 1},
	])
	.addField('#precio_unitario', [
		{rule: 'required'}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});

</script>

<?= $this->endSection();?>
