<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
        <div class="row">
        <div class="col-sm mt-2 d-grid"></div>
        <div class="col-sm mt-2 d-grid"></div>
        <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>
        <h4 class="c-grey-900 mT-10 mB-30">ERROR</h4>    
  </div>
  <div class="container">
    <div class="alert alert-danger" role="alert">
      ¡Posiblemente el archivo esta corrupto o es demasiado grande!<br>
      <?php var_dump($errors)?>
    </div>
  </div>
</main>
<?= $this->endSection();?>
