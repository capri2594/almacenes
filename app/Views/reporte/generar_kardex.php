<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
  	<link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/static/images/logo.png">
    <title>GADOR WAREHOUSE</title>
    <script src="<?=base_url()?>just-validate.min.js"></script>
    <script>
      let parametrosPopPup = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no, width=800,height=600,left=0,top=0 ` ;    </script>    
    <style>
      #loader {
        transition: all 0.3s ease-in-out;
        opacity: 1;
        visibility: visible;
        position: fixed;
        height: 100vh;
        width: 100%;
        background: #fff;
        z-index: 90000;
      }
	
      #loader.fadeOut {
        opacity: 0;
        visibility: hidden;
      }

      .spinner {
        width: 40px;
        height: 40px;
        position: absolute;
        top: calc(50% - 20px);
        left: calc(50% - 20px);
        background-color: #333;
        border-radius: 100%;
        -webkit-animation: sk-scaleout 1.0s infinite ease-in-out;
        animation: sk-scaleout 1.0s infinite ease-in-out;
      }

      @-webkit-keyframes sk-scaleout {
        0% { -webkit-transform: scale(0) }
        100% {
          -webkit-transform: scale(1.0);
          opacity: 0;
        }
      }

      @keyframes sk-scaleout {
        0% {
          -webkit-transform: scale(0);
          transform: scale(0);
        } 100% {
          -webkit-transform: scale(1.0);
          transform: scale(1.0);
          opacity: 0;
        }
      }
    </style>
  <script defer="defer" src="<?=base_url()?>main.js"></script>
  <script src="<?=base_url()?>jquery.min.js"></script>
  <script src="<?=base_url()?>select2.min.js"></script>

  </head>
  <body class="app">
    
    <div id="loader">
      <div class="spinner"></div>
    </div>

    <script>
      window.addEventListener('load', function load() {
        const loader = document.getElementById('loader');
        setTimeout(function() {
          loader.classList.add('fadeOut');
        }, 300);
      });
    </script>
    <?php
      if($bodega_id==0 || ($id_bodega==$bodega_id)){
       ; 
      }else{
        echo '<script>alert("No tienes permiso para acceder a esta bodega");</script>';
        return ;
      }
    ?>
    <div class="container">
      <form id="formKardex" action="<?= base_url('reporte/generar_kardex_producto') ?>" method="post" autocomplete="off" novalidate="novalidate">
      <?= csrf_field() ?>
        <div class="row">
        <div class="col-md-6 mt-3">
            <label class="text-normal text-dark form-label">SELECCIONE EL PRODUCTO:</label>
            <?php
              foreach ($productos as $key => $value) 
                $dataProd[$value['id_producto']] = $value['nombre_producto'];
              $js='id="id_producto" class="form-control" ';
              echo form_dropdown('id_producto',$dataProd, '', $js);
            ?>
          </div>
          <div class="col-md-6 mt-3">
            <input type="hidden" name="id_bodega_kardex" class="form-control" value="<?=$id_bodega;?>">
          </div>
          <div class="col-md-6 mt-3">
            <label class="text-normal text-dark form-label">GENERAR:</label>
            <button type="submit" class="btn btn-success">GENERAR</button>
          </div>
          
        </div>
      </form>
    </div>
    </div>
  </body>
  <link rel="stylesheet" href="<?=base_url()?>style.css">
  <link rel="stylesheet" href="<?=base_url()?>select2.min.css">
</html>
