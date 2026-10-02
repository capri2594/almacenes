<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <?php  if(session()->isLoggedIn['nivel']==1):?>
      <div class="row">
        <div class="col-sm mt-2 d-grid"><a class="btn btn-primary mb-3" href="<?=base_url().'bodega/gestionarBodega/'.$bodega['id_bodega']?>">Volver a gestionar</a></div>
        <div class="col-sm mt-2 d-grid"></div>
        <div class="col-sm mt-2 d-grid"></div>
        <div class="col-sm mt-2 d-grid"></div>
      </div>
    <?php endif;?>
    <h4 class="c-grey-900 mT-10 mB-30">SOLICITUDES POR ATENDER</h4>

    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="toolbar-filtro">
          <div class="row align-items-center g-2">
            <div class="col-md-4">
              <span class="fw-bold text-dark"><i class="ti-home me-1"></i> <?=$bodega['nombre_bodega']?></span>
            </div>
            <div class="col-md-8">
              <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <select id="filtroEstadoAtender" class="form-select select-filtro-estado" style="max-width: 170px;">
                  <option value="">Todos los estados</option>
                  <option value="1">SOLICITADO</option>
                  <option value="2">APROBADO</option>
                  <option value="3">ATENDIDO</option>
                  <option value="4">DESPACHADO</option>
                </select>
                <div class="input-group input-busqueda-grupo" style="max-width: 320px;">
                  <span class="input-group-text"><i class="ti-search"></i></span>
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar por correlativo, glosa..." autocomplete="off" data-tabla="#tablaAtenderSolicitud" data-contador="#contadorAtenderSolicitud" data-select-estado="#filtroEstadoAtender" data-noresult="#sinResultadosAtenderSolicitud">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorAtenderSolicitud" class="badge-contador-tabla">Total: <?=count($ordenes)?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaAtenderSolicitud" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">CORRELATIVO</th>
                <th scope="col">BODEGA</th>
                <th scope="col">DATO DE LA CREACIÓN</th>
                <th scope="col">GLOSA</th>
                <th scope="col">ESTADO</th>
                <th scope="col">ATENDER</th>
                <th scope="col">DEVOLVER SOLICITUD</th>
                <th scope="col">DESPACHAR</th>
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
                <tr class="fila-datos" data-estado="<?=$value['estado_orden']?>">
                  <td><strong><?=$value['contador']?></strong></td>
                  <td><?=$bodega['nombre_bodega']?></td>
                  <td><?=$value['fecha_orden']?></td>
                  <td><?=$value['glosa']?></td>
                  <td>
                    <?php 
                      $badgeClass = 'bg-secondary';
                      if ($value['estado_orden'] == 1) $badgeClass = 'bg-info text-dark';
                      else if ($value['estado_orden'] == 2) $badgeClass = 'bg-warning text-dark';
                      else if ($value['estado_orden'] == 3) $badgeClass = 'bg-primary text-white';
                      else if ($value['estado_orden'] == 4) $badgeClass = 'bg-success text-white';
                    ?>
                    <span class="badge <?=$badgeClass?>"><?=$estados_orden[$value['estado_orden']]?></span>
                  </td>
                  <?php
                    if (($bodega['atender_solicitud'] == 1) || (session()->isLoggedIn['nivel']==1)){
                        if($value['estado_orden']==2){
                          echo '
                            <td><a href="'.base_url('orden/atender/'.$value['id_orden']).'" class="btn btn-success btn-sm">ATENDER</a></td>
                            <td><a href="javascript:devolver('.$value['id_orden'].')" class="btn btn-warning btn-sm text-dark">DEVOLVER SOLICITUD</a></td>
                            ';
                        }
                        else 
                          echo '<td></td>';                        
                        if($value['estado_orden']==3){
                          echo '
                          <td></td>
                          <td><a href="javascript:despachar('.$value['id_orden'].')" class="btn btn-danger btn-sm text-dark">DESPACHAR</a></td>
                        ';
                        }
                        else 
                          echo '<td></td><td></td>';
                    }else{
                      echo '
                      <td></td>
                      <td></td>';
                      if($value['estado_orden']==3){
                        echo '<td><a href="javascript:despachar('.$value['id_orden'].')" class="btn btn-danger btn-sm text-dark">DESPACHAR</a></td>';
                      }
                      else 
                        echo '<td></td>';
                    }
                  ?>
                  
                </tr>
              <?php endforeach?>
              <tr id="sinResultadosAtenderSolicitud" style="display:none;">
                <td colspan="8" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron solicitudes que coincidan con la búsqueda.
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
        <div class="container">
            <form id="formNuevo" action="<?= base_url('orden/crear') ?>" method="post" autocomplete="off" novalidate="novalidate">
              <?= csrf_field() ?>
              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">MOTIVO (GLOSA):</label>
                  <textarea class="form-control" name="glosa" id="glosa"></textarea>
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
  
  function devolver(id){
    if(confirm("Esta seguro de devolver la solicitud con id = " + id)){
      location.href = "<?php echo base_url().'orden/devolver_solicitud/'?>"+id
    }
  }
  
  function despachar(id){
    if(confirm("Esta seguro de despachar la solicitud con id = " + id)){
      location.href = "<?php echo base_url().'orden/despachar/'?>"+id
    }
  }
  
  
</script>

<?= $this->endSection();?>
