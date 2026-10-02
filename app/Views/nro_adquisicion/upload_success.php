<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
<main class="main-content bgc-grey-100">
  <div class="container-fluid">
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a class="btn btn-primary" onclick="location.href='<?=base_url('ingreso/'.$nro_adquisicion['id_bodega'])?>'">Volver a lista de ingresos</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>
        <h4 class="c-grey-900 mT-10 mB-30"><?=$bodega['nombre_bodega']?> - N° DE INGRESO: <?=$nro_adquisicion['nro_correlativo']?></h4>    
  </div>
  <div class="container">
    <div class="alert alert-success" role="alert">
      ¡El archivo se subio correctamente!<br>
      <a href="<?=base_url('ingresos/'.$nro_adquisicion['id_nro_adquisicion'].'.pdf')?>" target="_blank" download="respaldo_ingreso.pdf" class="btn btn-primary">Descargar</a>
    </div>
  </div>
</main>

<?= $this->endSection();?>
