<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\NroAdquisicionModel;
use App\Models\ProductoModel;
use App\Models\AdquisicionProductoModel;

class AdquisicionProducto extends BaseController
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
    public function index($id_bodega, $id_nro_adquisicion)
    {        
        try {
            $miBodega = new BodegasModel();
            $bodega = $miBodega->find($id_bodega);

            $miNroAdquisicion = new NroAdquisicionModel();
            $nro_adquisicion = $miNroAdquisicion->find($id_nro_adquisicion);
            $miAdqProd = new AdquisicionProductoModel();
            $itemsAdquisicion = $miAdqProd->getItemsAdqProd($id_nro_adquisicion);
            
            $miProducto = new ProductoModel();
            $productos = $miProducto->where('id_bodega', $id_bodega)->where('estado_producto', 1)->orderBy('nombre_producto','ASC')->findAll();
            $data = [
                'miProducto' => $miProducto,
                'productos' => $productos,
                'id_bodega' => $id_bodega,
                'id_nro_adquisicion' => $id_nro_adquisicion,
                'bodega' => $bodega,
                'nro_adquisicion' => $nro_adquisicion,
                'itemsAdquisicion' => $itemsAdquisicion,
            ];
            return view('adquisicion_producto/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function cargarProducto($id_nro_adquisicion, $id_producto){
        $miProducto = new ProductoModel();
        $producto = $miProducto->find($id_producto);
        $data = [
            'producto' => $producto,
            'id_nro_adquisicion' => $id_nro_adquisicion
        ];
        return view('adquisicion_producto/cargarItem', $data);
    }
    public function addProducto()
    {
        $cant_ingreso = $this->request->getPost('cant_ingreso');
        $precio_adquisicion = $this->request->getPost('precio_adquisicion');
        $id_nro_adquisicion = $this->request->getPost('id_nro_adquisicion');
        $id_producto = $this->request->getPost('id_producto');
        
        $miNro_adquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $miNro_adquisicion->find($id_nro_adquisicion);
        $ingreso_valorado = round(($cant_ingreso*$precio_adquisicion),2);

        /* para la ultima fila*/
        $obj = new AdquisicionProducto();
        $ultimaFila = $obj->getLastAdquisicionByIdProducto($nro_adquisicion['id_bodega'], $id_producto);
        if(is_null($ultimaFila)){
            $saldo_fisico = $cant_ingreso;
            $saldo_valorado = $ingreso_valorado;
        }
        else{
            $saldo_fisico = $ultimaFila['saldo_fisico'] + $cant_ingreso;
            $saldo_valorado = $ultimaFila['saldo_valorado'] + $ingreso_valorado;
        } 
        /* fin para la ultima fila*/

        $postData = [
            'tipo_movimiento' => 1,
            'id_nro_adquisicion' => mb_strtoupper(trim($id_nro_adquisicion)),
            'id_producto' => mb_strtoupper(trim($id_producto)),
            'id_bodega' => $nro_adquisicion['id_bodega'],
            'cant_ingreso' => mb_strtoupper(trim($cant_ingreso)),
            'cant_existente' => mb_strtoupper(trim($cant_ingreso)),
            'precio_adquisicion' => mb_strtoupper(trim($precio_adquisicion)),
            'saldo_fisico' => $saldo_fisico,
            'ing_valorado' => $ingreso_valorado,
            'saldo_valorado' => $saldo_valorado,
            'log_adquisicion_producto' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $miAdquisicionProducto = new AdquisicionProductoModel();
        $miAdquisicionProducto->createAdquisicionProducto($postData);

        return redirect()->to('adquisicion_producto/'.$nro_adquisicion['id_bodega'].'/'.$id_nro_adquisicion);
    }

    public function editarItem($id_adquisicion_producto)
    {
        $miAdquisicionProducto = new AdquisicionProductoModel();
        $adquisicion_producto = $miAdquisicionProducto->find($id_adquisicion_producto);
        $miProducto = new ProductoModel();
        $producto = $miProducto->find($adquisicion_producto['id_producto']);
        $data = [
            'producto' => $producto,
            'adquisicion_producto' => $adquisicion_producto
        ];
        
        return view('adquisicion_producto/editarItem', $data);
    }

    public function actualizarItem($id)
    {
        $adquisicion_producto = new AdquisicionProductoModel();
        $log = $adquisicion_producto->getAdquisicionProducto($id);

        $miNroAdquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $miNroAdquisicion->find($log['id_nro_adquisicion']);

        $cant_ingreso = $this->request->getPost('cant_ingreso_editar');
        $precio_adquisicion = $this->request->getPost('precio_adquisicion_editar');

        $ing_valorado = round(($cant_ingreso*$precio_adquisicion),2);
        $postData = [
            'cant_ingreso' => mb_strtoupper(trim($cant_ingreso)),
            'precio_adquisicion' => mb_strtoupper(trim($precio_adquisicion)),
            'cant_existente' => mb_strtoupper(trim($cant_ingreso)),
            'ing_valorado' => $ing_valorado,
            'log_adquisicion_producto' => $log['log_adquisicion_producto'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $adquisicion_producto->updateAdquisicionProducto($id, $postData);
        return redirect()->to('adquisicion_producto/'.$nro_adquisicion['id_bodega'].'/'.$log['id_nro_adquisicion']);//recibe id_bodega, id_nro_adquisicion
    }

    public function eliminarItem($id_adquisicion_producto){
        $adquisicion_producto = new AdquisicionProductoModel();
        $adq_prod = $adquisicion_producto->getAdquisicionProducto($id_adquisicion_producto);

        $miNroAdquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $miNroAdquisicion->find($adq_prod['id_nro_adquisicion']);

        $adquisicion_producto->deleteAdquisicionProducto($id_adquisicion_producto);
        return redirect()->to(base_url().'adquisicion_producto/'.$nro_adquisicion['id_bodega'].'/'.$adq_prod['id_nro_adquisicion']);//recibe id_bodega, id_nro_adquisicion
    }

    public function getLastAdquisicionByIdProducto($id_bodega, $id_producto){
        $adq_prod = new AdquisicionProductoModel();
        $ultimaFila = $adq_prod->getLastAdquisicionByIdProducto($id_bodega, $id_producto);
        return $ultimaFila;
    }

}
