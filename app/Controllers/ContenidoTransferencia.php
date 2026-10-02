<?php

namespace App\Controllers;

use App\Models\TransferenciaModel;
use App\Models\ContenidoTransferenciaModel;
use App\Models\ProductoModel;
use App\Models\ItemsOrdenModel;
use App\Models\AdquisicionProductoTieneAppModel;

class ContenidoTransferencia extends BaseController
{
    public function __construct()
    {
        if(!((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1))){

            $session = session();
            $session->destroy();
        }else{            
            helper(['config']);
            helper('form');
        }
    }
    public function index($id_transferencia)
    {
        try {
            $objTransferencia = new TransferenciaModel();
            $transferencia = $objTransferencia->getTransferencia($id_transferencia);
            
            $objAdquisicionProductoTiene = new AdquisicionProductoTieneAppModel();
            $adq_prod_tiene = $objAdquisicionProductoTiene->getByIdBodegaIdAperturaIdSubApertura($transferencia['id_bodega'], $transferencia['id_apertura'], $transferencia['id_sub_apertura']);

            $objContenidoTransferencia = new ContenidoTransferenciaModel();
            $items_transferencia = $objContenidoTransferencia->getContenidoTransferenciaByIdTransferencia($id_transferencia);
            
            $data = [
                'transferencia' => $transferencia,
                'adq_prod_tiene' => $adq_prod_tiene,
                'items_transferencia' => $items_transferencia,
            ];
            return view('contenido_transferencia/index',$data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function cargarProducto($id_transferencia, $id_adquisicion_producto_tiene_app){
        $objAdquisicionProductoTiene = new AdquisicionProductoTieneAppModel();
        $adq_prod_tiene_app = $objAdquisicionProductoTiene->getAdquisicionProductoTieneAppById($id_adquisicion_producto_tiene_app);
        $miProducto = new ProductoModel();
        $producto = $miProducto->find($adq_prod_tiene_app['id_producto']);

        $cant_existente = $adq_prod_tiene_app['cantidad'];
        
        $data = [
            'producto' => $producto,
            'cant_existente' => $cant_existente,
            'id_transferencia' => $id_transferencia,
        ];
        return view('contenido_transferencia/cargarItem', $data);
    }

    public function addProducto()
    {
        $id_producto = $this->request->getPost('id_producto');
        $id_transferencia = $this->request->getPost('id_transferencia');
        $cantidad_transferencia = $this->request->getPost('cantidad_transferencia');
        
        //verificar si el producto ya esta en la lista a transferir
        $objContenidoTransferencia = new ContenidoTransferenciaModel();
        $item_transferencia = $objContenidoTransferencia->getItemTransferenciaByIdTransferenciaIdProducto($id_transferencia, $id_producto);
        if(count($item_transferencia)==0){
            $postData = [
                'id_producto' => $id_producto,
                'id_transferencia' => $id_transferencia,
                'cantidad_transferencia' => $cantidad_transferencia,
            ];
    
            $objContenidoTransferencia = new ContenidoTransferenciaModel();
            $objContenidoTransferencia->insertar($postData);
    
            return redirect()->to('contenido_transferencia/'.$id_transferencia);

        }else{
            return redirect()->to('contenido_transferencia/'.$id_transferencia)->with('item_existente', 'El item ya se encuentra en la lista.');
        }
        
        
    }

    public function editarItem($id_items_orden)
    {
        $miItemsOrden = new ItemsOrdenModel();
        $items_orden = $miItemsOrden->find($id_items_orden);

        $miProducto = new ProductoModel();
        $producto = $miProducto->find($items_orden['id_producto']);
        $data = [
            'producto' => $producto,
            'items_orden' => $items_orden
        ];
        
        return view('items_orden/editarItem', $data);
    }

    public function actualizarItem($id)
    {
        $items_orden = new ItemsOrdenModel();
        $it_ord = $items_orden->getItemsOrden($id);

        $cant_requerida = $this->request->getPost('cant_requerida_editar');
        $id_orden = $this->request->getPost('id_orden');

        $postData = [
            'cant_requerida' => $cant_requerida
        ];
        $items_orden->updateItemsOrden($id, $postData);
        return redirect()->to('items_orden/'.$it_ord['id_orden']);//recibe id_orden
    }

    public function eliminarItem($id_contenido_transferencia){
        $objContenidoTransferencia = new ContenidoTransferenciaModel();
        $contenido_transferencia = $objContenidoTransferencia->getContenidoTransferencia($id_contenido_transferencia);

        $objContenidoTransferencia->eliminar($id_contenido_transferencia);
        return redirect()->to(base_url().'contenido_transferencia/'.$contenido_transferencia['id_transferencia']);//recibe id_orden
    }

}
