<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
    <h4 class="c-grey-900 mT-10 mB-30">MIS SOLICITUDES</h4>
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger" role="alert">
          No tiene ninguna Bodega y/o Apertura asignada a su usuario, contactese con el administrador del almacen.
        </div>
      </div>
    </div>
  </div>
</main>


<?= $this->endSection();?>
