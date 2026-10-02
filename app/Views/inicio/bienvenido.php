<?= $this->extend('plantillas/layout');?>
<?= $this->section('contenido');?>
	<main class="main-content">
        <h3>Bienvenido(a): <?php print_r(session()->userData['nombre']);?></h3>
        
    </main>
<?= $this->endSection();?>
