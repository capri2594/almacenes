<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE BODEGAS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="mT-5 mB-30">
          <div class="gap-10 peers">
            <div class="peer">
            <?php if((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1)):?>
              <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico">NUEVO</button>
            <?php endif;?>

            </div>
          </div>
        </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">NOMBRE</th>
                <th scope="col">RESPONSABLE BODEGA</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $estados = estados_acceso();
              foreach ($bodegas as $key => $value): ?>
                <tr>
                  <td><?=$value['id_bodega']?></td>
                  <td><button class="btn btn-link" onclick="location.href = '<?=base_url();?>bodega/gestionarBodega/<?=$value['id_bodega']?>'"><?=$value['nombre_bodega']?></button></td>
                  <td><?=$value['recurso_username']?></td>
                  <td><?=$estados[$value['estado_bodega']]?></td>
                  <?php if((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1)):?>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar(<?=$value['id_bodega']?>)" class="btn btn-warning btn-sm">EDITAR</button></td>
                  <?php else:?>
                    <td></td>
                  <?php endif;?>
                </tr>
              <?php endforeach?>
            </tbody>
          </table>
          <?= $pager->links();?>
        </div>
      </div>
    </div>
  </div>
</main>

<!-- Modal nuevo -->
<div class="modal fade" id="modal_estatico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">NUEVO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevo" action="<?= base_url('bodega/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">NOMBRE BODEGA:</label>
            <input id="nombre_bodega" name="nombre_bodega" type="text" class="form-control" placeholder="BODEGA 1">
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">DIRECCIÓN BODEGA:</label>
            <input id="direccion_bodega" name="direccion_bodega" type="text" class="form-control" placeholder="AV. SIEMPRE VIVA N°123">
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">RESPONSABLE BODEGA:</label>
            <?php
              $dataUsuarios = array();
              if(count($usuarios)>0){
                foreach ($usuarios as $key => $value)
                  $dataUsuarios[$value['username']] = $value['nombre'];
                $js='class="form-control"';
                echo form_dropdown('recurso_username', $dataUsuarios, '', $js);
              }else{
                echo '<div class="text-danger">Aun no existen usuarios con el rol de almacen.</div>';
              }
            ?>
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">CONTROL EXPIRACIÓN:</label>
            <select name="control_expiracion" id="control_expiracion" class="form-control">
              <option value="0">NO</option>
              <option value="1">SI</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">PERMITIR ATENCIÓN SOLICITUDES:</label>
            <select name="atender_solicitud" id="atender_solicitud" class="form-control">
              <option value="0">NO</option>
              <option value="1">SI</option>
            </select>
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
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<!-- Modal Editar -->
<div class="modal fade" id="modal_estatico_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
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
    $.get('<?=base_url()?>/bodega/editar/'+id)
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
    alert("Eliminando "+id);
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#nombre_bodega', [
		{
		rule: 'required',
		}
	])
	.addField('#direccion_bodega', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
