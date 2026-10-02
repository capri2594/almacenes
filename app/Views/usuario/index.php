<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">LISTA DE USUARIOS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">USUARIO</th>
                <th scope="col">NOMBRE COMPLETO</th>
                <th scope="col">NIVEL/ROL</th>
                <th scope="col">ESTADO</th>
                <th scope="col">EDITAR</th>
                <th scope="col">BODEGAS</th>
                <th scope="col">APERTURAS</th>
              </tr>
            </thead>
            <tbody>
              <?php
              use App\Models\UsuarioBodegaModel;
              use App\Models\UsuarioAperturaModel;
              $i=1;

              $niveles = niveles_acceso();
              $estados = estados_acceso();
              foreach ($recursos as $key => $value): ?>
                <tr>
                  <td><?=($i++)?></td>
                  <td><?=$value['username']?></td>
                  <td><?=$value['nombre']?></td>
                  <td><?=$niveles[$value['nivel']];?></td>
                  <td><?=$estados[$value['estado_recurso']];?></td>
                  <td><button data-bs-toggle="modal" data-bs-target="#modal_estatico_editar" onclick="editar('<?=$value['username']?>')" class="btn btn-warning btn-sm">EDITAR</button></td>
                  <?php
                  $objUsuarioBodega = new UsuarioBodegaModel();
                  $us_bo = $objUsuarioBodega->getBodegaByUsername($value['username']);

                  $objUsuarioApertura = new UsuarioAperturaModel();
                  $us_ap = $objUsuarioApertura->getAperturaByUsername($value['username']);

                    if($value['nivel']==4 || $value['nivel']==2){
                      if(count($us_bo)==0)
                        echo '<td><a href="'.base_url('usuario/asignar_bodega/'.$value['username']).'" class="btn btn-success btn-sm">ASIGNAR</a></td>';
                      else 
                        echo '<td><a href="'.base_url('usuario/asignar_bodega/'.$value['username']).'" class="btn btn-primary btn-sm">VER/MODIFICAR</a></td>';
                      
                      if(count($us_ap)==0)
                        echo '<td><a href="'.base_url('usuario/asignar_apertura/'.$value['username']).'" class="btn btn-success btn-sm">ASIGNAR</a></td>';
                      else
                        echo '<td><a href="'.base_url('usuario/asignar_apertura/'.$value['username']).'" class="btn btn-primary btn-sm">VER/MODIFICAR</a></td>';
                    }
                    else
                      echo '<td></td><td></td>';
                  ?>
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

<!-- Modal Editar -->
<div class="modal fade" id="modal_estatico_editar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
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

  function editar(username){
    //alert(username);
    $.get('<?=base_url()?>/usuario/editar/'+username)
      .done(function(data) {  
        $('#contenido_modal_editar').html(data);
      })
      .fail(function(xhr, status, error) {
        console.error(error);
      });
  }  
</script>

<?= $this->endSection();?>
