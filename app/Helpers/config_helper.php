<?php

if (!function_exists('titulo')) {
    function titulo()
    {
        return 'Warehouse';
    }
}

if (!function_exists('endPointUsers')) {
  function endPointUsers()
  {
      return 'https://api.oruro.gob.bo/api/users/user_login'; 
  }
}

if (!function_exists('niveles_acceso')) {
    function niveles_acceso()
    {
        $niveles = array();
        $niveles = [
            '0'=>'<span class="text-danger">SIN NIVEL</span>',
            '1'=>'ADMINISTRADOR',
            '2'=>'ALMACENERO',
            '3'=>'REPORTE',
            '4'=>'USUARIO'
        ];
        return $niveles;
    }
}

if (!function_exists('estados_acceso')) {
  function estados_acceso()
  {
      $estados = array();
      $estados = [
          '0'=>'<span class="text-danger">DESHABILITADO</span>',
          '1'=>'HABILITADO'
      ];
      return $estados;
  }
}

if (!function_exists('estados_orden')) {
  function estados_orden()
  {
      $estados = array();
      $estados = [
          '1'=>'GENERADO',
          '2'=>'SOLICITADO',
          '3'=>'<span class="text-success">APROBADO</span>',
          '4'=>'<span class="text-primary">ATENDIDO</span>',
          '5'=>'<span class="text-danger">ANULADO</span>'
      ];
      return $estados;
  }
}

if (!function_exists('tiposProveedores')) {
  function tiposProveedores()
  {
      $niveles = array();
      $niveles = [
          '1'=>'JURIDICA',
          '2'=>'PERSONA NATURAL',
          '3'=>'OTRO'
      ];
      return $niveles;
  }
}

if (!function_exists('tiposOrigenDestino')) {
  function tiposOrigenDestino()
  {
      $niveles = array();
      $niveles = [
          '1'=>'ADQUISICIÓN',
          '2'=>'DONACIÓN',
          '3'=>'INGRESO ESPECIAL',
      ];
      return $niveles;
  }
}

if (!function_exists('tipoDocumento')) {
  function tipoDocumento()
  {
      $tipoDocumento = array();
      $tipoDocumento = [
          '1'=>'ORDEN DE COMPRA',
          '2'=>'ORDEN DE SERVICIO',
          '3'=>'ACTA',
          '4'=>'OTRO'
      ];
      return $tipoDocumento;
  }
}

// ex tiposDocumentos
if (!function_exists('documentoConstancia')) {
  function documentoConstancia()
  {
      $niveles = array();
      $niveles = [
          '1'=>'FACTURA',
          '2'=>'RECIBO',
          '3'=>'NOTA DE VENTA',
          '4'=>'OTRO'
      ];
      return $niveles;
  }
}

if (!function_exists('datetime_to_es'))
{
	function datetime_to_es($fecha)
	{
		$salida = substr($fecha, 8,2)."/".substr($fecha, 5,2)."/".substr($fecha, 0,4)." ".substr($fecha, 11,8);
		return $salida;
	}
}

if (!function_exists('leftMenuAdmin')) {
    function leftMenuAdmin()
    {
        echo '
          <ul class="sidebar-menu scrollable pos-r">
            <li class="nav-item mT-30 actived">
              <a class="sidebar-link" href="'.base_url().'">
                <span class="icon-holder">
                  <i class="c-blue-500 ti-home"></i>
                </span>
                <span class="title">Inicio</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'usuario">
                <span class="icon-holder">
                  <i class="c-brown-500 ti-user"></i>
                </span>
                <span class="title">Usuarios</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'bodega">
                <span class="icon-holder">
                  <i class="c-red-500 ti-shopping-cart"></i>
                </span>
                <span class="title">Bodegas</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'apertura">
                <span class="icon-holder">
                  <i class="c-green-500 ti-book"></i>
                </span>
                <span class="title">Aperturas</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'partida">
                <span class="icon-holder">
                  <i class="c-blue-500 ti-book"></i>
                </span>
                <span class="title">Partidas</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'reporte">
                <span class="icon-holder">
                  <i class="c-indigo-500 ti-bar-chart"></i>
                </span>
                <span class="title">Reportes</span>
              </a>
            </li>
          </ul>        
        ';
    }
}//fin funcion leftMenuAdmin


if (!function_exists('leftMenuAlmacenero')) {
  function leftMenuAlmacenero()
  {
      echo '
        <ul class="sidebar-menu scrollable pos-r">
          <li class="nav-item mT-30 actived">
            <a class="sidebar-link" href="'.base_url().'">
              <span class="icon-holder">
                <i class="c-blue-500 ti-home"></i>
              </span>
              <span class="title">Inicio</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="sidebar-link" href="'.base_url().'bodega">
              <span class="icon-holder">
                <i class="c-red-500 ti-shopping-cart"></i>
              </span>
              <span class="title">Bodegas</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="sidebar-link" href="'.base_url().'orden/atender_solicitud">
              <span class="icon-holder">
                <i class="c-blue-500 ti-share"></i>
              </span>
              <span class="title">Atender solicitud</span>
            </a>
          </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'reporte">
                <span class="icon-holder">
                  <i class="c-indigo-500 ti-bar-chart"></i>
                </span>
                <span class="title">Reportes</span>
              </a>
            </li>
        </ul>        
      ';
  }
}//fin funcion leftMenuAlmacenero

if (!function_exists('leftMenuUsuario')) {
  function leftMenuUsuario()
  {
      echo '
        <ul class="sidebar-menu scrollable pos-r">
          <li class="nav-item mT-30 actived">
            <a class="sidebar-link" href="'.base_url().'">
              <span class="icon-holder">
                <i class="c-blue-500 ti-home"></i>
              </span>
              <span class="title">Inicio</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="sidebar-link" href="'.base_url().'orden">
              <span class="icon-holder">
                <i class="c-blue-500 ti-share"></i>
              </span>
              <span class="title">Solicitudes</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="sidebar-link" href="'.base_url().'reporte/catalogo_usuario">
              <span class="icon-holder">
                <i class="c-indigo-500 ti-bar-chart"></i>
              </span>
              <span class="title">Catalogo</span>
            </a>
          </li>
        </ul>        
      ';
  }
}//fin funcion leftMenuUsuario


if (!function_exists('leftMenuReporte')) {
  function leftMenuReporte()
  {
      echo '
        <ul class="sidebar-menu scrollable pos-r">
          <li class="nav-item mT-30 actived">
            <a class="sidebar-link" href="'.base_url().'">
              <span class="icon-holder">
                <i class="c-blue-500 ti-home"></i>
              </span>
              <span class="title">Inicio</span>
            </a>
          </li>
            <li class="nav-item">
              <a class="sidebar-link" href="'.base_url().'reporte">
                <span class="icon-holder">
                  <i class="c-indigo-500 ti-bar-chart"></i>
                </span>
                <span class="title">Reportes</span>
              </a>
            </li>
        </ul>
      ';
  }
}//fin funcion leftMenuReporte

