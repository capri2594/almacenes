<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
 $(document).ready(function() {
    $('#id_partida').select2({dropdownParent: $('#modal_estatico')});
});
</script>

<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('bodega/gestionarBodega/'.$id_bodega)?>'">Volver a gestionar</a></div>
      <div class="col-sm mt-2 d-grid"><a class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal_buscar">Buscar</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>

    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE PRODUCTOS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="toolbar-filtro">
          <div class="row align-items-center g-2">
            <div class="col-md-4">
              <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1)):?>
                <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico"><i class="ti-plus"></i> NUEVO</button>
              <?php endif;?>
            </div>
            <div class="col-md-8">
              <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <div class="input-group input-busqueda-grupo" style="max-width: 400px;">
                  <span class="input-group-text"><i class="ti-search"></i></span>
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar por código, nombre, partida..." autocomplete="off" data-tabla="#tablaProductos" data-contador="#contadorProductos" data-noresult="#sinResultadosProd">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorProductos" class="badge-contador-tabla">Total: <?=count($productos)?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaProductos" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">CODIGO</th>
                <th scope="col">PARTIDA</th>
                <th scope="col">NOMBRE PRODUCTO</th>
                <th scope="col">UNIDAD MEDIDA</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
                <th scope="col">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $estados = estados_acceso();
              $i=1;
              foreach ($productos as $key => $value):
                $unid = $unidad->getUnidadMedida($value['id_unidad_medida']);
              ?>
                <tr class="fila-datos fila-producto">
                  <td><?=$i++;?></td>
                  <td><strong><?=$value['codigo']?></strong></td>
                  <td><?=$value['id_partida']?></td>
                  <td><?=$value['nombre_producto']?></td>
                  <td><?=$unid['nombre_unidad_medida']?></td>
                  <td><?=$estados[$value['estado_producto']]?></td>
                  
                  <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1)):?>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar(<?=$value['id_producto']?>)" class="btn btn-warning btn-sm">EDITAR</button></td>
                        <?php if(session()->isLoggedIn['nivel']==1):?>
                        <td><button onclick="eliminar(<?=$value['id_producto']?>)" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                        <?php else:?>
                          <td></td>
                        <?php endif;?>
                  <?php else:?>
                    <td></td>
                    <td></td>
                  <?php endif;?>
                </tr>
              <?php endforeach?>
              <tr id="sinResultadosProd" style="display:none;">
                <td colspan="8" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron productos que coincidan con la búsqueda.
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal nuevo -->
<div class="modal fade" id="modal_estatico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">NUEVO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
            <form id="formNuevo" action="<?= base_url('producto/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
              <?= csrf_field() ?>
              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">NOMBRE PRODUCTO:</label>
                  <input id="nombre_producto" name="nombre_producto" type="text" class="form-control" placeholder="BOLIGRAFO PILOT AZUL">
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">UNIDAD MEDIDA:</label>
                  <?php 
                  $dataUnidades = array();
                  foreach ($unidades_medida as $key => $value)
                    $dataUnidades[$value['id_unidad_medida']] = $value['nombre_unidad_medida'];
                  
                    $js="class='form-control'";
                    echo form_dropdown('id_unidad_medida',$dataUnidades,'',$js);
                  ?>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12 mt-3">
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

              <div class="row mt-4">
                <div class="col-md-6 mt-3">
                  <input id="id_bodega" name="id_bodega" type="hidden" class="form-control" value="<?=$id_bodega?>">
                </div>
                <div class="">
                  <div class="peers ai-c jc-sb fxw-nw">
                    <div class="peer">
                      <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
                      <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<!-- Modal Editar -->
<div class="modal fade" id="modal_estatico_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">EDITAR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="contenido_modal_editar">Cargando...</div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal editar -->

<!-- Modal BUSCAR -->
<div class="modal fade" id="modal_buscar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">BUSCAR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <form id="formBuscar" action="<?= base_url('producto/buscar/'.$id_bodega) ?>" method="post" autocomplete="off" novalidate="novalidate">
            <?= csrf_field() ?>
            <div class="row">
              <div class="col-md-12 mt-3">
                <label class="text-normal text-dark form-label">BUSCAR:</label>
                <input id="termino_buscar" name="termino_buscar" type="text" class="form-control">
              </div>
            </div>
            <div class="row mt-3">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">BUSCAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>            
          </form>
        </div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal BUSCAR -->

<script>
  
  function editar(id){
    $.get('<?=base_url()?>/producto/editar/'+id)
      .done(function(data) {  
        $('#contenido_modal_editar').html(data);
      })
      .fail(function(xhr, status, error) {
        console.error(error);
      });
  }
  
  let modalNuevo = document.getElementById('modal_estatico')
    modalNuevo.addEventListener('hidden.bs.modal', function (event) {
      let formNuevo = document.getElementById('formNuevo')
      formNuevo.reset();
  });

  function eliminar(id){
    if(confirm('¿Esta seguro de eliminar el item?'))
      location.href = "<?=base_url('producto/eliminar/')?>"+id;
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#nombre_producto', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});

</script>

<?= $this->endSection();?>
