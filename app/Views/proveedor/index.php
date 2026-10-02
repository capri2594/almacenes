<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('bodega/gestionarBodega/'.$id_bodega)?>'">Volver a gestionar</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE PROVEEDORES</h4>
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
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar por razón social, NIT, teléfono..." autocomplete="off" data-tabla="#tablaProveedores" data-contador="#contadorProveedores" data-noresult="#sinResultadosProveedores">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorProveedores" class="badge-contador-tabla">Total: <?=count($proveedores)?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaProveedores" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">RAZÓN SOCIAL</th>
                <th scope="col">CI/NIT</th>
                <th scope="col">TIPO</th>
                <th scope="col">TELF/CEL</th>
                <th scope="col">DIRECCIÓN</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
                <th scope="col">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $estados = estados_acceso();
              $tiposProveedores = tiposProveedores();
              $i=1;
              foreach ($proveedores as $key => $value): ?>
                <tr class="fila-datos">
                  <td><?=$i++;?></td>
                  <td><strong><?=$value['razon_social']?></strong></td>
                  <td><?=$value['ci_nit']?></td>
                  <td><?=$tiposProveedores[$value['tipo_proveedor']]?></td>
                  <td><?=$value['telefono_proveedor']?></td>
                  <td><?=$value['direccion_proveedor']?></td>
                  <td><?=$estados[$value['estado_proveedor']]?></td>
                  <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1) && ($value['id_proveedor']!=1) ):?>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar(<?=$value['id_proveedor']?>)" class="btn btn-warning btn-sm">EDITAR</button></td>
                    <td><button onclick="eliminar(<?=$value['id_proveedor']?>)" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                  <?php else:?>
                    <td></td>
                    <td></td>
                  <?php endif;?>
                </tr>
              <?php endforeach?>
              <tr id="sinResultadosProveedores" style="display:none;">
                <td colspan="9" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron proveedores que coincidan con la búsqueda.
                </td>
              </tr>
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
            <form id="formNuevo" action="<?= base_url('proveedor/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
              <?= csrf_field() ?>
              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">RAZÓN SOCIAL:</label>
                  <input id="razon_social" name="razon_social" type="text" class="form-control" placeholder="CARTONBOL">
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">CI/NIT:</label>
                  <input id="ci_nit" name="ci_nit" type="text" class="form-control" placeholder="1245784010">
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">DIRECCIÓN:</label>
                  <input id="direccion_proveedor" name="direccion_proveedor" type="text" class="form-control" placeholder="AV. 24 DE JULIO">
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">TELF/CEL:</label>
                  <input id="telefono_proveedor" name="telefono_proveedor" type="text" class="form-control" placeholder="25212345 - 77112345">
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">TIPO:</label>
                  <?php
                    $jsProveedor="class='form-control'";
                    echo form_dropdown('tipo_proveedor',$tiposProveedores,'',$jsProveedor);
                  ?>
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">NOTA/OBS:</label>
                  <input id="nota_proveedor" name="nota_proveedor" type="text" class="form-control" placeholder="EMPRESA DE CONFIANZA">
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">PERSONA CONTACTO:</label>
                  <input id="nombre_contacto" name="nombre_contacto" type="text" class="form-control" placeholder="JUAN PEREZ">
                </div>
                <div class="col-md-6 mt-3">
                  <label class="text-normal text-dark form-label">CEL/CONTACTO:</label>
                  <input id="celular_contacto" name="celular_contacto" type="text" class="form-control" placeholder="66112345">
                </div>
              </div>
              
              <div class="row mt-3">
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

<script>
  
  function editar(id){
    $.get('<?=base_url()?>/proveedor/editar/'+id)
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
    if(confirm("¿Esta seguro de eliminar este ítem?"))
      location.href="<?=base_url()?>proveedor/eliminar/"+id;
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#razon_social', [
		{
		rule: 'required',
		}
	])
	.addField('#ci_nit', [
		{
		rule: 'required',
		}
	])
	.addField('#direccion_proveedor', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
  
</script>

<?= $this->endSection();?>
