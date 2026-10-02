<?php

namespace App\Controllers;

use App\Models\AdquisicionProductoModel;
use App\Models\OrdenModel;
use App\Models\UsuarioAperturaModel;
use App\Models\UsuarioBodegaModel;
use App\Models\ProductoModel;
use App\Models\ItemsOrdenModel;
use App\Models\AdquisicionProductoTieneAppModel;

class ItemsOrden extends BaseController
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
    public function index($id_orden)
    {
        try {
            $miOrden = new OrdenModel();
            $orden = $miOrden->find($id_orden);
            if($orden['recurso_username'] != session()->isLoggedIn['username']){
                return redirect()->to('orden/');
            }
            $misItems = new ItemsOrdenModel();
            $items_orden = $misItems->getItemsOrdenByIdOrden($id_orden);
            
            $objUsuarioApertura = new UsuarioAperturaModel();
            $usuario_apertura = $objUsuarioApertura->getAperturaByUsername(session()->isLoggedIn['username']);

            $objAdquisicionProductoTiene = new AdquisicionProductoTieneAppModel();
            $adq_prod_tiene = $objAdquisicionProductoTiene->getByIdBodegaIdAperturaIdSubApertura($orden['id_bodega'], $usuario_apertura[0]['id_apertura'], $usuario_apertura[0]['id_sub_apertura']);
            if (empty($adq_prod_tiene) && !empty($usuario_apertura) && $usuario_apertura[0]['id_sub_apertura'] != 0) {
                $adq_prod_tiene = $objAdquisicionProductoTiene->getByIdBodegaIdAperturaIdSubApertura($orden['id_bodega'], $usuario_apertura[0]['id_apertura'], 0);
            }
            $data = [
                'id_orden' => $id_orden,
                'items_orden' => $items_orden,
                'adq_prod_tiene' => $adq_prod_tiene,
                //'miProducto' => $miProducto,
                //'productos' => $productos
            ];
            
            return view('items_orden/index',$data);
            
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function cargarProducto($id_orden, $id_adquisicion_producto_tiene_app){
        $objAdquisicionProductoTiene = new AdquisicionProductoTieneAppModel();
        $adq_prod_tiene_app = $objAdquisicionProductoTiene->getAdquisicionProductoTieneAppById($id_adquisicion_producto_tiene_app);

        $miProducto = new ProductoModel();
        $producto = $miProducto->find($adq_prod_tiene_app['id_producto']);

        $objUsuarioApertura = new UsuarioAperturaModel();
        $usuario_apertura = $objUsuarioApertura->getAperturaByUsername(session()->isLoggedIn['username']);

        $objAdqProd = new AdquisicionProductoModel();
        $cant_existente = $adq_prod_tiene_app['cantidad'];
        //$cant_existente = $objAdqProd->getCantExistenteByProducto($id_producto, $usuario_apertura[0]['id_apertura'], $usuario_apertura[0]['id_sub_apertura']);
        $data = [
            'producto' => $producto,
            'cant_existente' => $cant_existente,
            'id_orden' => $id_orden,
            'usuario_apertura' => $usuario_apertura,
        ];
        return view('items_orden/cargarItem', $data);
    }
    public function addProducto()
    {
        $miItemsOrden = new ItemsOrdenModel();
        $cant_requerida = $this->request->getPost('cant_requerida');
        $id_orden = $this->request->getPost('id_orden');
        $id_producto = $this->request->getPost('id_producto');
        
        $duplicado = $miItemsOrden->verificaDuplicidad($id_orden, $id_producto);
        if(count($duplicado) > 0){
            return redirect()->to('items_orden/'.$id_orden);
        }
        $postData = [
            'id_producto' => $id_producto,
            'id_orden' => $id_orden,
            'cant_requerida' => $cant_requerida,
        ];
        $miItemsOrden->createItemsOrden($postData);

        return redirect()->to('items_orden/'.$id_orden);
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

    public function eliminarItem($id_items_orden){
        $items_orden = new ItemsOrdenModel();
        $it_ord = $items_orden->getItemsOrden($id_items_orden);

        $items_orden->deleteItemsOrden($id_items_orden);
        return redirect()->to(base_url().'items_orden/'.$it_ord['id_orden']);//recibe id_orden
    }

}
