<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
$(document).ready(function () {
  $('#id_apertura').on('change', function() {//apertura origen
        var selectedApertura = $(this).val();
        if(selectedApertura!=""){
          $("#ajax_idsub_apertura").html('<img class="img-thumbnail" src="<?=base_url()?>assets/static/images/loader.gif">');
          
          $.get('<?= base_url('adquisicion_producto_tiene_app/cargar_sub_apertura/') ?>' + selectedApertura, function(data) {
            $("#ajax_idsub_apertura").html(data);
          }).fail(function() {
            $("#ajax_idsub_apertura").html('<p class="text-danger">Error al cargar los datos.</p>');
          });
        }
    });
});
</script>

<main class="main-content bgc-grey-100">
  <div class="container-fluid">
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('bodega/gestionarBodega/'.$id_bodega)?>'">Volver a gestionar</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

    <h4 class="c-grey-900 mT-10 mB-30">SELECCIONE APERTURA/SUB APERTURA</h4>
    <?php if (session()->getFlashdata('error_transferencia')): ?>
      <div class="alert alert-danger">
        <?= session()->getFlashdata('error_transferencia') ?>
      </div>
    <?php endif; ?>
<?php
use App\Models\AperturasModel;
use App\Models\SubAperturasModel;

$aperturaModel = new  AperturasModel();
$aperturas = $aperturaModel->getAperturasHabilitadas();

$aperturaModel = new SubAperturasModel();
$sub_aperturas = $aperturaModel->getSubAperturasHabilitadas();
?>

    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="mT-5 mB-30">
          <div class="gap-10 peers">
            <div class="peer">
              <form action="<?= base_url('adquisicion_producto_tiene_app') ?>/generar_saldos_modificar/<?= $id_bodega ?>" method="get">
              <?= csrf_field() ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <label for="id_apertura" class="form-label">APERTURA</label>
                      <select name="id_apertura" id="id_apertura" class="form-select">
                        <option value="">Seleccione</option>
                        <?php foreach ($aperturas as $apertura): ?>
                          <option value="<?= $apertura['id_apertura'] ?>"><?= $apertura['codigo_apertura'] ?> - <?= $apertura['descripcion_apertura'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-12 mt-3">
                    <div class="form-group">
                    <label for="id_sub_apertura" class="form-label">SUB APERTURA</label>
                      <div id="ajax_idsub_apertura">
                      <select name="id_sub_apertura" id="id_sub_apertura" class="form-select">
                        <option value="">Seleccione</option>
                        <?php foreach ($sub_aperturas as $sub_apertura): ?>
                          <option value="<?= $sub_apertura['id_sub_apertura'] ?>"><?= $sub_apertura['codigo_sub_apertura'] ?> - <?= $sub_apertura['descripcion_sub_apertura'] ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                    </div>
                  </div>

                  <div class="col-md-12 mt-3">
                    <div class="form-group">
                      <label for="id_sub_apertura" class="form-label">GENERAR</label>
                      <div><button type="submit" class="btn btn-success text-white">Generar</button></div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
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

        </div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<script>
  
  function eliminar(id){
    if(confirm('¿Esta seguro de eliminar el item?'))
      location.href = "<?=base_url('producto/eliminar/')?>"+id;
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#glosa', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
