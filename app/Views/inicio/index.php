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
    <div class="peers ai-s fxw-nw h-100vh">
      <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv" style='background-image: url("<?=base_url()?>assets/static/images/bg.jpg")'>
      </div>
      <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 scrollable pos-r" style="min-width: 320px; background: url('<?=base_url()?>assets/static/images/form_login.jpg')" >
        <h4 class="fw-300 c-grey-900 mB-40">Ingreso</h4>

  		<?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
          <?= session()->getFlashdata('error') ?>
        </div>
      <?php endif; ?>

        <form id="formLogin" action="<?= base_url('inicio/login') ?>" method="post" autocomplete="off" novalidate="novalidate">
		      <?= csrf_field() ?>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">Correo electrónico:</label>
            <input id="username" name="username" type="text" class="form-control" placeholder="juan.perez@gmail.com">
          </div>
          <div class="mb-3">
            <label class="text-normal text-dark form-label">Contraseña:</label>
            <input id="password" name="password" type="password" class="form-control" placeholder="">
          </div>
          <div class="">
            <div class="peers ai-c jc-sb fxw-nw">
              <div class="peer">
                <button type="submit" class="btn btn-primary btn-color">Ingresar</button>
              </div>
            </div>
          </div>
        </form>
        <div class="position-relative h-50">
          <div class="position-absolute top-100 start-50 translate-middle">
            <img src="<?=base_url();?>assets/static/images/oruro-de-pie.png" alt="">
          </div>
        </div>

      </div>
    </div>
	<script>
	const validator = new JustValidate('#formLogin');

	validator
	.addField('#username', [
		{
		rule: 'required',
		}
	])
	.addField('#password', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});

	
	</script>
<?= $this->endSection();?>