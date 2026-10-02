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
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('orden/')?>'">Volver a mis solicitudes</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

    <h4 class="c-grey-900 mT-10 mB-30">CÓDIGO SABS: <?=$id_orden?></h4>
    <div class="row">
      <div class="col-sm mt-2 d-grid">
        <!-- <select id="producto_elegido" name="producto_elegido" class="form-control"> -->
          <?php
            use App\Models\ProductoModel;
            $dataProd  = array();
            foreach ($adq_prod_tiene as $key => $value) {
              $objProducto = new ProductoModel();
              $producto = $objProducto->getProducto($value['id_producto']);
              $dataProd[$value['id_adquisicion_producto_tiene_app']] = $producto['nombre_producto'];
            }
            $jsProd='class="form-control" id="producto_elegido"';
            echo form_dropdown('producto_elegido',$dataProd,'',$jsProd);
          ?>
        <!-- </select>         -->
      </div>
      <div class="col-sm mt-2 d-grid">
        <button data-bs-toggle="modal" data-bs-target="#modal_add_producto" onclick="agregarProducto()" id="btn_seleccionar" class="btn btn-success">Agregar a la lista</button>
      </div>
      <div class="col-sm mt-2 d-grid">
        
      </div>
    </div>

    <h4 class="c-grey-900 mt-3">ITEMS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">

          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">ID SABS</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">CANT. REQUERIDA</th>
                <th scope="col">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
            <?php
              
              $estados = estados_acceso();
              $i=1;
              $total = 0;
              
              foreach ($items_orden as $key => $value):
                $objProductoItem = new ProductoModel();
                //$producto = $objProductoItem->getProducto($value['id_producto']);
  
                $prod = $objProductoItem->find($value['id_producto']);
                $total+=$value['cant_requerida'];
              ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$id_orden;?></td>
                  <td><?=$prod['nombre_producto'];?></td>
                  <td class="text-end"><?=number_format($value['cant_requerida'],2,',','.');?></td>
                  <?php if(session()->isLoggedIn['estado_recurso']==1):?>
                    <td><button onclick="eliminar(<?=$value['id_items_orden']?>)" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                  <?php else:?>
                    <td></td>
                  <?php endif;?>
                </tr>
              <?php endforeach?>
                <tr>
                  <th class="text-primary font-weight-bold" colspan="3">TOTAL ITEMS</th>
                  <th class="text-end text-primary font-weight-bold"><?=number_format($total,2,',','.');?></th>
                  <th></th>
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
        <h5 class="modal-title">AGREGAR ÍTEM</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="contenido_modal_add">Cargando...</div>
      </div>
    </div>
  </div>
</div><!-- Fin Modal -->

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

</main>
<script>
  function agregarProducto(){
    let producto = $("#producto_elegido").select2('data');
    let ruta = '<?=base_url()?>items_orden_cargar/'+<?=$id_orden?>+'/'+producto[0].id;
    $.get(ruta)
      .done(function(data) {  
        $('#contenido_modal_add').html(data);
      })
      .fail(function(xhr, status, error) {
        console.error(error);
      });

  }

  function eliminar(id){
    if(confirm('Esta seguro de eliminar este item?')){
      location.href = '<?=base_url()?>items_orden/eliminar_item/'+id
    }
  }

</script>

<?= $this->endSection();?>
