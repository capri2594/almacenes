<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">MIS CATALOGOS</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="bgc-white bd bdrs-3 p-20">
        <div class="mT-5 mB-30">
          <div class="gap-10 peers">
            <div class="peer">
            </div>
          </div>
        </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th scope="col">N°</th>
                <th scope="col">BODEGA</th>
              </tr>
            </thead>
            <tbody>
              <?php
                use App\Models\BodegasModel;
                $i=1;
                foreach ($bodegas as $key => $value):
                  $objBodega = new BodegasModel();
                  $bodega = $objBodega->getBodega($value['id_bodega']);
              ?>
                <tr>
                  <td><?=$i++?></td>
                  <td><a href="<?=base_url('reporte/imprimir_catalogo_usuario/'.$value['id_bodega'])?>" target="_blank" onClick="window.open(this.href, this.target, 'width=800,height=600'); return false;"><?=$bodega['nombre_bodega']?></a></td>
                </tr>
              <?php endforeach?>
            </tbody>
          </table>
          
        </div>
      </div>
    </div>
  </div>
</main>  

<?= $this->endSection();?>
