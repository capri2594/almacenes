<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
	<main class="main-content bgc-grey-100">    
    <div class="row">
      <div class="col-sm mt-2 d-grid"><a class="btn btn-primary mb-3" href="<?=base_url()?>bodega">Bodegas disponibles</a></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
      <div class="col-sm mt-2 d-grid"></div>
    </div>
    <h4>INFORMACIÓN</h4>
    <div class="container">
        <div class="row">
          <div class="col-sm mt-2 d-grid">BODEGA: <?=$bodega['nombre_bodega'];?></div>
          <div class="col-sm mt-2 d-grid">DIRECCIÓN: <?=$bodega['direccion_bodega'];?></div>
          <div class="col-sm mt-2 d-grid">RESPONSABLE: <?=$usuario['nombre'];?></div>
        </div>
        <div class="row">
          <div class="col-sm mt-2 d-grid">CONTROL EXPIRACIÓN: <?php echo $bodega['control_expiracion']==0?'NO':'SI'?></div>
          <div class="col-sm mt-2 d-grid">ESTADO: <?php echo $bodega['estado_bodega']==0?'DESHABILITADO':'HABILITADO'?></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>
    </div>
    <h4 class="mt-3">GESTIONAR</h4>
      <div class="container">
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a href="<?=base_url()?>unidad_medida/<?=$bodega['id_bodega'];?>" class="btn btn-primary">Unidades de medida</a></div>
          <div class="col-sm mt-2 d-grid"><a href="<?=base_url()?>proveedor/<?=$bodega['id_bodega'];?>" class="btn btn-primary">Proveedores</a></div>
          <div class="col-sm mt-2 d-grid"><a href="<?=base_url()?>producto/<?=$bodega['id_bodega'];?>" class="btn btn-primary">Productos/Items</a></div>
        </div>
        <div class="row">
          <div class="col-sm mt-2 d-grid"><a href="<?=base_url()?>ingreso/<?=$bodega['id_bodega'];?>" class="btn btn-primary">Ingreso</a></div>
          <div class="col-sm mt-2 d-grid"></div>
          <div class="col-sm mt-2 d-grid"></div>
        </div>

      </div>
  </main>
<?= $this->endSection();?>
