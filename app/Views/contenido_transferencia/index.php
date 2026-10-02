<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<script>
 $(document).ready(function() {
    $('#producto_elegido').select2();
});

</script>

<main class="main-content bgc-grey-100">
  <div class="container-fluid">
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('transferencia/'.$transferencia['id_bodega'])?>'">Volver a lista de transferencias</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

    <h4 class="c-grey-900 mT-10 mB-30">CONTENIDO DE LA TRANSFERENCIA</h4>
    <div class="row">
      <div class="col-md d-grid">
        <?php
          use App\Models\AperturasModel;
          use App\Models\SubAperturasModel;
          
          $objApp = new AperturasModel();
          $app = $objApp->getApertura($transferencia['id_apertura']);
          $app_destino = $objApp->getApertura($transferencia['id_apertura_destino']);

          $objSubApp = new SubAperturasModel();
          $sub_app = $objSubApp->getSubApertura($transferencia['id_sub_apertura']);
          $sub_app_txt = (is_null($sub_app))?'Sin sub apertura':($sub_app['codigo_sub_apertura'].' - '.$sub_app['descripcion_sub_apertura']);

          $sub_app_destino = $objSubApp->getSubApertura($transferencia['id_sub_apertura_destino']);
          $sub_app_destino_txt = (is_null($sub_app_destino))?'Sin sub apertura':($sub_app_destino['codigo_sub_apertura'].' - '.$sub_app_destino['descripcion_sub_apertura']);

          echo '<span style="color: #558ccf">DE: '.$app['codigo_apertura'].' - '.$app['descripcion_apertura'].' -> '.$sub_app_txt. '</span>';
          echo '<span style="color: #2c9c31">A: '.$app_destino['codigo_apertura'].' - '.$app_destino['descripcion_apertura'].' -> '.$sub_app_destino_txt. '</span>';
        ?>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-sm mt-2 d-grid">
          <?php
            use App\Models\ProductoModel;

            foreach ($adq_prod_tiene as $key => $value) {
              $objProducto = new ProductoModel();
              $producto = $objProducto->getProducto($value['id_producto']);
              $dataProd[$value['id_adquisicion_producto_tiene_app']] = $producto['nombre_producto'];
            }
            $jsProd='class="form-control" id="producto_elegido"';
            echo form_dropdown('producto_elegido',$dataProd,'',$jsProd);
          ?>
      </div>
      <div class="col-sm mt-2 d-grid">
        <a data-bs-toggle="modal" data-bs-target="#modal_add_producto" onclick="agregarProducto()" id="btn_seleccionar" class="btn btn-primary">Agregar a la lista de transferencia</a>
      </div>
      <div class="col-sm mt-2 d-grid">
        <?php if($transferencia['estado_transferencia'] == 1): ?>
          <a onclick="ejecutarTransferencia(<?=$transferencia['id_transferencia']?>)" class="btn btn-success">Ejecutar transferencia</a>
        <?php endif; ?>
      </div>
    </div>

    <h4 class="c-grey-900 mt-3">ITEMS A TRANSFERIR</h4>
    <?php if (session()->getFlashdata('item_existente')): ?>
      <div class="alert alert-danger">
        <?= session()->getFlashdata('item_existente') ?>
      </div>
    <?php endif; ?>
    
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">

        <div class="toolbar-filtro">
          <div class="row align-items-center g-2">
            <div class="col-md-6">
              <span class="fw-bold text-dark"><i class="ti-list me-1"></i> ITEMS A TRANSFERIR</span>
            </div>
            <div class="col-md-6">
              <div class="d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <div class="input-group input-busqueda-grupo" style="max-width: 350px;">
                  <span class="input-group-text"><i class="ti-search"></i></span>
                  <input type="text" class="form-control filtro-dinamico-auto" placeholder="Buscar ítem..." autocomplete="off" data-tabla="#tablaContenidoTransf" data-contador="#contadorContenidoTransf" data-noresult="#sinResultadosContenidoTransf">
                  <button class="btn btn-limpiar" type="button" title="Limpiar"><i class="ti-close"></i></button>
                </div>
                <span id="contadorContenidoTransf" class="badge-contador-tabla">Total: <?=count($items_transferencia)?></span>
              </div>
            </div>
          </div>
        </div>

          <table id="tablaContenidoTransf" class="table table-striped table-bordered table-hover tabla-dinamica">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">PRODUCTO</th>
                <th scope="col" class="text-end">CANT. A TRANSFERIR</th>
                <th scope="col" class="text-end">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                $html='';
                foreach ($items_transferencia as $key => $item){
                  $objProducto2 = new ProductoModel();
                  $producto_item = $objProducto2->getProducto($item['id_producto']);

                  $html.='<tr class="fila-datos">';
                  $html.='<td>'.($i++).'</td>';
                  $html.='<td>'.$producto_item['nombre_producto'].'</td>';
                  $html.='<td class="text-end">'.$item['cantidad_transferencia'].'</td>';
                  if($transferencia['estado_transferencia'] == 1){
                    $html.='<td class="text-end"><a type="button" onclick="eliminar('.$item['id_contenido_transferencia'].')" class="btn btn-danger btn-sm">Eliminar</a></td>';
                  }else{
                    $html.='<td class="text-end"></td>';
                  }
                  $html.='</tr>';
                }
                echo $html;
              ?>
              <tr id="sinResultadosContenidoTransf" style="display:none;">
                <td colspan="4" class="text-center py-4 text-muted">
                  <i class="ti-info-alt me-1"></i> No se encontraron ítems que coincidan con la búsqueda.
                </td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
    
  </div>

<!-- Modal add prod -->
<div class="modal fade" id="modal_add_producto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">ÍTEM A TRANSFERIR</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="contenido_modal_add">Cargando...</div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal -->

</main>
<script>
  function agregarProducto(){
    let producto = $("#producto_elegido").select2('data');
    
    let ruta = '<?=base_url()?>contenido_transferencia/'+<?php echo $transferencia['id_transferencia']?>+'/'+producto[0].id;
    $.get(ruta)
      .done(function(data) {  
        $('#contenido_modal_add').html(data);
      })
      .fail(function(xhr, status, error) {
        console.error(error);
      });

  }

  function ejecutarTransferencia(id_transferencia){
    if(confirm('Ejecutar Transferencia')){
      location.href = '<?=base_url()?>transferencia/ejecutar_transferencia/'+id_transferencia;
    }

  }

  function eliminar(id){
    if(confirm('Esta seguro de quitar este item de la lista?')){
      location.href = '<?=base_url()?>contenido_transferencia/eliminar_item/'+id
    }
  }

</script>

<?= $this->endSection();?>
