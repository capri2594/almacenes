<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
  $(document).ready(function (){
      $('#id_apertura_general').on('change', function() {
        var selectedApertura = $(this).val();
        if(selectedApertura!=""){
          $("#ajax_idsub_apertura").html('<img class="img-thumbnail" src="<?=base_url()?>assets/static/images/loader.gif">');
          
          $.get('<?= base_url('ingreso/cargar_sub_apertura/') ?>' + selectedApertura, function(data) {
            $("#ajax_idsub_apertura").html(data);
          }).fail(function() {
            $("#ajax_idsub_apertura").html('<p class="text-danger">Error al cargar los datos.</p>');
          });
        }          
      });
  });
</script>
<?php
  $tipo_doc = tipoDocumento();
  $dataTipo = tiposOrigenDestino();
  use App\Models\AperturasModel;

?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('bodega/gestionarBodega/'.$id_bodega)?>'">Volver a gestionar</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE INGRESOS - <?=$bodega['nombre_bodega']?></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="mT-5 mB-30">
          <div class="gap-10 peers">
            <div class="peer">
            <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1)):?>
              <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico">NUEVO</button>
            <?php endif;?>
            <p class="row">
              <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-danger">
                  <?= session()->getFlashdata('message') ?>
                </div>
              <?php endif; ?>
            </p>
            </div>
          </div>
        </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N° CORRE.</th>
                <th scope="col">FECHA</th>
                <th scope="col">HOJA RUTA</th>
                <th scope="col">AP. PROG.</th>
                <th scope="col">SUB AP.</th>
                <th scope="col">EDITAR</th>
                <th scope="col">ITEMS</th>
                <th scope="col">PDF</th>
                <th scope="col">FINALIZAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              use App\Models\SubAperturasModel;
                foreach ($nro_adquisiciones as $key => $value): 
              ?>
                <tr>
                  <td><?=$value['nro_correlativo']?></td>
                  <td><?=datetime_to_es($value['fecha_adquisicion'])?></td>
                  <td><?=$value['hoja_ruta']?></td>
                  <td><?php
                      $ojbApertura = new AperturasModel();
                      $apertura_programatica = $ojbApertura->getApertura($value['id_apertura_general']);
                      echo $apertura_programatica['codigo_apertura'].'-'.$apertura_programatica['descripcion_apertura'];?>
                  </td>
                  <?php
                      if(($value['id_sub_apertura']==0) || (is_null($value['id_sub_apertura']))){      
                        echo '<td></td>';
                      }else{
                        $objSubApertura = new SubAperturasModel();
                        $sub_apertura = $objSubApertura->getSubApertura($value['id_sub_apertura']);
                        echo '<td>'.$sub_apertura['descripcion_sub_apertura'].'</td>';
                      }
                  ?>
                  <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1) && ($value['estado_nro_adquisicion']==0)):?>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar(<?=$value['id_nro_adquisicion']?>)" class="btn btn-warning btn-sm">EDITAR</button></td>
                    <td><a href="<?=base_url('adquisicion_producto/'.$id_bodega.'/'.$value['id_nro_adquisicion'])?>" class="btn btn-success btn-sm">ITEMS</a></td>
                    <td><a href="<?=base_url('ingreso/subir/'.$value['id_nro_adquisicion'])?>" class="btn btn-primary btn-sm">SUBIR</a></td>
                    <td><button data-bs-toggle="modal" onclick="finalizar(<?=$value['id_nro_adquisicion']?>)" class="btn btn-secondary btn-sm">FINALIZAR</button></td>
                    <?php else:?>
                      <?php if((session()->isLoggedIn['nivel']==1) && ($value['estado_nro_adquisicion']!=2)):?>
                        <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_cambiar_estado" onclick="cambiar_estado(<?=$value['id_nro_adquisicion']?>)" class="btn btn-warning btn-sm">Cambiar estado</button></td>
                      <?php else:?>
                          <td></td>
                    <?php endif;?>
                    <td></td>
                    <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2)):?>
                      <td><a href="<?=base_url()?>ingresos/<?=$value['doc_upload']?>" target="_blank" download="respaldo_ingreso.pdf" class="btn btn-primary btn-sm">Descargar</a></td>
                    <?php else:?>
                      <td></td>
                    <?php endif;?>
                    <td><button onclick="window.open('<?=base_url()?>ingreso/imprimir_ingreso_materiales/<?=$value['id_nro_adquisicion']?>','Ingreso de materiales',parametrosPopPup)" id="btn_imprimir" class="btn btn-outline-primary btn-sm">Imprimir</button></td>
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
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">NUEVO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
            <form id="formNuevo" action="<?= base_url('ingreso/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
              <?= csrf_field() ?>
              <div class="row">
                <div class="col-md-6">
                  <label class="text-normal text-dark form-label">FECHA INGRESO:</label>
                  <input id="fecha_adquisicion" name="fecha_adquisicion" type="date" class="form-control">
                </div>
                <div class="col-md-6">
                  <label class="text-normal text-dark form-label">PROVEEDOR:</label>
                  <?php
                      $dataProveedor=array();
                      $dataProveedor[null]='Seleccione proveedor';
                      foreach ($proveedores as $key => $value)
                        $dataProveedor[$value['id_proveedor']] = $value['razon_social'];
                      
                        $jsProveedor="class='form-control' id='id_proveedor' required='required'";
                      if(!empty($dataProveedor))
                        echo form_dropdown('id_proveedor',$dataProveedor,'',$jsProveedor);
                      else echo '<div class="text-danger">No existen proveedores, necesita registrar antes.</div>';
                  ?>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">APERTURA PROGRAMÁTICA:</label>
                  <?php
                    if(is_null($dataAperturaGeneral)){
                      echo '<div class="alert alert-danger" role="alert">Error no existen aperturas en el sistema.</div>';
                    }else{
                      $dataApGral[null] = "Seleccione una apertura programatica";
                      foreach ($dataAperturaGeneral as $key => $value){
                        $dataApGral[$value['id_apertura']] = $value['codigo_apertura'].' - '.$value['descripcion_apertura'];
                      }
                      $jsApGral = 'id="id_apertura_general" class="form-control"';
                      echo form_dropdown('id_apertura_general',$dataApGral, '', $jsApGral);
                    }
                  ?>
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">SUB APERTURA:</label>
                  <div id="ajax_idsub_apertura">
                    <select class="form-control" id="id_sub_apertura" name="id_sub_apertura">
                      <option value="0">Sin sub apertura</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">HOJA DE RUTA:</label>
                  <input id="hoja_ruta" name="hoja_ruta" type="text" class="form-control" placeholder="SDAFP-2509/2024">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">TIPO DE DOCUMENTO:</label>
                  <?php
                    $jsTipoDoc = 'id="tipo_documento" class="form-control" ';
                    echo form_dropdown('tipo_documento', $tipo_doc,'', $jsTipoDoc)
                  ?>
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">N° DOCUMENTO:</label>
                  <input id="nro_tipo_documento" name="nro_tipo_documento" type="text" class="form-control" placeholder="SDAFP-2509/2024">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">DOCUMENTO DE CONSTANCIA:</label>
                  <?php
                      $documentoConstancia = documentoConstancia();
                      $jsTipoDoc="id='doc_constancia' class='form-control'";
                      echo form_dropdown('doc_constancia',$documentoConstancia,'',$jsTipoDoc);
                  ?>

                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">N° DOCUMENTO CONSTANCIA:</label>
                  <input id="nro_doc_constancia" name="nro_doc_constancia" type="text" class="form-control" placeholder="2102">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">TIPO ADQUISICIÓN:</label>
                  <?php
                      $jsTipo="id='tipo_adquisicion' class='form-control'";
                      echo form_dropdown('tipo_adquisicion',$dataTipo,'',$jsTipo);
                  ?>
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">OBSERVACIONES:</label>
                  <textarea class="form-control" name="observaciones" id="observaciones"></textarea>
                </div>
              </div>

              <div class="row">
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

