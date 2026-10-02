<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Inicio::index');
$routes->post('/inicio/login', 'Inicio::login');
$routes->get('/inicio/bienvenido', 'Inicio::bienvenido');
$routes->get('/inicio/salir', 'Inicio::salir');

$routes->get('/bodega', 'Bodega::index');
$routes->post('/bodega/crear', 'Bodega::crear');
$routes->get('/bodega/editar/(:num)', 'Bodega::editar/$1');
$routes->post('/bodega/actualizar/(:num)', 'Bodega::actualizar/$1');
$routes->get('/bodega/gestionarBodega/(:num)', 'Bodega::gestionarBodega/$1');

$routes->get('/usuario', 'Usuario::index');
$routes->get('/usuario/editar/(:segment)', 'Usuario::editar/$1');
$routes->post('/usuario/actualizar/(:segment)', 'Usuario::actualizar/$1');
$routes->get('/usuario/asignar_bodega/(:segment)', 'Usuario::asignar_bodega/$1');
$routes->post('/usuario/asignar_bodega_guardar/', 'Usuario::asignar_bodega_guardar');
$routes->get('/usuario/eliminar_acceso/(:num)', 'Usuario::eliminar_acceso/$1');
$routes->get('/usuario/asignar_apertura/(:segment)', 'Usuario::asignar_apertura/$1');
$routes->post('/usuario/asignar_apertura_guardar/', 'Usuario::asignar_apertura_guardar');
$routes->get('/usuario/eliminar_acceso_apertura/(:num)', 'Usuario::eliminar_acceso_apertura/$1');
$routes->get('/usuario/cargar_sub_apertura/(:num)', 'Usuario::cargar_sub_apertura/$1');

$routes->get('/unidad_medida/(:num)', 'UnidadMedida::index/$1');
$routes->post('/unidad_medida/crear', 'UnidadMedida::crear');
$routes->get('/unidad_medida/editar/(:num)', 'UnidadMedida::editar/$1');
$routes->post('/unidad_medida/actualizar/(:num)', 'UnidadMedida::actualizar/$1');
$routes->get('/unidad_medida/eliminar/(:num)', 'UnidadMedida::eliminar/$1');

$routes->get('/proveedor/(:num)', 'Proveedor::index/$1');
$routes->post('/proveedor/crear', 'Proveedor::crear');
$routes->get('/proveedor/editar/(:num)', 'Proveedor::editar/$1');
$routes->post('/proveedor/actualizar/(:num)', 'Proveedor::actualizar/$1');
$routes->get('/proveedor/eliminar/(:num)', 'Proveedor::eliminar/$1');

$routes->get('/producto/(:num)', 'Producto::index/$1');
$routes->post('/producto/crear', 'Producto::crear');
$routes->get('/producto/editar/(:num)', 'Producto::editar/$1');
$routes->post('/producto/actualizar/(:num)', 'Producto::actualizar/$1');
$routes->get('/producto/eliminar/(:num)', 'Producto::eliminar/$1');
$routes->post('/producto/buscar/(:num)', 'Producto::buscar/$1');

$routes->get('/origen_destino', 'OrigenDestino::index');
$routes->post('/origen_destino/crear', 'OrigenDestino::crear');
$routes->get('/origen_destino/editar/(:num)', 'OrigenDestino::editar/$1');
$routes->post('/origen_destino/actualizar/(:num)', 'OrigenDestino::actualizar/$1');
$routes->get('/origen_destino2', 'OrigenDestino::index2');
$routes->post('/origen_destino/crear2', 'OrigenDestino::crear2');
$routes->get('/origen_destino/editar2/(:num)', 'OrigenDestino::editar2/$1');
$routes->post('/origen_destino/actualizar2/(:num)', 'OrigenDestino::actualizar2/$1');

$routes->get('/ingreso/(:num)', 'NroAdquisicion::index/$1');
$routes->post('/ingreso/crear', 'NroAdquisicion::crear');
$routes->get('/ingreso/editar/(:num)', 'NroAdquisicion::editar/$1');
$routes->post('/ingreso/actualizar/(:num)', 'NroAdquisicion::actualizar/$1');
$routes->get('/ingreso/imprimir_ingreso_materiales/(:num)', 'NroAdquisicion::imprimir_ingreso_materiales/$1');
$routes->get('/ingreso/subir/(:num)', 'NroAdquisicion::subir/$1');
$routes->post('/ingreso/accion_subir/(:num)', 'NroAdquisicion::upload/$1');
$routes->get('/ingreso/eliminar_pdf/(:num)', 'NroAdquisicion::eliminar_pdf/$1');
$routes->get('/ingreso/finalizar/(:num)', 'NroAdquisicion::finalizar/$1');
$routes->get('/ingreso/cargar_sub_apertura/(:num)', 'NroAdquisicion::cargar_sub_apertura/$1');
$routes->get('/ingreso/cambiar_estado/(:num)', 'NroAdquisicion::cambiar_estado/$1');
$routes->post('/ingreso/actualizar_estado/(:num)', 'NroAdquisicion::actualizar_estado/$1');

