<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
$(document).ready(function () {
  $('#id_apertura').on('change', function() {
        var selectedApertura = $(this).val();
        if(selectedApertura!=""){
          $("#ajax_idsub_apertura").html('<img class="img-thumbnail" src="<?=base_url()?>assets/static/images/loader.gif">');
          
          $.get('<?= base_url('usuario/cargar_sub_apertura/') ?>' + selectedApertura, function(data) {
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
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('usuario/')?>'">Volver a lista de usuarios</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>

    <h4 class="c-grey-900 mT-10 mB-30">ASIGNACIÓN DE APERTURAS A USUARIOS <span class="text-primary"> (<?=$usuario['nombre']?>) <span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
          <form id="formulario" action="<?php echo base_url('usuario/asignar_apertura_guardar');?>" method="post">
            <input type="hidden" name="username" id="username" value="<?php echo $usuario['username'];?>">
            <?= csrf_field() ?>
            <div class="form-group row">
              <label for="apertura" class="col-sm-4 col-form-label fw-bold">SELECCIONE APERTURA: </label>
              <div class="col-sm-8">
                <select class="form-control" id="id_apertura" name="id_apertura">
                  <option value="">Seleccione apertura</option>
                  <?php foreach($aperturas as $aperura):?>
                    <option value="<?php echo $aperura['id_apertura'];?>"><?php echo $aperura['codigo_apertura'].' - '.$aperura['descripcion_apertura'];?></option>
                  <?php endforeach;?>
                </select>
              </div>
            </div>
            <div class="form-group row mt-3">
                <label for="id_sub_apertura" class="col-sm-4 col-form-label fw-bold">SELECCIONE SUB APERTURA: </label>
                <div class="col-sm-8">
                  <div id="ajax_idsub_apertura">
                    <select class="form-control" id="id_sub_apertura" name="id_sub_apertura">
                      <option value="">Sin sub apertura</option>
                    </select>
                  </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-sm-4">
                  <button type="submit" class="btn btn-success btn-sm text-white">ASIGNAR</button>
                </div>
            </div>
            
          </form>
        </div>
      </div>
    </div>

		<?php if (session()->getFlashdata('error_usuario_con_app')): ?>
      <div class="row mt-3">
        <div class="alert alert-danger">
          <?= session()->getFlashdata('error_usuario_con_app') ?>
        </div>
      </div>
    <?php endif; ?>


    <div class="row mt-3">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="toolbar-filtro">
          <div class="row align-items-center g-2">
            <div class="col-md-6">
              <span class="fw-bold text-dark"><i class="ti-folder me-1"></i> APERTURAS ASIGNADAS</span>
            </div>
            <div class="col-md-6">
              <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <div class="input-group input-busqueda-grupo" style="max-width: 350px;">
                  <span class="input-group-text"><i class="ti-search"></i></span>
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar apertura..." autocomplete="off" data-tabla="#tablaUsuarioAperturas" data-contador="#contadorUsuarioAperturas" data-noresult="#sinResultadosUsuarioAperturas">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorUsuarioAperturas" class="badge-contador-tabla">Total: <?=isset($usuario_apertura) ? count($usuario_apertura) : 0?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaUsuarioAperturas" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">APERTURA ASIGNADA</th>
                <th scope="col">SUB APERTURA</th>
                <th scope="col">ELIMINAR ACCESO</th>
              </tr>
            </thead>
            <tbody>
              <?php
              use App\Models\AperturasModel;
              use App\Models\SubAperturasModel;

              if(isset($usuario_apertura)){
                $i = 1;
                $salida='';
                foreach ($usuario_apertura as $key => $us_app) {
                  $objApertura = new AperturasModel();
                  $apertura = $objApertura->getApertura($us_app['id_apertura']);
                  $salida.= '
                    <tr class="fila-datos">
                      <td>'.($i++).'</td>
                      <td><strong>'.$apertura['codigo_apertura'].' -  '.$apertura['descripcion_apertura'].'</strong></td>';
                      if(($us_app['id_sub_apertura']==0) || (is_null($us_app['id_sub_apertura']))){
                        $salida.= '<td></td>';
                      }else{
                        $objSubApertura = new SubAperturasModel();
                        $sub_apertura = $objSubApertura->getSubApertura($us_app['id_sub_apertura']);

                        $salida.= '<td>'.$sub_apertura['codigo_sub_apertura'].' - '.$sub_apertura['descripcion_sub_apertura'].'</td>';
                      }
                      
                  $salida.='<td><a href="'.base_url('usuario/eliminar_acceso_apertura/'.$us_app['id_usuario_apertura']).'" class="btn btn-danger btn-sm">ELIMINAR</a></td>
                    </tr>
                  ';
                }
                echo $salida;
              }
              ?>
              <tr id="sinResultadosUsuarioAperturas" style="display:none;">
                <td colspan="4" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron registros que coincidan con la búsqueda.
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</main>

<script>
  const validator = new JustValidate('#formulario');
	validator
	.addField('#id_apertura', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>

<?= $this->endSection();?>
