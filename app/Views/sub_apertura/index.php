<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary mb-3" href="<?=base_url('apertura/')?>">Volver a lista de aperturas</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>

    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE SUB APERTURAS DE:</h4>
    <h5 class="c-blue-900 mT-10 mB-30"><?php echo $apertura['codigo_apertura'].' - '.$apertura['descripcion_apertura']?></h5>
  <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="mT-5 mB-30">
          <div class="gap-10 peers">
            <div class="peer">
              <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico">NUEVO</button>
            </div>
          </div>
        </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">CÓDIGO</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $estados = estados_acceso();
                $i=1;
                $html='';
                foreach ($sub_aperturas as $key => $sub_ap){
                  $html.= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$sub_ap['codigo_sub_apertura'].'</td>
                    <td>'.$sub_ap['descripcion_sub_apertura'].'</td>
                    <td>'.$estados[$sub_ap['estado_sub_apertura']].'</td>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar('.$sub_ap['id_sub_apertura'].')" class="btn btn-warning btn-sm">Editar</button></td>
                  ';
                }
                echo $html;
              ?>
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
        <form id="formNuevo" action="<?= base_url('sub_apertura/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <input id="id_apertura" name="id_apertura" type="hidden" class="form-control" value="<?=$apertura['id_apertura']?>">
          <div class="mb-3">
            <label class="text-normal text-dark form-label">CÓDIGO SUB APERTURA:</label>
            <input id="codigo_sub_apertura" name="codigo_sub_apertura" type="text" class="form-control" placeholder="000 0 001">
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">DESCRIPCIÓN SUB APERTURA:</label>
            <input id="descripcion_sub_apertura" name="descripcion_sub_apertura" type="text" class="form-control" placeholder="UNIDAD DE MOVIMIENTOS SOCIALES">
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
    $.get('<?=base_url()?>/sub_apertura/editar/'+id)
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
	.addField('#codigo_sub_apertura', [
		{
		rule: 'required',
		}
	])
	.addField('#descripcion_sub_apertura', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
