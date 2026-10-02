<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('usuario/')?>'">Volver a lista de usuarios</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>

    <h4 class="c-grey-900 mT-10 mB-30">ASIGNACIÓN DE BODEGAS A USUARIOS <span class="text-primary"> (<?=$usuario['nombre']?>) <span></h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
          <form action="<?php echo base_url('usuario/asignar_bodega_guardar');?>" method="post">
            <input type="hidden" name="username" id="username" value="<?php echo $usuario['username'];?>">
            <?= csrf_field() ?>
            <div class="form-group row">
              <label for="bodega" class="col-sm-2 col-form-label fw-bold">SELECCIONE BODEGA A ASIGNAR: </label>
              <div class="col-sm-6">
                <select class="form-control" id="id_bodega" name="id_bodega">
                  <?php foreach($bodegas as $bodega):?>
                    <option value="<?php echo $bodega['id_bodega'];?>"><?php echo $bodega['nombre_bodega'];?></option>
                  <?php endforeach;?>
                </select>
              </div>
              <div class="col-sm-4">
                <button type="submit" class="btn btn-success btn-sm text-white">ASIGNAR BODEGA</button>
              </div>
            </div>
            
          </form>
        </div>
      </div>
    </div>

		<?php if (session()->getFlashdata('error')): ?>
      <div class="row mt-3">
        <div class="alert alert-danger">
          <?= session()->getFlashdata('error') ?>
        </div>
      </div>
    <?php endif; ?>


    <div class="row mt-3">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">BODEGA ASIGNADA</th>
                <th scope="col">ENCARGADO BODEGA</th>
                <th scope="col">ELIMINAR ACCESO</th>
              </tr>
              <?php
              use App\Models\BodegasModel;

              if(isset($usuario_bodega)){
                $i = 1;
                foreach ($usuario_bodega as $key => $us_bo) {
                  $objBodega = new BodegasModel();
                  $bodega = $objBodega->getBodega($us_bo['id_bodega']);
                  echo '
                    <tr>
                      <td>'.($i++).'</td>
                      <td>'.$bodega['nombre_bodega'].'</td>
                      <td>'.$bodega['recurso_username'].'</td>
                      <td><a href="'.base_url('usuario/eliminar_acceso/'.$us_bo['id_usuario_bodega']).'" class="btn btn-danger btn-sm">ELIMINAR</a></td>
                    </tr>
                  ';
                }
              }
              ?>
            </thead>
            <tbody>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</main>

<script>
</script>

<?= $this->endSection();?>
