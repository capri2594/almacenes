<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
	<main class="main-content bgc-grey-100">    
    <h4 class="mt-3">REPORTES DISPONIBLES</h4>
      <div class="container">
        <div class="row">
          <div class="col-sm mt-2 d-grid">
            <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico2">kardex</button>
          </div>
          <div class="col-sm mt-2 d-grid">
            <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_estatico3">Consolidado</button>
          </div>
          <div class="col-sm mt-2 d-grid">
            <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_catalogo">Catalogo</button>
          </div>
        </div>
        <div class="row">
          <div class="col-sm mt-2 d-grid">
            <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_kardex_insumos">Kardex de insumos (valorado)</button>
          </div>
          <div class="col-sm mt-2 d-grid">
            <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_inventario_inicial">Inventario inicial</button>
          </div>
          <div class="col-sm mt-2 d-grid">
            <a href="<?= base_url('reporte/r5_r6') ?>" class="btn cur-p btn-primary btn-color" target="_blank">R5 & R6</a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm mt-2 d-grid">
            <button type="button" class="btn cur-p btn-primary btn-color" data-bs-toggle="modal" data-bs-target="#modal_actualizacion_saldo">Actualización de saldos</button>
          </div>
          <div class="col-sm mt-2 d-grid">
            <a href="<?= base_url('reporte/kardex_final') ?>" class="btn cur-p btn-primary btn-color" target="_blank">Kardex final</a>
          </div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>
      </div>
  </main>

