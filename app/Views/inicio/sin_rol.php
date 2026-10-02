<?= $this->extend('plantillas/layoutLogin');?>
<?= $this->section('contenido');?>

<script>
  window.addEventListener('load', function load() {
    const loader = document.getElementById('loader');
    setTimeout(function() {
      loader.classList.add('fadeOut');
    }, 300);
  });	  
</script>

<div class="pos-a t-0 l-0 bgc-white w-100 h-100 d-f fxd-r fxw-w ai-c jc-c pos-r p-30">
  <div class="mR-60">
    <img alt="#" src="<?=base_url();?>assets/static/images/500.png">
  </div>

  <div class="d-f jc-c fxd-c">
    <h1 class="mB-30 fw-900 lh-1 c-red-500" style="font-size: 60px;">Bienvenido</h1>
    <h3 class="mB-10 fsz-lg c-grey-900 tt-c">Acceso correcto, contactese con el responsable de almacenes.</h3>
    <p class="mB-30 fsz-def c-grey-700">Espere a que un administrador le asigne un nivel de acceso</p>
    <div>
      <a href="<?=base_url();?>" type="primary" class="btn btn-primary">Volver al inicio</a>
    </div>
  </div>
</div>

<?= $this->endSection();?>