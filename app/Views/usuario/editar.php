<form id="formEditar" action="<?= base_url('usuario/actualizar/'.$usuario['username']) ?>" method="post" autocomplete="off" novalidate="novalidate">
  <?= csrf_field() ?>
  <?php
    $niveles = niveles_acceso();
    $estados = estados_acceso();
  ?>
    <div class="mb-3">
      <label class="text-normal text-dark form-label">NIVEL ACCESO:</label>
      <?php
        $js='class="form-control"';
        echo form_dropdown('nivel', $niveles, $usuario['nivel'], $js);
      ?>
    </div>
    <div class="mb-3">
      <label class="text-normal text-dark form-label">ESTADO:</label>
      <?php
        $js_estado='class="form-control"';
        echo form_dropdown('estado_recurso', $estados, $usuario['estado_recurso'], $js_estado);
      ?>
    </div>
  <div class="">
    <div class="peers ai-c jc-sb fxw-nw">
      <div class="peer">
        <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
        <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
      </div>
    </div>
  </div>
</form>
