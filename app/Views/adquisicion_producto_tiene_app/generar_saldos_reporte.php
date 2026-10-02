<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de saldos</title>
    <link rel="stylesheet" href="<?=base_url()?>reportes.css">
</head>
<body>
    <div class="encabezado">
        <table>
            <tr>
                <td width="50%">
                  <div class="container">
                    <div class="image-container">
                      <img src="<?=base_url()?>assets/static/images/escudo_oruro.jpg" alt="logo oruro">
                    </div>
                    <div class="text-container">
                        <p>
                          GOBIERNO AUTÓNOMO DEPARTAMENTAL DE ORURO<br>
                          SECRETARÍA DEPARTAMENTAL DE ADMINISTRACIÓN Y FINANZAS PÚBLICAS<br>
                          SECCIÓN ALMACENES<br>
                        </p>
                    </div>
                  </div>                  
                </td>
                <td width="50%">
                  <div class="container-qr">
                    <div class="image-qr-container">
                      <img src="<?=base_url()?>assets/static/images/logo_bi.jpg" alt="">
                    </div>
                  </div>                  
                </td>
            </tr>
        </table>
    </div>

    <div class="encabezado">
        <table>
            <tr>
              <td width="100%">
                <div class="container-title">
                  <p>
                    SALDOS - <?=$bodega['nombre_bodega']?>
                  </p>
                </div>        
              </td>
            </tr>
            <tr>
              <td width="100%">
                <div class="container-title">
                  <p>
                    APERTURA:
                    <?php
                    if(isset($apertura['codigo_apertura'])){
                      echo $apertura['codigo_apertura'] . ' - ' . $apertura['descripcion_apertura'];
                    }else{
                      echo 'SIN APERTURA';
                    }
                    ?>
                  </p>
                </div>
              </td>
            </tr>
            <tr>
              <td width="100%">
                <div class="container-title">
                  <p>
                    SUB APERTURA:
                    <?php
                      if(isset($sub_apertura['codigo_sub_apertura'])){
                        echo $sub_apertura['codigo_sub_apertura'] . ' - ' . $sub_apertura['descripcion_sub_apertura'];
                      }else{
                        echo 'SIN SUB APERTURA';
                      }
                    ?>
                  </p>
                </div>
              </td>
            </tr>
        </table>
    </div>    
    <style>
      @media print {
        .no-print { display: none !important; }
      }
      .barra-filtro {
        margin: 12px 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-family: Arial, sans-serif;
        background: #f8f9fa;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
      }
      .input-filtro {
        padding: 6px 12px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 13px;
        width: 320px;
      }
      .btn-accion {
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        border: 1px solid #ccc;
        background: #fff;
      }
      .btn-imprimir {
        background: #0d6efd;
        color: #fff;
        border: none;
        font-weight: bold;
      }
      .btn-imprimir:hover {
        background: #0b5ed7;
      }
    </style>

    <?php
      use App\Models\UnidadesMedidaModel;
      use App\Models\ProductoModel;
    ?>

    <div class="no-print barra-filtro">
      <div>
        <input type="text" id="filtroReporte" class="input-filtro" placeholder="🔍 Filtrar por código o descripción..." autocomplete="off">
        <button type="button" class="btn-accion" onclick="document.getElementById('filtroReporte').value=''; filtrarReporte();">Limpiar</button>
      </div>
      <div style="font-size: 12px; color: #444;">
        <span id="contadorReporte" style="font-weight: bold; margin-right: 15px;">Total: <?=count($adquisicion_producto_tiene_app)?> ítems</span>
        <button type="button" class="btn-accion btn-imprimir" onclick="window.print()">🖨️ Imprimir Reporte</button>
      </div>
    </div>

    <div class="contenido">
        <?php if(empty($adquisicion_producto_tiene_app)): ?>
          <div style="text-align: center; padding: 40px; font-family: Arial, sans-serif; color: #666; background: #fff; border: 1px dashed #ccc; margin-top: 15px; border-radius: 4px;">
            <h3 style="margin-bottom: 8px; color: #333;">No se encontraron productos registrados</h3>
            <p style="font-size: 13px; margin: 0;">Esta apertura o sub-apertura no cuenta con existencias físicas en esta bodega.</p>
          </div>
        <?php else: ?>
          <table width="100%" id="tablaReporte">
            <caption>LISTA DE ITEMS</caption>
            <thead>
              <tr>
                  <td class="negrita" style="background:#ccc">Nro.</td>
                  <td class="negrita" style="background:#ccc">CÓDIGO</td>
                  <td class="negrita" style="background:#ccc">UNIDAD</td>
                  <td class="negrita" style="background:#ccc">DESCRIPCIÓN</td>
                  <td class="negrita" style="background:#ccc; text-align:right;">CANTIDAD</td>
              </tr>
            </thead>
            <tbody>
              <?php
                $i=1;
                $total_unidades=0;
                foreach ($adquisicion_producto_tiene_app as $key => $value):
                  $miProducto = new ProductoModel();
                  $prod = $miProducto->getProducto($value['id_producto']);

                  $miUnidadMedida = new UnidadesMedidaModel();
                  $unidad_medida = $miUnidadMedida->getUnidadMedida($prod['id_unidad_medida']);
                  $total_unidades += (float)$value['cantidad'];
              ?>
              <tr class="fila-reporte">
                  <td><?=$i++?></td>
                  <td><strong><?=$prod['codigo']?></strong></td>
                  <td><?=$unidad_medida['nombre_unidad_medida']?></td>
                  <td><?=$prod['nombre_producto']?></td>
                  <td style="text-align:right" data-cant="<?=$value['cantidad']?>"><?=number_format($value['cantidad'], 2)?></td>
              </tr>
              <?php endforeach;?>
              <tr id="sinResultadosReporte" style="display:none;">
                <td colspan="5" style="text-align:center; padding: 20px; color: #888;">
                  No se encontraron ítems que coincidan con la búsqueda.
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr style="background:#f2f2f2; font-weight:bold; font-size:10px;">
                <td colspan="4" style="text-align:right;">TOTAL EXISTENCIAS VISIBLES:</td>
                <td style="text-align:right;" id="totalUnidadesVisibles"><?=number_format($total_unidades, 2)?></td>
              </tr>
            </tfoot>
          </table>
        <?php endif; ?>
    </div>

    <script>
      function filtrarReporte() {
        var input = document.getElementById('filtroReporte');
        if (!input) return;
        var texto = (input.value || '').toLowerCase().trim();
        var filas = document.querySelectorAll('.fila-reporte');
        var visibles = 0;
        var sumUnits = 0;

        filas.forEach(function(f) {
          var content = f.textContent.toLowerCase();
          if (texto === '' || content.indexOf(texto) > -1) {
            f.style.display = '';
            visibles++;
            var cantCell = f.querySelector('td[data-cant]');
            if (cantCell) {
              sumUnits += parseFloat(cantCell.getAttribute('data-cant') || 0);
            }
          } else {
            f.style.display = 'none';
          }
        });

        var noRes = document.getElementById('sinResultadosReporte');
        if (noRes) {
          noRes.style.display = (visibles === 0 && filas.length > 0) ? '' : 'none';
        }

        var cont = document.getElementById('contadorReporte');
        if (cont) {
          cont.textContent = (texto !== '') ? 'Filtrados: ' + visibles + ' de ' + filas.length + ' ítems' : 'Total: ' + filas.length + ' ítems';
        }
        var totCell = document.getElementById('totalUnidadesVisibles');
        if (totCell) {
          totCell.textContent = sumUnits.toLocaleString('es-BO', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }
      }

      var inputFiltro = document.getElementById('filtroReporte');
      if (inputFiltro) {
        inputFiltro.addEventListener('input', filtrarReporte);
      }
    </script>
</body>
</html>