$routes->get('/adquisicion_producto/(:num)/(:num)', 'AdquisicionProducto::index/$1/$2');
$routes->get('/adquisicion_producto_cargar/(:num)/(:num)', 'AdquisicionProducto::cargarProducto/$1/$2');//carga id_nro_adquisicion y id_producto
$routes->post('/adquisicion_producto/add_producto', 'AdquisicionProducto::addProducto');
$routes->get('/adquisicion_producto/editar_item/(:num)', 'AdquisicionProducto::editarItem/$1');
$routes->post('/adquisicion_producto/actualizar_item/(:num)', 'AdquisicionProducto::actualizarItem/$1');
$routes->get('/adquisicion_producto/eliminar_item/(:num)', 'AdquisicionProducto::eliminarItem/$1');

$routes->get('/orden', 'Orden::index');
$routes->post('/orden/crear', 'Orden::crear');
$routes->get('/orden/editar/(:num)', 'Orden::editar/$1');
$routes->post('/orden/actualizar/(:num)', 'Orden::actualizar/$1');
$routes->get('/orden/enviar_orden/(:num)', 'Orden::enviarOrden/$1');
$routes->get('/orden/atender_solicitud/', 'Orden::atenderSolicitud');
$routes->get('/orden/atenderSolicitudAdmin/(:num)', 'Orden::atenderSolicitudAdmin/$1');
$routes->get('/orden/ver_solicitudes_atendidas/(:num)', 'Orden::ver_solicitudes_atendidas/$1');
$routes->get('/orden/devolver_solicitud/(:num)', 'Orden::devolverSolicitud/$1');
$routes->get('/orden/eliminar_items/(:num)/(:num)', 'Orden::eliminarItems/$1/$2');
$routes->get('/orden/atender/(:num)', 'Orden::atender/$1');
$routes->post('/orden/ejecutar_solicitud/(:num)', 'Orden::ejecutarSolicitud/$1');
$routes->get('/orden/solicitud_success/(:num)', 'Orden::solicitudSuccess/$1');
$routes->get('/orden/imprimir_solicitud_aprobada/(:num)', 'Orden::imprimir_solicitud_aprobada/$1');
$routes->get('/orden/despachar/(:num)', 'Orden::despachar/$1');
$routes->get('/orden/anular/(:num)', 'Orden::anular/$1');
$routes->get('/orden/eliminar_orden/(:num)', 'Orden::eliminarOrden/$1');


$routes->get('/items_orden/(:num)', 'ItemsOrden::index/$1');
$routes->get('/items_orden_cargar/(:num)/(:num)', 'ItemsOrden::cargarProducto/$1/$2');//carga id_orden y id_producto
$routes->post('/items_orden_cargar/add_producto', 'ItemsOrden::addProducto');
$routes->get('/items_orden/editar_item/(:num)', 'ItemsOrden::editarItem/$1');
$routes->post('/items_orden/actualizar_item/(:num)', 'ItemsOrden::actualizarItem/$1');
$routes->get('/items_orden/eliminar_item/(:num)', 'ItemsOrden::eliminarItem/$1');

$routes->get('/reporte', 'Reporte::index');
$routes->get('/reporte/stock_actual', 'Reporte::stockActual');
$routes->get('/reporte/kardex', 'Reporte::kardex');
$routes->post('/reporte/generar_kardex_producto', 'Reporte::generar_kardex_producto');
$routes->get('/reporte/consolidado', 'Reporte::consolidado');
$routes->get('/reporte/consolidado_fisico', 'Reporte::consolidado_fisico');
$routes->get('/reporte/catalogo', 'Reporte::catalogo');
$routes->get('/reporte/catalogo_usuario', 'Reporte::catalogo_usuario');
$routes->get('/reporte/imprimir_catalogo_usuario/(:num)', 'Reporte::imprimir_catalogo_usuario/$1');
$routes->get('/reporte/inventario_inicial', 'Reporte::inventario_inicial');
$routes->get('/reporte/r6', 'Reporte::r6/$1');
$routes->get('/reporte/salvatore/(:num)/(:num)', 'Reporte::salvatore/$1/$2');
$routes->get('/reporte/salvatore2/(:num)/(:num)', 'Reporte::salvatore2/$1/$2');
$routes->get('/reporte/recalcular/(:num)/(:num)', 'Reporte::recalcular/$1/$2');//procesar
$routes->get('/reporte/kardex_insumos', 'Reporte::kardex_insumos');
$routes->get('/reporte/kardex_insumos_fisico', 'Reporte::kardex_insumos_fisico');
$routes->get('/reporte/contador_productos', 'Reporte::contadorProductos');
$routes->get('/reporte/kardex_valorado', 'Reporte::kardexValorado');
$routes->get('/reporte/r5_r6', 'Reporte::r5_r6');
$routes->get('/reporte/kardex_final', 'Reporte::kardex_final');
$routes->get('/reporte/actualizacion_saldo', 'Reporte::actualizacion_saldo');

