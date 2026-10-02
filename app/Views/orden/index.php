<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">MIS SOLICITUDES</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <style>
          .toolbar-filtro {
            background: #f8fafc;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 22px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
          }
          .input-busqueda-grupo {
            border: 1.5px solid #64748b !important;
            border-radius: 8px !important;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            transition: all 0.2s ease-in-out;
          }
          .input-busqueda-grupo:focus-within {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.18) !important;
          }
          .input-busqueda-grupo .input-group-text {
            background: #f8fafc !important;
            border: none !important;
            color: #475569 !important;
            font-size: 15px;
            padding: 0 12px;
          }
          .input-busqueda-grupo .form-control {
            border: none !important;
            box-shadow: none !important;
            font-size: 14px !important;
            color: #1e293b !important;
            padding: 8px 12px;
            height: 40px;
          }
          .input-busqueda-grupo .btn-limpiar {
            border: none !important;
            border-left: 1px solid #cbd5e1 !important;
            background: #fff;
            color: #64748b;
            padding: 0 14px;
            font-size: 14px;
            transition: all 0.15s ease;
          }
          .input-busqueda-grupo .btn-limpiar:hover {
            background: #fee2e2;
            color: #dc2626;
          }
          .select-filtro-estado {
            border: 1.5px solid #64748b !important;
            border-radius: 8px !important;
            background-color: #fff !important;
            color: #1e293b !important;
            font-weight: 500 !important;
            font-size: 14px !important;
            height: 42px !important;
            padding: 0 14px !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
            transition: all 0.2s ease-in-out !important;
          }
          .select-filtro-estado:focus {
            border-color: #0d6efd !important;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.18) !important;
          }
          .badge-contador-ordenes {
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px;
            font-size: 13.5px;
            font-weight: 700;
            border-radius: 8px;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: #ffffff;
            border: 1.5px solid #0a58ca;
            box-shadow: 0 2px 5px rgba(13,110,253,0.25);
            white-space: nowrap;
            letter-spacing: 0.3px;
          }
          .btn-nuevo-solicitud {
            height: 42px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            padding: 0 18px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1.5px solid #0a58ca;
            background: #0d6efd;
            color: #fff !important;
            box-shadow: 0 2px 4px rgba(13,110,253,0.2);
            transition: all 0.15s ease;
          }
          .btn-nuevo-solicitud:hover {
            background: #0b5ed7;
            border-color: #084298;
          }
          #tablaOrdenes {
            border: 1.5px solid #cbd5e1;
          }
          #tablaOrdenes thead th {
            background-color: #f1f5f9;
            color: #1e293b;
            border-bottom: 2px solid #64748b;
            font-size: 13px;
            font-weight: 700;
            vertical-align: middle;
          }
          #tablaOrdenes tbody td {
            vertical-align: middle;
            border-color: #e2e8f0;
          }
        </style>

        <div class="toolbar-filtro">
          <div class="row align-items-center g-2">
            <div class="col-md-3">
              <?php if(session()->isLoggedIn['estado_recurso']==1):?>
                <button type="button" class="btn btn-nuevo-solicitud" data-bs-toggle="modal" data-bs-target="#modal_estatico"><i class="ti-plus"></i> NUEVA SOLICITUD</button>
              <?php endif;?>
            </div>
            <div class="col-md-9">
              <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <div class="input-group input-busqueda-grupo" style="max-width: 420px;">
                  <span class="input-group-text"><i class="ti-search"></i></span>
                  <input type="text" id="filtroTabla" class="form-control" placeholder="Buscar por bodega, glosa, estado..." autocomplete="off">
                  <button class="btn btn-limpiar" type="button" id="btnLimpiarFiltro" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <select id="filtroEstado" class="form-select select-filtro-estado" style="max-width: 185px;">
                  <option value="">Todos los estados</option>
                  <option value="GENERADO">GENERADO</option>
                  <option value="SOLICITADO">SOLICITADO</option>
                  <option value="APROBADO">APROBADO</option>
                  <option value="ATENDIDO">ATENDIDO</option>
                  <option value="ANULADO">ANULADO</option>
                </select>
                <span id="contadorFilas" class="badge-contador-ordenes">Total: <?=count($ordenes)?></span>
              </div>
            </div>
          </div>
        </div>
        <?php if (session()->getFlashdata('message')): ?>
          <div class="alert alert-danger">
            <?= session()->getFlashdata('message') ?>
          </div>
        <?php endif; ?>

          <table id="tablaOrdenes" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">BODEGA</th>
                <th scope="col">DATO DE LA CREACIÓN</th>
                <th scope="col">GLOSA</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
                <th scope="col">ITEMS</th>
                <th scope="col">ENVIAR SOLICITUD</th>
                <th scope="col">IMPRIMIR</th>
                <th scope="col">ELIMINAR</th>
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
                  $txt_estado = '';
                  switch ($value['estado_orden']) {
                    case 1: $txt_estado = 'GENERADO'; break;
                    case 2: $txt_estado = 'SOLICITADO'; break;
                    case 3: $txt_estado = 'APROBADO'; break;
                    case 4: $txt_estado = 'ATENDIDO'; break;
                    case 5: $txt_estado = 'ANULADO'; break;
                  }
              ?>
                <tr class="fila-orden" data-estado="<?=$txt_estado?>" data-id="<?=$value['id_orden']?>">
                  <td><?=$i++?></td>
                  <td><?=$bodega['nombre_bodega']?></td>
                  <td><?=date('d/m/Y H:i:s', strtotime($value['fecha_orden']))?></td>
                  <td><?=$value['glosa']?></td>
                  <td>
                    <?php
                    switch ($value['estado_orden']) {
                      case 1: echo '<span class="badge bg-secondary">GENERADO</span>'; break;
                      case 2: echo '<span class="badge bg-warning text-dark">SOLICITADO</span>'; break;
                      case 3: echo '<span class="badge bg-info text-dark">APROBADO</span>'; break;
                      case 4: echo '<span class="badge bg-success">ATENDIDO</span>'; break;
                      case 5: echo '<span class="badge bg-danger">ANULADO</span>'; break;
                      default: echo '<span class="badge bg-light text-dark">OTRO</span>'; break;
                    }
                    ?>
                  </td>
                  <?php
                  $id_orden = $value['id_orden'];
                  switch ($value['estado_orden']) {
                    case 1:
                      echo '
                        <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar('.$value['id_orden'].')" class="btn btn-warning btn-sm">EDITAR</button></td>
                        <td><a href="'.base_url('items_orden/'.$value['id_orden']).'" class="btn btn-success btn-sm">ITEMS</a></td>
                        <td><button onclick="enviar('.$value['id_orden'].')" class="btn btn-primary btn-sm">ENVIAR</button></td>
                        <td></td>
                        <td><button onclick="eliminar('.$value['id_orden'].')" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                      ';
                      break;
                      case 2:
                        echo '
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        ';
                        break;                     
                        case 3:
                          echo '
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><a href="javascript:window.open(`'.base_url("orden/imprimir_solicitud_aprobada/").$id_orden.'`,`Salida`,`parametrosPopPup`)" class="btn btn-primary btn-sm text-white">Imprimir</a></td>
                            <td></td>
                          ';
                          break;                     
                          case 4:
                            echo '
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><a href="javascript:window.open(`'.base_url("orden/imprimir_solicitud_aprobada/").$id_orden.'`,`Salida`,`parametrosPopPup`)" class="btn btn-primary btn-sm text-white">Imprimir</a></td>
                              <td></td>
                            ';
                            break;                     
                          case 5:
                            echo '
                              <td></td>
                              <td></td>
                              <td></td>
                              <td><a href="javascript:window.open(`'.base_url("orden/imprimir_solicitud_aprobada/").$id_orden.'`,`Salida`,`parametrosPopPup`)" class="btn btn-primary btn-sm text-white">Imprimir</a></td>
                              <td></td>
                            ';
                            break;               
                        }?>
                </tr>
              <?php endforeach?>
              <tr id="sinResultados" style="display:none;">
                <td colspan="10" class="text-center py-4 text-muted">
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
                  <label class="text-normal text-dark form-label">OBJETO:</label>
                  <input class="form-control" type="text" id="obj_glosa" name="obj_glosa" placeholder="Material para 5 personas / 123-abc (placa)">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">JUSTIFICACIÓN:</label>
                  <textarea class="form-control" name="glosa" id="glosa"></textarea>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">CELULAR / TELÉFONO DE CONTACTO:</label>
                  <input class="form-control" type="text" id="celular" name="celular" value="<?= esc($recurso_actual['celular'] ?? '') ?>" placeholder="Ej: 71234567">
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mt-3">
                  <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
                  <?php
                    $dataBodegas[null] = 'Seleccione una bodega';
                    foreach ($us_bo as $key => $value){
                      $miBodega2 = new BodegasModel();
                      $bodega2 = $miBodega2->getBodega($value['id_bodega']);
                      $dataBodegas[$value['id_bodega']] = $bodega2['nombre_bodega'];
                    } 
                    $js='id="id_bodega" class="form-control" ';
                    echo form_dropdown('id_bodega',$dataBodegas, '', $js);
                  ?>
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
  
  function editar(id){
    $.get('<?=base_url()?>/orden/editar/'+id)
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

  function enviar(id_orden){
    if(confirm('¿Esta seguro de enviar la orden con id = '+id_orden+' ?')){
      location.href = '<?php echo base_url()?>/orden/enviar_orden/'+id_orden;
    }
  }

  function eliminar(id_orden){
    if(confirm('¿Esta seguro de eliminar la orden con id = '+id_orden+' ?')){
      location.href = '<?php echo base_url()?>/orden/eliminar_orden/'+id_orden;
    }
  }

	const validator = new JustValidate('#formNuevo');
	validator
	.addField('#glosa', [
		{
		rule: 'required',
		}
	])
	.addField('#obj_glosa', [
		{
		rule: 'required',
		}
	])
	.addField('#id_bodega', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});

  // --- Filtro dinámico en tiempo real (Opción B) ---
  $(document).ready(function() {
    function filtrarTabla() {
      let texto = ($('#filtroTabla').val() || '').toLowerCase().trim();
      let estado = ($('#filtroEstado').val() || '').toLowerCase().trim();
      let total = $('#tablaOrdenes tbody tr.fila-orden').length;
      let visibles = 0;

      $('#tablaOrdenes tbody tr.fila-orden').each(function() {
        let fila = $(this);
        let contenido = fila.text().toLowerCase();
        let filaEstado = (fila.data('estado') || '').toString().toLowerCase();

        let coincideTexto = (texto === '' || contenido.indexOf(texto) > -1);
        let coincideEstado = (estado === '' || filaEstado.indexOf(estado) > -1);

        if (coincideTexto && coincideEstado) {
          fila.show();
          visibles++;
        } else {
          fila.hide();
        }
      });

      if (visibles === 0 && total > 0) {
        $('#sinResultados').show();
      } else {
        $('#sinResultados').hide();
      }

      if (texto !== '' || estado !== '') {
        $('#contadorFilas').text('Filtrados: ' + visibles + ' de ' + total);
      } else {
        $('#contadorFilas').text('Total: ' + total);
      }
    }

    $('#filtroTabla').on('keyup input', filtrarTabla);
    $('#filtroEstado').on('change', filtrarTabla);

    $('#btnLimpiarFiltro').on('click', function() {
      $('#filtroTabla').val('');
      $('#filtroEstado').val('');
      filtrarTabla();
      $('#filtroTabla').focus();
    });
  });

</script>

<?= $this->endSection();?>
