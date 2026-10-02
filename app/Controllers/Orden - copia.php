<?php

namespace App\Controllers;

use App\Models\AdquisicionProductoModel;
use App\Models\AdquisicionProductoTieneAppModel;
use App\Models\OrdenModel;
use App\Models\BodegasModel;
use App\Models\ItemsOrdenModel;
use App\Models\ProductoModel;
use App\Models\UsuarioBodegaModel;
use App\Models\UsuarioAperturaModel;

class Orden extends BaseController
{
    public function __construct()
    {
        if(!(session()->isLoggedIn['estado_recurso']==1)){
            $session = session();
            $session->destroy();
        }else{            
            helper(['config']);
            helper('form');
        }
    }

    public function index()
    {
        try {
            $miOrden = new OrdenModel();
            $ordenes = $miOrden->getMisOrdenes(session()->userData['username']);

            $objUsuarioBodega = new UsuarioBodegaModel();
            $us_bo = $objUsuarioBodega->getBodegaByUsername(session()->userData['username']);

            $objUsuarioApertura = new UsuarioAperturaModel();
            $us_app = $objUsuarioApertura->getAperturaByUsername(session()->userData['username']);
            if((count($us_bo)>0) && (count($us_app)>0)){
                $data = [
                    'ordenes' => $ordenes,
                    'us_bo' => $us_bo,
                ];
                return view('orden/index', $data);
            }else{
                $data = [
                    'us_app' => $us_app,
                    'us_bo' => $us_bo,
                ];
                return view('orden/sin_bodega_app.php', $data);
            }

        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function crear()
    {        
        $recurso_username = session()->userData['username'];
        $fecha_orden = date('Y-m-d H:i:s');
        $obj_glosa = $this->request->getPost('obj_glosa');
        $glosa = $this->request->getPost('glosa');
        $id_bodega = $this->request->getPost('id_bodega');
        if(is_null($id_bodega)){
            $session = session();
            $session->destroy();
            return redirect()->to('/');
        }
        $postData = [
            'recurso_username' => trim($recurso_username),
            'fecha_orden' => mb_strtoupper(trim($fecha_orden)),
            'estado_orden' => 1,// 1 generado
            'obj_glosa' => mb_strtoupper(trim($obj_glosa)),
            'glosa' => mb_strtoupper(trim($glosa)),
            'id_bodega' => $id_bodega,
            'log_orden' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $orden = new OrdenModel();
        $orden->createOrden($postData);

        return redirect()->to('orden/');
    }
    public function editar($id)
    {
        $miOrden = new OrdenModel();
        $orden=$miOrden->getOrden($id);

        // $miBodega = new BodegasModel();
        // $bodegas = $miBodega->getBodegasHabilitadas();
        $objUsuarioBodega = new UsuarioBodegaModel();
        $us_bo = $objUsuarioBodega->getBodegaByUsername(session()->userData['username']);

        $data = [
            'orden' => $orden,
            'us_bo' => $us_bo,
        ];
        return view('orden/editar', $data);
    }
    public function actualizar($id)
    {
        $miOrden = new OrdenModel();
        $log = $miOrden->getOrden($id);

        $recurso_username = session()->userData['username'];
        $obj_glosa = $this->request->getPost('obj_glosa_editar');
        $glosa = $this->request->getPost('glosa_editar');
        $id_bodega = $this->request->getPost('id_bodega_editar');

        $postData = [
            'recurso_username' => trim($recurso_username),
            'obj_glosa' => mb_strtoupper(trim($obj_glosa)),
            'glosa' => mb_strtoupper(trim($glosa)),
            'id_bodega' => $id_bodega,
            'log_orden' => $log['log_orden'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $miOrden->updateOrden($id, $postData);
        return redirect()->to('orden/');
    }

    public function enviarOrden($id_orden){
        $miOrden = new OrdenModel();
        $orden = $miOrden->getOrden($id_orden);
        $objItemsOrden = new ItemsOrdenModel();
        $items_orden = $objItemsOrden->getItemsOrdenByIdOrden($id_orden);
        if(count($items_orden)==0){            
            return redirect()->to('orden/')->with('message', 'Su solicitud no tiene items. Por lo tanto no se envio su solicitud.');
        }else{
            $postData = [
                'estado_orden' => 2, // 2 solicitado
                'fecha_solicitado' => date('Y-m-d H:i:s'),
                'log_orden' => $orden['log_orden'].',ENVIADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
            ];
            $miOrden->updateOrden($id_orden, $postData);
            return redirect()->to('orden/');
        }

    }

    public function atenderSolicitud(){
        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodegaByUsername(session()->userData['username']);
        
        $objOrden = new OrdenModel();
        $ordenes = $objOrden->getOrdenesSolicitados($bodega['id_bodega']);

        $data = [
            'ordenes' => $ordenes,
            'bodega' => $bodega,
        ];
        return view('orden/atender_solicitud', $data);
    }
    
    public function atenderSolicitudAdmin($id_bodega){
        $objOrden = new OrdenModel();
        $ordenes = $objOrden->getOrdenesSolicitados($id_bodega);

        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $data = [
            'id_bodega' => $id_bodega,
            'ordenes' => $ordenes,
            'bodega' => $bodega,
        ];
        return view('orden/atender_solicitud_admin', $data);
    }

    public function ver_solicitudes_atendidas($id_bodega){
        $objOrden = new OrdenModel();
        $ordenes = $objOrden->getOrdenesAtendidas($id_bodega);

        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $data = [
            'id_bodega' => $id_bodega,
            'ordenes' => $ordenes,
            'bodega' => $bodega,
        ];
        return view('orden/ver_solicitudes_atendidas', $data);
    }

    public function eliminarItems($id_orden, $id_bodega){
        $objItemsOrden = new ItemsOrdenModel();
        $objItemsOrden->deleteItemsOrdenByIdOrden($id_orden);
        return redirect()->to('orden/atenderSolicitudAdmin/'.$id_bodega);
    }
    
    public function devolverSolicitud($id_orden){
        $objOrden = new OrdenModel();
        $orden = $objOrden->getOrden($id_orden);
        $data = [
            'estado_orden' => 1
        ];
        $objOrden->updateOrden($id_orden, $data);
        if(session()->isLoggedIn['nivel']==1)
            return redirect()->to('orden/atenderSolicitudAdmin/'.$orden['id_bodega']);
        else
        return redirect()->to('orden/atenderSolicitud/');
    }

    public function atender($id_orden){
        try {
            $miOrden = new OrdenModel();
            $orden = $miOrden->find($id_orden);
            if($orden['estado_orden']!=2){
                return redirect()->to('orden/atenderSolicitudAdmin/'.$orden['id_bodega']);
            }
            $misItems = new ItemsOrdenModel();
            $items_orden = $misItems->getItemsOrdenByIdOrden($id_orden);
            $miProducto = new ProductoModel();
            
            $objUsuarioApertura = new UsuarioAperturaModel();
            $usuario_apertura = $objUsuarioApertura->getAperturaByUsername($orden['recurso_username']);

            $miProducto = new ProductoModel();
            $productos = $miProducto->where('id_bodega', $orden['id_bodega'])->where('estado_producto', 1)->orderBy('nombre_producto','ASC')->findAll();
            $data = [
                'id_orden' => $id_orden,
                'orden' => $orden,
                'items_orden' => $items_orden,
                'miProducto' => $miProducto,
                'productos' => $productos,
                'usuario_apertura' => $usuario_apertura,
            ];
            
            return view('orden/atender',$data);            
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function ejecutarSolicitud($id_orden){

        $objOrden = new OrdenModel();
        $orden = $objOrden->find($id_orden);
        $id_bodega = $orden['id_bodega'];
        $objItemsOrden = new ItemsOrdenModel();
        $items = $objItemsOrden->getItemsOrdenByIdOrden($id_orden);

        $objUsuarioApertura = new UsuarioAperturaModel();
        $usuario_apertura = $objUsuarioApertura->getAperturaByUsername($orden['recurso_username']);
        
        /* actulizando items_orden */
        $i=1;
        foreach ($items as $key => $item) {
            $cant_aprobada = $this->request->getPost('cant_aprobada_'.$i);
            $dataItemsOrden = array();
            $dataItemsOrden = [
                'cant_aprobada'=>$cant_aprobada,
            ];
            $objItemsOrden->updateItemsOrden($item['id_items_orden'], $dataItemsOrden);

            //experimental
            $adqTieneProdApp = new AdquisicionProductoTieneAppModel();
            $adq_tiene_prod_app = $adqTieneProdApp->getByIdBodegaIdAperturaIdSubAperturaIdProducto($id_bodega, $usuario_apertura[0]['id_apertura'], $usuario_apertura[0]['id_sub_apertura'], $item['id_producto']);
            $cantidad_actual = $adq_tiene_prod_app[0]['cantidad'];
            $nueva_cantidad = $cantidad_actual - $cant_aprobada;
            if($nueva_cantidad < 0)
                $nueva_cantidad = 0;
            $data_adquisicion_producto_tiene_app = [
                'cantidad'=>$nueva_cantidad
            ];
            $adqTieneProdApp->actualizar($adq_tiene_prod_app[0]['id_adquisicion_producto_tiene_app'], $data_adquisicion_producto_tiene_app);
            //fin experimental
            
            $i++;
        }
        /*fin actualizacion items_orden */

        $i=1;
        foreach ($items as $key => $item) {
            $cant_aprobada = $this->request->getPost('cant_aprobada_'.$i);
//            do {
                $adq_prod = new AdquisicionProductoModel();
                $primeraFila = $adq_prod->getFirstByIdProductoExistente($orden['id_bodega'], $item['id_producto']);
                $ultimaFila = $adq_prod->getLastAdquisicionByIdProducto($orden['id_bodega'], $item['id_producto']);

                $cant_existente_fila = $primeraFila['cant_existente'];
                $precio_adquisicion_fila = $primeraFila['precio_adquisicion'];
                //$resta = $cant_existente_fila - $cant_aprobada; // 0-1 = -1
                
                $cant_salida = $cant_aprobada;
//                 if($resta < 0 ){
//                     $cant_aprobada = $resta*(-1);
//                     $dataActulizacionFila = [ 'cant_existente' => 0];
//                     $cant_salida = $cant_aprobada;
//                     $resta = $cant_salida;
// //                    $cant_salida = $primeraFila['cant_existente'];
//                 }
//                 else{
//                     $dataActulizacionFila = [ 'cant_existente' => $resta];
//                 }

                $filaSalida = [
                    'tipo_movimiento' => 0,
                    'id_orden' => $item['id_orden'],
                    'id_producto' => $item['id_producto'],
                    'id_bodega' => $orden['id_bodega'],
                    'precio_adquisicion' => $precio_adquisicion_fila,
                    'cant_salida' => $cant_salida,
                    'ref_adq' => $primeraFila['id_adquisicion_producto'],
                    'saldo_fisico' => $ultimaFila['saldo_fisico']-$cant_salida,
                    'sal_valorado' => $precio_adquisicion_fila*$cant_salida,
                    'saldo_valorado' => $ultimaFila['saldo_valorado']-($precio_adquisicion_fila*$cant_salida),
                    'log_adquisicion_producto' => 'SALIDA ALMACEN|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username'],
                    'id_apertura' => $usuario_apertura[0]['id_apertura'],
                    'id_sub_apertura' => $usuario_apertura[0]['id_sub_apertura'],
                    'fecha_movimiento' => date('Y-m-d'),
                ];
                
                // var_dump($filaSalida);
                // echo '<br>';
                $adq_prod->createAdquisicionProducto($filaSalida);//estaba

                //$adq_prod->updateAdquisicionProducto($primeraFila['id_adquisicion_producto'], $dataActulizacionFila);//estaba
//                } while ($resta < 0);
                $i++;
        }// fin foreach
        $contador_actual = $objOrden->ultimoContadorbyIdBodega($id_bodega);
        $contador_actual = ((int)$contador_actual['contador']) +1;
        /* Actualizamos el estado de la orden*/
        $dataOrden = [
            'estado_orden' => 3,
            'contador' => $contador_actual,
            'fecha_aprobado' => date('Y-m-d H:i:s')
        ];
        
        $objOrden->updateOrden($id_orden, $dataOrden);
        return redirect()->to('orden/atenderSolicitudAdmin/'.$orden['id_bodega']);
    }
    
    public function solicitudSuccess($id_orden){
        return "success: ".$id_orden;
    }

    public function imprimir_solicitud_aprobada($id_orden){
        $objOrden = new OrdenModel();
        $orden = $objOrden->getOrden($id_orden);

        $miBodega = new BodegasModel();
        $bodega = $miBodega->getBodega($orden['id_bodega']);

        $obsItemsOrden = new ItemsOrdenModel();
        $items = $obsItemsOrden->getItemsOrdenByIdOrden($id_orden);

        $objAdquisicionProducto = new AdquisicionProductoModel();
        $adquisicion_producto = $objAdquisicionProducto->getAdquisicionesByIdOrden($id_orden);
        
        $data = [
            'bodega' => $bodega,
            'orden' => $orden,
            'items' => $items,
            'adquisicion_producto' => $adquisicion_producto,
        ];
        return view('orden/imprimir_solicitud_aprobada',$data);
    }

    public function despachar($id_orden){
        $objOrden = new OrdenModel();
        $log = $objOrden->getOrden($id_orden);

        $dataOrden = [
            'estado_orden' => 4,
            'fecha_atendido' => date('Y-m-d H:i:s'),
            'log_orden' => $log['log_orden'].',DESPACHADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $objOrden->updateOrden($id_orden, $dataOrden);
        if(session()->isLoggedIn['nivel']==1){
            return redirect()->to('orden/atenderSolicitudAdmin/'.$log['id_bodega']);
        }else{
            return redirect()->to('orden/atender_solicitud/');
        }

    }

    public function anular($id_orden){
        $objOrden = new OrdenModel();
        $log = $objOrden->getOrden($id_orden);

        $dataOrden = [
            'estado_orden' => 5,
            'fecha_devuelto' => date('Y-m-d H:i:s'),
            'log_orden' => $log['log_orden'].',ANULADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $objOrden->updateOrden($id_orden, $dataOrden);

        $objAdquisicionProducto = new AdquisicionProductoModel();
        $adquisicion_producto = $objAdquisicionProducto->getAdquisicionesByIdOrdenSalida($id_orden);
        
       foreach ($adquisicion_producto as $key => $value) {
            $obj_adq_prod_ref = new AdquisicionProductoModel();
            $adq_prod_ref = $obj_adq_prod_ref->getAdquisicionProducto($value['ref_adq']);
            
            $data_adq_prod_restaurar = [
                'cant_existente' => $adq_prod_ref['cant_existente'] + $value['cant_salida'],
            ];
            
            $obj_adq_prod_ref->updateAdquisicionProducto($value['ref_adq'], $data_adq_prod_restaurar); 
            
            $objAdqProdApp = new AdquisicionProductoTieneAppModel();
            $adq_prod_app = $objAdqProdApp->getParaActualizarDevolucion($value['id_bodega'], $value['id_apertura'], $value['id_sub_apertura'], $value['id_producto'], $adq_prod_ref['ref_adq']);
            if(is_null($adq_prod_app)){
                $obj_adq_prod = new AdquisicionProductoModel(); 
                $obj_adq_prod->deleteAdquisicionProducto($value['id_adquisicion_producto']); 
                continue;
            }
            $data_adq_prod_app = [
                'cantidad' => $adq_prod_app['cantidad'] + $value['cant_salida'],
            ];
            $objAdqProdApp->actualizar($adq_prod_app['id_adquisicion_producto_tiene_app'], $data_adq_prod_app); 

            $obj_adq_prod = new AdquisicionProductoModel(); 
            $obj_adq_prod->deleteAdquisicionProducto($value['id_adquisicion_producto']); 
            
       }
    return redirect()->to('orden/ver_solicitudes_atendidas/'.$log['id_bodega']);       
    }

    public function eliminarOrden($id_orden){
        $objOrden = new OrdenModel();
        $orden = $objOrden->getOrden($id_orden);
        $objItemsOrden = new ItemsOrdenModel();
        if($orden['estado_orden'] == 1){
            $objItemsOrden->deleteItemsOrdenByIdOrden($id_orden);
            $objOrden->deleteOrden($id_orden);
        }
        return redirect()->to('orden');
    }

}