$routes->get('/transferencia/(:num)', 'Transferencia::index/$1');
$routes->get('/transferencia/cargar_sub_apertura/(:num)', 'Transferencia::cargar_sub_apertura/$1');
$routes->get('/transferencia/cargar_sub_apertura_destino/(:num)', 'Transferencia::cargar_sub_apertura_destino/$1');
$routes->post('/transferencia/crear_transferencia', 'Transferencia::crear_transferencia');
$routes->get('/transferencia/ejecutar_transferencia/(:num)', 'Transferencia::ejecutar_transferencia/$1');

$routes->get('/contenido_transferencia/(:num)', 'ContenidoTransferencia::index/$1');
$routes->get('/contenido_transferencia/(:num)/(:num)', 'ContenidoTransferencia::cargarProducto/$1/$2');//carga id_transferencia y id_producto
$routes->post('/contenido_transferencia/add_producto', 'ContenidoTransferencia::addProducto');
$routes->get('/contenido_transferencia/eliminar_item/(:num)', 'ContenidoTransferencia::eliminarItem/$1');

$routes->get('/apertura', 'Apertura::index');
$routes->get('/apertura/editar/(:num)', 'Apertura::editar/$1');
$routes->post('/apertura/crear', 'Apertura::crear');
$routes->post('/apertura/actualizar/(:num)', 'Apertura::actualizar/$1');

$routes->get('/sub_apertura/(:num)', 'SubApertura::index/$1');
$routes->get('/sub_apertura/editar/(:num)', 'SubApertura::editar/$1');
$routes->post('/sub_apertura/crear', 'SubApertura::crear');
$routes->post('/sub_apertura/actualizar/(:num)', 'SubApertura::actualizar/$1');

$routes->get('/partida', 'Partida::index');
$routes->get('/partida/editar/(:num)', 'Partida::editar/$1');
$routes->post('/partida/crear', 'Partida::crear');
$routes->post('/partida/actualizar/(:num)', 'Partida::actualizar/$1');

$routes->get('/sub_partida/(:num)', 'SubPartida::index/$1');
$routes->get('/sub_partida/editar/(:num)', 'SubPartida::editar/$1');
$routes->post('/sub_partida/crear', 'SubPartida::crear');
$routes->post('/sub_partida/actualizar/(:num)', 'SubPartida::actualizar/$1');

$routes->get('/ingreso_salida/(:num)', 'IngresoSalida::index/$1');
$routes->post('/ingreso_salida/crear', 'IngresoSalida::crear');
$routes->get('/ingreso_salida/editar/(:num)', 'IngresoSalida::editar/$1');
$routes->get('/ingreso_salida_items/(:num)/(:num)', 'IngresoSalida::ingreso_salida_items/$1/$2');
$routes->post('/ingreso_salida_items/add/(:num)/(:num)', 'IngresoSalidaItems::add/$1/$2');//id_bodega/id_ingreso_salida
$routes->get('/ingreso_salida_items/eliminar/(:num)', 'IngresoSalidaItems::eliminar/$1');
$routes->get('/ingreso_salida/cargar_sub_apertura/(:num)', 'IngresoSalida::cargar_sub_apertura/$1');
$routes->get('/ingreso_salida/imprimir_ingreso_materiales/(:num)', 'IngresoSalida::imprimir_ingreso_materiales/$1');
$routes->get('/ingreso_salida/subir/(:num)', 'IngresoSalida::subir/$1');
$routes->get('/ingreso_salida/finalizar/(:num)', 'IngresoSalida::finalizar/$1');
$routes->get('/ingreso_salida/cambiar_estado/(:num)', 'IngresoSalida::cambiar_estado/$1');
$routes->post('/ingreso_salida/actualizar_estado/(:num)', 'IngresoSalida::actualizar_estado/$1');
$routes->post('/ingreso_salida/actualizar/(:num)', 'IngresoSalida::actualizar/$1');

$routes->get('/adquisicion_producto_tiene_app/(:num)', 'AdquisicionProductoTieneApp::index/$1');
$routes->get('/adquisicion_producto_tiene_app/modificar/(:num)', 'AdquisicionProductoTieneApp::modificar/$1');
$routes->post('/adquisicion_producto_tiene_app/modificar_saldos/(:num)', 'AdquisicionProductoTieneApp::modificar_saldos/$1');
$routes->get('/adquisicion_producto_tiene_app/cargar_sub_apertura/(:num)', 'AdquisicionProductoTieneApp::cargar_sub_apertura/$1');
$routes->get('/adquisicion_producto_tiene_app/cargar_sub_apertura_destino/(:num)', 'AdquisicionProductoTieneApp::cargar_sub_apertura_destino/$1');
$routes->get('/adquisicion_producto_tiene_app/generar_saldos/(:num)', 'AdquisicionProductoTieneApp::generar_saldos/$1');
$routes->get('/adquisicion_producto_tiene_app/generar_saldos_modificar/(:num)', 'AdquisicionProductoTieneApp::generar_saldos_modificar/$1');

$routes->get('/admin/default', 'Admin::default');

