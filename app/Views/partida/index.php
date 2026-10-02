<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE PARTIDAS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="toolbar-filtro">
          <div class="row align-items-center g-2">
            <div class="col-md-4">
              <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico"><i class="ti-plus"></i> NUEVO</button>
            </div>
            <div class="col-md-8">
              <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <div class="input-group input-busqueda-grupo" style="max-width: 400px;">
                  <span class="input-group-text"><i class="ti-search"></i></span>
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar por código, descripción..." autocomplete="off" data-tabla="#tablaPartidas" data-contador="#contadorPartidas" data-noresult="#sinResultadosPartidas">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorPartidas" class="badge-contador-tabla">Total: <?=count($partidas)?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaPartidas" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">CÓDIGO</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">EDITAR</th>
                <th scope="col">SUB PARTIDAS</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $estados = estados_acceso();
                $html='';
                foreach ($partidas as $key => $ap){
                  $html.= '<tr class="fila-datos">
                    <td><strong>'.$ap['id_partida'].'</strong></td>
                    <td>'.$ap['descripcion'].'</td>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar('.$ap['id_partida'].')" class="btn btn-warning btn-sm">Editar</button></td>
                    <td><a href="'.base_url('sub_partida/'.$ap['id_partida']).'" class="btn btn-primary btn-sm">Sub partida</a></td>
                  </tr>';
                }
                echo $html;
              ?>
              <tr id="sinResultadosPartidas" style="display:none;">
                <td colspan="4" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron partidas que coincidan con la búsqueda.
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
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">NUEVO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formNuevo" action="<?= base_url('partida/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">CÓDIGO:</label>
            <input id="id_partida" name="id_partida" type="text" class="form-control" placeholder="31200">
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">DESCRIPCIÓN:</label>
            <input id="descripcion" name="descripcion" type="text" class="form-control" placeholder="Alimentos para Animales Gastos destinados a la adquisición de forrajes y otros alimentos para animales de propiedad de instituciones públicas; alimentación de los animales de propiedad del Ejército y de la Policía Boliviana, parques zoológicos, laboratorios de experimentación y otros.">
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
    $.get('<?=base_url()?>/partida/editar/'+id)
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
	.addField('#id_partida', [
		{
		rule: 'required',
		}
	])
	.addField('#descripcion', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
