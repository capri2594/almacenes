<?php
  $tipo_doc = tipoDocumento();
  $dataTipo = tiposOrigenDestino();
?>
<script>
  $(document).ready(function (){
      $('#id_apertura_editar').on('change', function() {
        var selectedApertura = $(this).val();
        if(selectedApertura!=""){
          $("#ajax_idsub_apertura_editar").html('<img class="img-thumbnail" src="<?=base_url()?>assets/static/images/loader.gif">');
          
          $.get('<?= base_url('ingreso_salida/cargar_sub_apertura/') ?>' + selectedApertura, function(data) {
            $("#ajax_idsub_apertura_editar").html(data);
          }).fail(function() {
            $("#ajax_idsub_apertura_editar").html('<p class="text-danger">Error al cargar los datos.</p>');
          });
        }          
      });
  });
</script>

<div class="container">
  <form id="formEditar" action="<?= base_url('ingreso_salida/actualizar/'.$ingreso_salida['id_ingreso_salida']) ?>" method="post" autocomplete="off" novalidate="novalidate">
    <?= csrf_field() ?>
    <div class="row">
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">FECHA INGRESO:</label>
        <input id="fecha_ingreso_salida_editar" name="fecha_ingreso_salida_editar" type="date" class="form-control" value="<?=$ingreso_salida['fecha_ingreso_salida']?>">
      </div>
      <div class="col-md-6">
        <label class="text-normal text-dark form-label">PROVEEDOR:</label>
        <?php
            $dataProveedor=array();
            foreach ($proveedores as $key => $value)
              $dataProveedor[$value['id_proveedor']] = $value['razon_social'];
            
              $jsProveedor="class='form-control' id='id_proveedor' required='required'";
            if(!empty($dataProveedor))
              echo form_dropdown('id_proveedor_editar',$dataProveedor,$ingreso_salida['id_proveedor'],$jsProveedor);
            else echo '<div class="text-danger">No existen proveedores, necesita registrar antes.</div>';
        ?>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">APERTURA PROGRAMÁTICA:</label>
        <?php
          use App\Models\AperturasModel;
          use App\Models\SubAperturasModel;
          $ojbApertura = new AperturasModel();
          $aperturas = $ojbApertura->getAperturasHabilitadas();
          
          foreach ($aperturas as $key => $ap)
            $dataApertura[$ap['id_apertura']] = $ap['codigo_apertura'].' - '.$ap['descripcion_apertura'];
          $jsApertura = 'id="id_apertura_editar" class="form-control"';
          echo form_dropdown('id_apertura_editar', $dataApertura,$ingreso_salida['id_apertura'], $jsApertura);
        ?>        
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">SUB APERTURA:</label>
        <div id="ajax_idsub_apertura_editar">
          <?php
            $ojbSubApertura = new SubAperturasModel();
            $sub_aperturas = $ojbSubApertura->getSubAperturaByIdApertura($ingreso_salida['id_apertura']);
            
            if(count($sub_aperturas)!=0){
              foreach ($sub_aperturas as $key2 => $sub_ap)
                $dataSubApertura[$sub_ap['id_sub_apertura']] = $sub_ap['codigo_sub_apertura'].' - '.$sub_ap['descripcion_sub_apertura'];
              $jsSubApertura = 'id="id_sub_apertura_editar" class="form-control"';
              echo form_dropdown('id_sub_apertura_editar', $dataSubApertura, $ingreso_salida['id_sub_apertura'], $jsSubApertura);
            }else{
              echo '
                <select class="form-control" id="id_sub_apertura_editar" name="id_sub_apertura_editar">
                  <option value="0">Sin sub apertura</option>
                </select>
              ';
            }
          ?>        
        </div>

      </div>
    </div>

    <div class="row">
      <div class="col-md-12 mt-3">
        <label class="text-normal text-dark form-label">HOJA DE RUTA:</label>
        <input id="hoja_ruta_editar" name="hoja_ruta_editar" type="text" class="form-control" placeholder="SDAFP-2509/2024" value="<?=$ingreso_salida['hoja_ruta']?>">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">TIPO DE DOCUMENTO:</label>
        <?php
          $jsTipoDoc = 'id="tipo_documento_editar" class="form-control" ';
          echo form_dropdown('tipo_documento_editar', $tipo_doc,$ingreso_salida['tipo_documento'], $jsTipoDoc)
        ?>
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">N° DOCUMENTO:</label>
        <input id="nro_tipo_documento_editar" name="nro_tipo_documento_editar" type="text" class="form-control" placeholder="SDAFP-2509/2024" value="<?=$ingreso_salida['nro_tipo_documento']?>">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">DOCUMENTO DE CONSTANCIA:</label>
        <?php
            $documentoConstancia = documentoConstancia();
            $jsTipoDoc="id='doc_constancia_editar' class='form-control'";
            echo form_dropdown('doc_constancia_editar',$documentoConstancia,$ingreso_salida['doc_constancia'],$jsTipoDoc);
        ?>

      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">N° DOCUMENTO CONSTANCIA:</label>
        <input id="nro_doc_constancia_editar" name="nro_doc_constancia_editar" type="text" class="form-control" placeholder="2102" value="<?=$ingreso_salida['nro_doc_constancia']?>">
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">TIPO ADQUISICIÓN:</label>
        <?php
            $jsTipo="id='tipo_adquisicion_editar' class='form-control'";
            echo form_dropdown('tipo_adquisicion_editar',$dataTipo,$ingreso_salida['tipo_adquisicion'],$jsTipo);
        ?>
      </div>
      <div class="col-md-6 mt-3">
        <label class="text-normal text-dark form-label">OBSERVACIONES:</label>
        <textarea class="form-control" name="observaciones_editar" id="observaciones_editar"><?=$ingreso_salida['observaciones']?></textarea>
      </div>
    </div>

    <div class="mt-3">
      <div class="peers ai-c jc-sb fxw-nw">
        <div class="peer">
          <button type="submit" class="btn btn-success btn-color">GUARDAR</button>
          <button type="button" class="btn btn-danger btn-color" data-bs-dismiss="modal">CANCELAR</button>
        </div>
      </div>
    </div>
  </form>
</div>
<script>  
	const validator2 = new JustValidate('#formEditar');
	validator2
	.addField('#fecha_ingreso_salida_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#id_apertura_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#nro_tipo_documento_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#hoja_ruta_editar', [
		{
		rule: 'required',
		}
	])
	.addField('#nro_doc_constancia_editar', [
		{
		rule: 'required',
		}
	])
	.onSuccess(( event ) => {
    	event.currentTarget.submit();
	});
</script>