<!-- Modal cambiar_estado -->
<div class="modal fade" id="modal_estatico_cambiar_estado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">CAMBIAR ESTADO</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="contenido_modal_cambiar_estado">Cargando...</div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal cambiar_estado -->

<script>
  
  function editar(id){
    $.get('<?=base_url()?>/ingreso/editar/'+id)
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

  function cambiar_estado(id){
    $.get('<?=base_url()?>ingreso/cambiar_estado/'+id)
      .done(function(data) {  
        $('#contenido_modal_cambiar_estado').html(data);
      })  
      .fail(function(xhr, status, error) {
        console.error(error);
      });  
  }    
  

  function finalizar(id){
    if(confirm("¿Esta seguro de finalizar y publicar este ingreso?")){
      location.href = "<?=base_url()?>/ingreso/finalizar/"+id;
    }
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#fecha_adquisicion', [
		{
		rule: 'required',
		}
	])
	.addField('#nro_tipo_documento', [
		{
		rule: 'required',
		}
	])
	.addField('#hoja_ruta', [
		{
		rule: 'required',
		}
	])
  .addField('#id_apertura_general', [
		{
		rule: 'required',
		}
	])
	.addField('#nro_doc_constancia', [
		{
		rule: 'required',
		}
	])
	.addField('#id_proveedor', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
