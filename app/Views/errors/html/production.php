<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">

    <title>Ocurrio algo inesperado</title>

    <style>
        <?= preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'debug.css')) ?>
    </style>
<script defer="defer" src="<?=base_url()?>main.js"></script>
</head>
<body>

    <div class="container text-center">

        <h1 class="headline">Ocurrio algo inesperado</h1>

        <p class="lead">Envie una captura de este problema al desarrollador por favor.</p>
        <a class="btn btn-primary" href="<?=base_url()?>">Volver al inicio</a>
    </div>

</body>

</html>
