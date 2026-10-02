<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
$(document).ready(function () {
  $('#id_apertura').on('change', function() {//apertura origen
        var selectedApertura = $(this).val();
        if(selectedApertura!=""){
          $("#ajax_idsub_apertura").html('<img class="img-thumbnail" src="<?=base_url()?>assets/static/images/loader.gif">');
          
          $.get('<?= base_url('transferencia/cargar_sub_apertura/') ?>' + selectedApertura, function(data) {
            $("#ajax_idsub_apertura").html(data);
          }).fail(function() {
            $("#ajax_idsub_apertura").html('<p class="text-danger">Error al cargar los datos.</p>');
          });
        }
    });

    $('#id_apertura_destino').on('change', function() {//apertura destino
      var selectedApertura_destino = $(this).val();
        if(selectedApertura_destino!=""){
          $("#ajax_idsub_apertura_destino").html('<img class="img-thumbnail" src="<?=base_url()?>assets/static/images/loader.gif">');
          
          $.get('<?= base_url('transferencia/cargar_sub_apertura_destino/') ?>' + selectedApertura_destino, function(data) {
            $("#ajax_idsub_apertura_destino").html(data);
          }).fail(function() {
            $("#ajax_idsub_apertura_destino").html('<p class="text-danger">Error al cargar los datos destino. '+data+'</p>');
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

    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE TRANSFERENCIAS</h4>
    <?php if (session()->getFlashdata('error_transferencia')): ?>
      <div class="alert alert-danger">
        <?= session()->getFlashdata('error_transferencia') ?>
      </div>
    <?php endif; ?>

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
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar por fecha, apertura, glosa..." autocomplete="off" data-tabla="#tablaTransferencias" data-contador="#contadorTransferencias" data-noresult="#sinResultadosTransferencias">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorTransferencias" class="badge-contador-tabla">Total: <?=count($transferencias)?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaTransferencias" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">DATO DE LA CREACIÓN</th>
                <th scope="col">AP ORIGEN</th>
                <th scope="col">SUB AP ORIGEN</th>
                <th scope="col">GLOSA</th>
                <th scope="col">AP DESTINO</th>
                <th scope="col">SUB AP DESTINO</th>
                <th scope="col">CONTENIDO</th>
              </tr>
            </thead>
            <tbody>
              <?php
              use App\Models\AperturasModel;
              use App\Models\SubAperturasModel;
              
                $i=1;
                $html='';
                foreach ($transferencias as $key => $transf){
                  $objApp = new AperturasModel();
                  $app = $objApp->getApertura($transf['id_apertura']);
                  $app_destino = $objApp->getApertura($transf['id_apertura_destino']);

                  $objSubApp = new SubAperturasModel();
                  $sub_app = $objSubApp->getSubApertura($transf['id_sub_apertura']);
                  $sub_app_txt = (is_null($sub_app))?'Sin sub apertura':($sub_app['codigo_sub_apertura'].' - '.$sub_app['descripcion_sub_apertura']);

                  $sub_app_destino = $objSubApp->getSubApertura($transf['id_sub_apertura_destino']);
                  $sub_app_destino_txt = (is_null($sub_app_destino))?'Sin sub apertura':($sub_app_destino['codigo_sub_apertura'].' - '.$sub_app_destino['descripcion_sub_apertura']);

                  $html.='
                    <tr class="fila-datos">
                      <td>'.($i++).'</td>
                      <td>'.datetime_to_es(($transf['fecha_transferencia'])).'</td>
                      <td>'.($app['codigo_apertura'].' - '.$app['descripcion_apertura']).'</td>
                      <td>'.($sub_app_txt).'</td>
                      <td>'.($transf['glosa']).'</td>
                      <td>'.($app_destino['codigo_apertura'].' - '.$app_destino['descripcion_apertura']).'</td>
                      <td>'.($sub_app_destino_txt).'</td>';
                      if($transf['estado_transferencia']==1){
                        $html.='<td><a href="'.base_url('contenido_transferencia/').$transf['id_transferencia'].'" class="btn btn-primary btn-sm">Contenido</a></td>';
                      }else{
                        $html.='<td><a href="'.base_url('contenido_transferencia/').$transf['id_transferencia'].'" class="btn btn-primary btn-sm">Ver contenido</a></td>';
                      }
                    
                  $html.='</tr>';
                }
                echo $html;
              ?>
              <tr id="sinResultadosTransferencias" style="display:none;">
                <td colspan="8" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron transferencias que coincidan con la búsqueda.
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
            <form id="formNuevo" action="<?= base_url('transferencia/crear_transferencia') ?>" method="post" autocomplete="off" novalidate="novalidate">
              <?= csrf_field() ?>
              <div class="row" style="background-color: #bdd1ff; padding:10px; border-radius:10px">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">SELECCIONE APERTURA ORIGEN:</label>
                  <select class="form-control" id="id_apertura" name="id_apertura">
                    <option value="">Seleccione apertura</option>
                    <?php foreach($aperturas as $aperura):?>
                      <option value="<?php echo $aperura['id_apertura'];?>"><?php echo $aperura['codigo_apertura'].' - '.$aperura['descripcion_apertura'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">SELECCIONE SUB APERTURA ORIGEN:</label>
                  <div id="ajax_idsub_apertura">
                    <select class="form-control" id="id_sub_apertura" name="id_sub_apertura">
                      <option value="">Sin sub apertura</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="row mt-3" style="background-color: #c4ffc6; padding:10px; border-radius:10px">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">SELECCIONE APERTURA DESTINO:</label>
                  <select class="form-control" id="id_apertura_destino" name="id_apertura_destino">
                    <option value="">Seleccione apertura</option>
                    <?php foreach($aperturas as $aperura):?>
                      <option value="<?php echo $aperura['id_apertura'];?>"><?php echo $aperura['codigo_apertura'].' - '.$aperura['descripcion_apertura'];?></option>
                    <?php endforeach;?>
                  </select>
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">SELECCIONE SUB APERTURA DESTINO:</label>
                  <div id="ajax_idsub_apertura_destino">
                    <select class="form-control" id="id_sub_apertura_destino" name="id_sub_apertura_destino">
                      <option value="">Sin sub apertura</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">GLOSA:</label>
                  <textarea class="form-control" id="glosa" name="glosa" rows="3" required></textarea>
                  </div>
                </div>
              </div>
              
              <div class="row mt-4">
                <div class="col-md-6 mt-3">
                  <input id="id_bodega" name="id_bodega" type="hidden" class="form-control" value="<?=$id_bodega?>">
                </div>
                <div class="">
                  <div class="peers ai-c jc-sb fxw-nw">
                    <div class="peer">
                      <button type="submit" class="btn btn-success btn-color">REALIZAR ACCIÓN</button>
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
