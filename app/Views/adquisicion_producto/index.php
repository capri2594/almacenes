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
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('ingreso/'.$id_bodega)?>'">Volver a lista de ingresos</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

    <h4 class="c-grey-900 mT-10 mB-30"><?=$bodega['nombre_bodega']?> - N° DE INGRESO: <?=$nro_adquisicion['nro_correlativo']?></h4>
    <div class="row">
      <div class="col-sm mt-2 d-grid">
        <select id="producto_elegido" name="producto_elegido" class="form-control">
          <?php
          foreach ($productos as $key => $value):
          ?>
          <option value="<?=$value['id_producto']?>"><?=$value['nombre_producto']?></option>
          <?php endforeach;?>
        </select>        
      </div>
      <div class="col-sm mt-2 d-grid">
        <button data-bs-toggle="modal" data-bs-target="#modal_add_producto" onclick="agregarProducto()" id="btn_seleccionar" class="btn btn-success">Agregar a la lista</button>
      </div>
      <div class="col-sm mt-2 d-grid">
        <button onclick="window.open('<?=base_url()?>ingreso/imprimir_ingreso_materiales/<?=$nro_adquisicion['id_nro_adquisicion']?>','Ingreso de materiales',parametrosPopPup)" id="btn_imprimir" class="btn btn-primary">Imprimir</button>
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
                <th scope="col">CODIGO</th>
                <th scope="col">PARTIDA</th>
                <th scope="col">DESCRIPCIÓN</th>
                <th scope="col">CANT. INGRESO</th>
                <th scope="col">PRECIO UNIT.</th>
                <th scope="col">SUB TOTAL</th>
                <th scope="col">EDITAR</th>
                <th scope="col">ELIMINAR</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $estados = estados_acceso();
              $i=1;
              $total = 0;
              foreach ($itemsAdquisicion as $key => $value):
                $prod = $miProducto->find($value['id_producto']);
              ?>
                <tr>
                  <td><?=$i++;?></td>
                  <td><?=$prod['codigo'];?></td>
                  <td><?=$prod['id_partida'];?></td>
                  <td><?=$prod['nombre_producto'];?></td>
                  <td class="text-end"><?=number_format($value['cant_ingreso'],2,',','.');?></td>
                  <td class="text-end"><?=number_format($value['precio_adquisicion'],2,',','.');?></td>
                  <td class="text-end"><?php
                    $sub = round(($value['cant_ingreso'] * $value['precio_adquisicion']),2);
                    $total+=$sub; 
                    echo number_format($sub, 2, ',', '.');?>
                  </td>
                  <?php if((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1)):?>
                    <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar(<?=$value['id_adquisicion_producto']?>)" class="btn btn-warning btn-sm">EDITAR</button></td>
                    <td><button onclick="eliminar(<?=$value['id_adquisicion_producto']?>)" class="btn btn-danger btn-sm">ELIMINAR</button></td>
                  <?php else:?>
                    <td></td>
                    <td></td>
                  <?php endif;?>
                  
                </tr>
              <?php endforeach?>
                <tr>
                  <th class="text-primary font-weight-bold" colspan="6">TOTAL</th>
                  <th class="text-end text-primary font-weight-bold"><?=number_format($total,2,',','.');?></th>
                  <th></th>
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

</main>
<script>
  function agregarProducto(){
    let producto = $("#producto_elegido").select2('data');
    let ruta = '<?=base_url()?>adquisicion_producto_cargar/'+<?=$nro_adquisicion['id_nro_adquisicion']?>+'/'+producto[0].id;
    $.get(ruta)
      .done(function(data) {  
        $('#contenido_modal_add').html(data);
      })
      .fail(function(xhr, status, error) {
        console.error(error);
      });

  }

  function editar(id){
    $.get('<?=base_url()?>adquisicion_producto/editar_item/'+id)
      .done(function(data) {  
        $('#contenido_modal_editar').html(data);
      })
      .fail(function(xhr, status, error) {
        console.error(error);
      });
  }

  function eliminar(id){
    if(confirm('Esta seguro de eliminar este item?')){
      location.href = '<?=base_url()?>adquisicion_producto/eliminar_item/'+id
    }
  }

</script>

<?= $this->endSection();?>
