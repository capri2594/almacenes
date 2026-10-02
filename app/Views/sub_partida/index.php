<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary mb-3" href="<?=base_url('partida/')?>">Volver a lista de partidas</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>

    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE SUB PARTIDAS DE:</h4>
    <h5 class="c-blue-900 mT-10 mB-30"><?php echo $partida['id_partida'].' - '.$partida['descripcion']?></h5>
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
                <th scope="col">#</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">EDITAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                $html='';
                foreach ($sub_partidas as $key => $sub_partida){
                  $html.= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.$sub_partida['descripcion'].'</td>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar('.$sub_partida['id_rangos_partida'].')" class="btn btn-warning btn-sm">Editar</button></td>
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
        <form id="formNuevo" action="<?= base_url('sub_partida/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <input id="id_partida" name="id_partida" type="hidden" class="form-control" value="<?=$partida['id_partida']?>">
          <div class="mb-3">
            <label class="text-normal text-dark form-label">DESCRIPCIÓN:</label>
            <input id="descripcion" name="descripcion" type="text" class="form-control" placeholder="Alimentos para Animales Gastos destinados a la adquisición de forrajes y otros alimentos para animales de propiedad de instituciones públicas; alimentación de los animales de propiedad del Ejército y de la Policía Boliviana, parques zoológicos, laboratorios de experimentación y otros.">
            <input id="id_partida" name="id_partida" type="hidden" class="form-control" value="<?=$partida['id_partida']?>">
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
    $.get('<?=base_url()?>/sub_partida/editar/'+id)
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
