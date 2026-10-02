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

    
    
    <div>
      <!-- #Left Sidebar ==================== -->
      <div class="sidebar">
        <div class="sidebar-inner">
          <!-- ### $Sidebar Header ### -->
          <div class="sidebar-logo">
            <div class="peers ai-c fxw-nw">
              <div class="peer peer-greed">
                <a class="sidebar-link td-n" href="<?=base_url()?>">
                  <div class="peers ai-c fxw-nw">
                    <div class="peer">
                      <div class="logo">
                        <img src="<?=base_url()?>assets/static/images/logo.png" alt="" style="width:50px;">
                      </div>
                    </div>
                    <div class="peer peer-greed">
                      <h5 class="lh-1 mB-0 logo-text"><?=titulo()?></h5>
                    </div>
                  </div>
                </a>
              </div>
              <div class="peer">
                <div class="mobile-toggle sidebar-toggle">
                  <a href="" class="td-n">
                    <i class="ti-arrow-circle-left"></i>
                  </a>
                </div>
              </div>
            </div>
          </div>
          <!-- ### $Sidebar Menu ### -->
          <?php
          if((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1))
            leftMenuAdmin(); 
          elseif((session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1))
            leftMenuAlmacenero(); 
          elseif((session()->isLoggedIn['nivel']==3) && (session()->isLoggedIn['estado_recurso']==1))
            leftMenuReporte(); 
          elseif((session()->isLoggedIn['nivel']==4) && (session()->isLoggedIn['estado_recurso']==1))
            leftMenuUsuario(); 
          ?>
          <!-- ### $Sidebar Menu ### -->
        </div>
      </div>
      

      <!-- #Main ============================ -->
      <div class="page-container">
        <!-- ### $Topbar ### -->
        <div class="header navbar">
          <div class="header-container">
            <ul class="nav-left">
              <li>
                <a id="sidebar-toggle" class="sidebar-toggle" href="javascript:void(0);">
                  <i class="ti-menu"></i>
                </a>
              </li>
              <li class="search-input">
                <input class="form-control" type="text" placeholder="Search...">
              </li>
            </ul>
            <ul class="nav-right">
              <!-- <li class="notifications dropdown">
                <span class="counter bgc-red">3</span>
                <a href="" class="dropdown-toggle no-after" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="ti-bell"></i>
                </a>

                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1"> 
                  <li class="pX-20 pY-15 bdB">
                    <i class="ti-bell pR-10"></i>
                    <span class="fsz-sm fw-600 c-grey-900">Notifications</span>
                  </li>
                  <li>
                    <ul class="ovY-a pos-r scrollable lis-n p-0 m-0 fsz-sm">
                      <li>
                        <a href="" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                          <div class="peer mR-15">
                            <img class="w-3r bdrs-50p" src="<?=base_url()?>assets/fotos/default.jpg" alt="">
                          </div>
                          <div class="peer peer-greed">
                            <span>
                              <span class="fw-500">John Doe</span>
                              <span class="c-grey-600">liked your <span class="text-dark">post</span>
                              </span>
                            </span>
                            <p class="m-0">
                              <small class="fsz-xs">5 mins ago</small>
                            </p>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                          <div class="peer mR-15">
                            <img class="w-3r bdrs-50p" src="https://randomuser.me/api/portraits/men/2.jpg" alt="">
                          </div>
                          <div class="peer peer-greed">
                            <span>
                              <span class="fw-500">Moo Doe</span>
                              <span class="c-grey-600">liked your <span class="text-dark">cover image</span>
                              </span>
                            </span>
                            <p class="m-0">
                              <small class="fsz-xs">7 mins ago</small>
                            </p>
                          </div>
                        </a>
                      </li>
                      <li>
                        <a href="" class="peers fxw-nw td-n p-20 bdB c-grey-800 cH-blue bgcH-grey-100">
                          <div class="peer mR-15">
                            <img class="w-3r bdrs-50p" src="https://randomuser.me/api/portraits/men/3.jpg" alt="">
                          </div>
                          <div class="peer peer-greed">
                            <span>
                              <span class="fw-500">Lee Doe</span>
                              <span class="c-grey-600">commented on your <span class="text-dark">video</span>
                              </span>
                            </span>
                            <p class="m-0">
                              <small class="fsz-xs">10 mins ago</small>
                            </p>
                          </div>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="pX-20 pY-15 ta-c bdT">
                    <span>
                      <a href="" class="c-grey-600 cH-blue fsz-sm td-n">View All Notifications <i class="ti-angle-right fsz-xs mL-10"></i></a>
                    </span>
                  </li>
                </ul>
              </li> -->
              
              <li class="dropdown">
                <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-bs-toggle="dropdown">
                  <div class="peer mR-10">
                    <img class="w-2r bdrs-50p" src="<?=base_url()?>assets/fotos/default.jpg" alt="">
                  </div>
                  <div class="peer">
                    <span class="fsz-sm c-grey-900"><?php print_r(session()->userData['username']);?></span>
                  </div>
                </a>
                <ul class="dropdown-menu fsz-sm">
                  <li role="separator" class="divider"></li>
                  <li>
                    <a href="<?=base_url()?>inicio/salir" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                      <i class="ti-power-off mR-10"></i>
                      <span>Salir</span>
                    </a>
                  </li>
                </ul>
              </li>
              
            </ul>
          </div>
        </div>
      
        <?=$this->renderSection('contenido');?>

        <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
          <span>Copyright © <?=date('Y')?> Gobierno Autónomo Departamental de Oruro</span>
        </footer>
      </div>
    </div>

    <script>
      // ========================================================
      // HELPER GLOBAL: FILTRADO DINÁMICO EN TIEMPO REAL PARA TABLAS
      // ========================================================
      function inicializarFiltroTabla(inputSelector, tablaSelector, contadorSelector, estadoSelector, noResultSelector) {
        var $input = $(inputSelector);
        var $tabla = $(tablaSelector);
        if (!$input.length || !$tabla.length) return;

        function ejecutarFiltro() {
          var texto = ($input.val() || '').toLowerCase().trim();
          var estado = (estadoSelector && $(estadoSelector).length) ? ($(estadoSelector).val() || '').toLowerCase().trim() : '';
          var $filas = $tabla.find('tbody tr.fila-datos');
          var total = $filas.length;
          var visibles = 0;

          $filas.each(function() {
            var $fila = $(this);
            var contenido = $fila.text().toLowerCase();
            var filaEstado = ($fila.data('estado') || '').toString().toLowerCase();

            var coincideTexto = (texto === '' || contenido.indexOf(texto) > -1);
            var coincideEstado = (estado === '' || filaEstado.indexOf(estado) > -1);

            if (coincideTexto && coincideEstado) {
              $fila.show();
              visibles++;
            } else {
              $fila.hide();
            }
          });

          if (noResultSelector && $(noResultSelector).length) {
            if (visibles === 0 && total > 0) {
              $(noResultSelector).show();
            } else {
              $(noResultSelector).hide();
            }
          }

          if (contadorSelector && $(contadorSelector).length) {
            if (texto !== '' || estado !== '') {
              $(contadorSelector).text('Filtrados: ' + visibles + ' de ' + total);
            } else {
              $(contadorSelector).text('Total: ' + total);
            }
          }
        }

        $input.on('keyup input', ejecutarFiltro);
        if (estadoSelector && $(estadoSelector).length) {
          $(estadoSelector).on('change', ejecutarFiltro);
        }

        var $grupo = $input.closest('.input-busqueda-grupo');
        $grupo.find('.btn-limpiar').on('click', function() {
          $input.val('');
          if (estadoSelector && $(estadoSelector).length) $(estadoSelector).val('');
          ejecutarFiltro();
          $input.focus();
        });
      }

      $(document).ready(function() {
        // Auto-activación para inputs con clase .filtro-dinamico-auto
        $('.filtro-dinamico-auto').each(function() {
          var $el = $(this);
          inicializarFiltroTabla(
            $el,
            $el.data('tabla'),
            $el.data('contador'),
            $el.data('estado'),
            $el.data('noresult')
          );
        });
      });
    </script>
  </body>
  <link rel="stylesheet" href="<?=base_url()?>style.css">
  <link rel="stylesheet" href="<?=base_url()?>select2.min.css">
</html>