<!-- Modal nuevo -->
<div class="modal fade" id="modal_estatico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">STOCK ACTUAL</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario" action="<?= base_url('reporte/stock_actual') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-control"';
              echo form_dropdown('id_bodega', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<!-- Modal nuevo -->
<div class="modal fade" id="modal_estatico2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">KARDEX</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_kardex" action="<?= base_url('reporte/kardex') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-control"';
              echo form_dropdown('id_bodega_kardex', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<!-- Modal nuevo -->
<div class="modal fade" id="modal_estatico3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">GENERAR CONSOLIDADO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_consolidado" action="<?= base_url('reporte/consolidado') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-select"';
              echo form_dropdown('id_bodega_consolidado', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE MES:</label>
            <?php
              $dataMesConsolidado = array();
              $dataMesConsolidado[0] = 'ENERO';
              $dataMesConsolidado[1] = 'FEBRERO';
              $dataMesConsolidado[2] = 'MARZO';
              $dataMesConsolidado[3] = 'ABRIL';
              $dataMesConsolidado[4] = 'MAYO';
              $dataMesConsolidado[5] = 'JUNIO';
              $dataMesConsolidado[6] = 'JULIO';
              $dataMesConsolidado[7] = 'AGOSTO';
              $dataMesConsolidado[8] = 'SEPTIEMBRE';
              $dataMesConsolidado[9] = 'OCTUBRE';
              $dataMesConsolidado[10] = 'NOVIEMBRE';
              $dataMesConsolidado[11] = 'DICIEMBRE';
            
            $jsMesConsolidado = 'class="form-control"';
            echo form_dropdown('mes_consolidado', $dataMesConsolidado, '', $jsMesConsolidado);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<!-- Modal nuevo -->
<div class="modal fade" id="modal_catalogo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">CATALOGO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_catalogo" action="<?= base_url('reporte/catalogo') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-control"';
              echo form_dropdown('id_bodega_catalogo', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<!-- Modal inventario inicial -->
<div class="modal fade" id="modal_inventario_inicial" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">INVENTARIO INICIAL</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_inventario_inicial" action="<?= base_url('reporte/inventario_inicial') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-control"';
              echo form_dropdown('id_bodega_inventario_inicial', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal inventario inicial -->

<!-- Modal inventario inicial -->
<div class="modal fade" id="modal_actualizacion_saldo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">ACTUALIZACIÓN DE SALDOS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_actualizacion_saldo" action="<?= base_url('reporte/actualizacion_saldo') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-control"';
              echo form_dropdown('id_bodega_actualizacion_saldo', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal inventario inicial -->


<!-- Modal kardex insumos fisico-->
<div class="modal fade" id="modal_kardex_insumos_fisico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">KARDEX INSUMOS FISICO</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_kardex_insumos_fisico" action="<?= base_url('reporte/kardex_insumos_fisico') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE MES:</label>
            <?php
            $dataMes = array();
              $dataMes[0] = 'ENERO';
              $dataMes[1] = 'FEBRERO';
              $dataMes[2] = 'MARZO';
              $dataMes[3] = 'ABRIL';
              $dataMes[4] = 'MAYO';
              $dataMes[5] = 'JUNIO';
              $dataMes[6] = 'JULIO';
              $dataMes[7] = 'AGOSTO';
              $dataMes[8] = 'SEPTIEMBRE';
              $dataMes[9] = 'OCTUBRE';
              $dataMes[10] = 'NOVIEMBRE';
              $dataMes[11] = 'DICIEMBRE';
            
            $jsMes = 'class="form-control"';
            echo form_dropdown('mes_kardex_insumos', $dataMes, '', $jsMes);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal kardex insumos fisico-->

<!-- Modal kardex insumos -->
<div class="modal fade" id="modal_kardex_insumos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">KARDEX INSUMOS</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_kardex_insumos" action="<?= base_url('reporte/kardex_insumos') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE MES:</label>
            <?php
            $dataMes = array();
              $dataMes[0] = 'ENERO';
              $dataMes[1] = 'FEBRERO';
              $dataMes[2] = 'MARZO';
              $dataMes[3] = 'ABRIL';
              $dataMes[4] = 'MAYO';
              $dataMes[5] = 'JUNIO';
              $dataMes[6] = 'JULIO';
              $dataMes[7] = 'AGOSTO';
              $dataMes[8] = 'SEPTIEMBRE';
              $dataMes[9] = 'OCTUBRE';
              $dataMes[10] = 'NOVIEMBRE';
              $dataMes[11] = 'DICIEMBRE';
            
            $jsMes = 'class="form-control"';
            echo form_dropdown('mes_kardex_insumos', $dataMes, '', $jsMes);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal kardex insumos -->

<!-- Modal R6 -->
<div class="modal fade" id="modal_r6" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">R6</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formulario_r6" action="<?= base_url('reporte/r6') ?>" method="get" target="_blank" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">SELECCIONE BODEGA:</label>
            <?php
            foreach ($bodegas as $key => $value)
                $dataBodega[$value['id_bodega']] = $value['nombre_bodega'];
              $jsBodega = 'class="form-control"';
              echo form_dropdown('id_bodega_r6', $dataBodega, '', $jsBodega);
            ?>
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">CONSULTAR</button>
                <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div><!-- Fin Modal nuevo -->

<script>
    let modalNuevo = document.getElementById('modal_estatico')
    modalNuevo.addEventListener('hidden.bs.modal', function (event) {
      let formulario = document.getElementById('formulario')
      formulario.reset();
    });

    $("#formulario").submit(function (e) {
    location.href = '<?=base_url("reporte")?>';
  });
  
  $("#formulario_kardex").submit(function (e) {
    location.href = '<?=base_url("reporte")?>';
  });
  
  $("#formulario_consolidado").submit(function (e) {
    let fecha_inicial = $("#fecha_inicial").val();
    let fecha_final = $("#fecha_final").val();
    if(fecha_inicial == '' || fecha_final == ''){
      alert('Debe seleccionar una fecha inicial y una fecha final');
      return false;
    }
    location.href = '<?=base_url("reporte")?>';
  });
  
  $("#formulario_consolidado_fisico").submit(function (e) {
    location.href = '<?=base_url("reporte")?>';
  });
  
</script>
<?= $this->endSection();?>
