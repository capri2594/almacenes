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
        <h4 class="c-grey-900 mT-10 mB-30"><?=$bodega['nombre_bodega']?> -  N° DE INGRESO: <?=$nro_adquisicion['nro_correlativo']?></h4>    
  </div>
  <div class="container">
    <?php if(is_null($nro_adquisicion['doc_upload'])): ?>
    
      <form id="formSubir" action="<?= base_url('ingreso/accion_subir/'.$nro_adquisicion['id_nro_adquisicion']) ?>" method="post" autocomplete="off" novalidate="novalidate" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="row">
          <div class="col-md-6 mt-3">
            <label class="text-normal text-dark form-label">FECHA INGRESO:</label>
            <input type="file" id="archivo_pdf" name="archivo_pdf" class="form-control" multiple>
          </div>
        </div>

          <div class="mt-4">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
              </div>
            </div>
          </div>
      </form>
    <?php else:?>
      <div class="alert alert-primary" role="alert">
        Ya existe un documento de respaldo para este ingreso.<br>
        Elija una opción: <br>
        <a href="<?=base_url('ingreso/eliminar_pdf/'.$nro_adquisicion['id_nro_adquisicion'])?>" class="btn btn-danger">Eliminar pdf para cargar otro</a> <br><br>
        <a href="<?=base_url('ingresos/'.$nro_adquisicion['id_nro_adquisicion'].'.pdf')?>" target="_blank" download="respaldo_ingreso.pdf" class="btn btn-primary">Descargar el actual</a>
      </div>
    <?php endif?>
  </div>
</main>

<script>  
	const validator = new JustValidate('#formSubir');
	validator
  .addField('#archivo_pdf', [
    {
      rule: 'minFilesCount',
      value: 1,
    },
    {
      rule: 'maxFilesCount',
      value: 1,
    },
    {
      rule: 'files',
      value: {
        files: {
          types: ['application/pdf'],
          extensions: ['pdf'],
        },
      },
    },
  ])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});

</script>

<?= $this->endSection();?>
