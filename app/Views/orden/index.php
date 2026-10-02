<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">MIS SOLICITUDES</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="mT-5 mB-30">
          <div class="gap-10 peers">
            <div class="peer">
            <?php if(session()->isLoggedIn['estado_recurso']==1):?>
              <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico">NUEVO</button>
            <?php endif;?>
            </div>
          </div>
        </div>
        <?php if (session()->getFlashdata('message')): ?>
          <div class="alert alert-danger">
            <?= session()->getFlashdata('message') ?>
          </div>
        <?php endif; ?>

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">ID</th>
                <th scope="col">BODEGA</th>
                <th scope="col">FECHA CREACIÓN</th>
                <th scope="col">GLOSA</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
                <th scope="col">ITEMS</th>
                <th scope="col">ENVIAR SOLICITUD</th>
                <th scope="col">IMPRIMIR</th>
                <th scope="col">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                use App\Models\BodegasModel;
                $miBodega = new BodegasModel();
                
                $estados_orden = estados_orden();
                foreach ($ordenes as $key => $value):
                  $bodega = $miBodega->getBodega($value['id_bodega']);
              ?>
                <tr>
                  <td><?=$i++?></td>
                  <td><?=$value['id_orden']?></td>
                  <td><?=$bodega['nombre_bodega']?></td>
                  <td><?=date('d/m/Y H:i:s', strtotime($value['fecha_orden']))?></td>
                  <td><?=$value['glosa']?></td>
                  <td><?=$estados_orden[$value['estado_orden']]?></td>
                  <?php
                  $id_orden = $value['id_orden'];
                  switch ($value['estado_orden']) {
                    case 1:
                      echo '
                        <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar('.$value['id_orden'].')" class="btn btn-warning btn-sm">EDITAR</button></td>
                        <td><a href="'.base_url('items_orden/'.$value['id_orden']).'" class="btn btn-success btn-sm">ITEMS</a></td>
                        <td><button onclick="enviar('.$value['id_orden'].')" class="btn btn-primary btn-sm">ENVIAR</button></td>
                        <td></td>
                        <td><button onclick="eliminar('.$value['id_orden'].')" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                      ';
                      break;
                      case 2:
                        echo '
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        ';
                        break;                     
                        case 3:
                          echo '
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><a href="javascript:window.open(`'.base_url("orden/imprimir_solicitud_aprobada/").$id_orden.'`,`Salida`,`parametrosPopPup`)" class="btn btn-primary btn-sm text-white">Imprimir</a></td>
                            <td></td>
                          ';
                          break;                     
                          case 4:
                            echo '
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><a href="javascript:window.open(`'.base_url("orden/imprimir_solicitud_aprobada/").$id_orden.'`,`Salida`,`parametrosPopPup`)" class="btn btn-primary btn-sm text-white">Imprimir</a></td>
                              <td></td>
                            ';
                            break;                     
                          case 5:
                            echo '
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><a href="javascript:window.open(`'.base_url("orden/imprimir_solicitud_aprobada/").$id_orden.'`,`Salida`,`parametrosPopPup`)" class="btn btn-primary btn-sm text-white">Imprimir</a></td>
                              <td></td>
                            ';
                            break;               
                        }?>
                </tr>
              <?php endforeach?>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal nuevo -->
<div class="modal fade" id="modal_estatico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">NUEVO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
            <form id="formNuevo" action="<?= base_url('orden/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
              <?= csrf_field() ?>
              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">OBJETO:</label>
                  <input class="form-control" type="text" id="obj_glosa" name="obj_glosa" placeholder="Material para 5 personas / 123-abc (placa)">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">JUSTIFICACIÓN:</label>
                  <textarea class="form-control" name="glosa" id="glosa"></textarea>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">CELULAR / TELÉFONO DE CONTACTO:</label>
                  <input class="form-control" type="text" id="celular" name="celular" value="<?= esc($recurso_actual['celular'] ?? '') ?>" placeholder="Ej: 71234567">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
                  <?php
                    $dataBodegas[null] = 'Seleccione una bodega';
                    foreach ($us_bo as $key => $value){
                      $miBodega2 = new BodegasModel();
                      $bodega2 = $miBodega2->getBodega($value['id_bodega']);
                      $dataBodegas[$value['id_bodega']] = $bodega2['nombre_bodega'];
                    } 
                    $js='id="id_bodega" class="form-control" ';
                    echo form_dropdown('id_bodega',$dataBodegas, '', $js);
                  ?>
                </div>
              </div>

              <div class="row mt-4">
                  <div class="peers ai-c jc-sb fxw-nw">
                    <div class="peer">
                      <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
                      <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
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
  <div class="modal-dialog modal-lg">
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

<script>
  
  function editar(id){
    $.get('<?=base_url()?>/orden/editar/'+id)
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

  function enviar(id_orden){
    if(confirm('¿Esta seguro de enviar la orden con id = '+id_orden+' ?')){
      location.href = '<?php echo base_url()?>/orden/enviar_orden/'+id_orden;
    }
  }

  function eliminar(id_orden){
    if(confirm('¿Esta seguro de eliminar la orden con id = '+id_orden+' ?')){
      location.href = '<?php echo base_url()?>/orden/eliminar_orden/'+id_orden;
    }
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#glosa', [
		{
		rule: 'required',
		}
	])
	.addField('#obj_glosa', [
		{
		rule: 'required',
		}
	])
	.addField('#id_bodega', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
