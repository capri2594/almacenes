<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\RecursosModel;
use App\Models\ProveedorModel;
use App\Models\ProductoModel;
use App\Models\UnidadesMedidaModel;
use App\Models\RangosPartidaModel;
use App\Models\PartidaModel;

class Producto extends BaseController
{
    protected $db;

    public function __construct()
    {
        if(!((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1))){
            $session = session();
            $session->destroy();
        }else{            
            helper(['config']);
            helper('form');
            $this->db = db_connect();
        }
    }

    public function index($id_bodega)
    {
        try {
            $producto = new ProductoModel();
            $unidad_medida = new UnidadesMedidaModel();
            $bodega = new BodegasModel();
            $miReferenciaPartida = new RangosPartidaModel();
            $referenciaPartida = $miReferenciaPartida->findAll();
            $control_expiracion = $bodega->getBodega($id_bodega);
            $productos = $producto->where('id_bodega', $id_bodega)->paginate(10000);
            $data = [
                'productos' => $productos,
                'pager' => $producto->pager,
                'id_bodega' => $id_bodega,
                'unidades_medida' => $unidad_medida->getUnidadesBodega($id_bodega),
                'unidad' => $unidad_medida,
                'referenciaPartida' => $referenciaPartida,
                'control_expiracion' => $control_expiracion['control_expiracion']
            ];
            return view('producto/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function buscar($id_bodega)
    {
        try {
            $producto = new ProductoModel();
            $unidad_medida = new UnidadesMedidaModel();
            $bodega = new BodegasModel();
            $miReferenciaPartida = new RangosPartidaModel();
            $referenciaPartida = $miReferenciaPartida->findAll();
            $control_expiracion = $bodega->getBodega($id_bodega);
            $termino_buscar = mb_strtoupper(trim($this->request->getPost('termino_buscar')));

            $db = \Config\Database::connect();
            $sql = 'SELECT * FROM `productos` WHERE `id_bodega`= '.$id_bodega.' AND (`nombre_producto` LIKE "%'.$termino_buscar.'%" OR `id_partida` LIKE "%'.$termino_buscar.'%" OR `codigo` LIKE "%'.$termino_buscar.'%");';
            $productos = $db->query($sql)->getResultArray();

            $data = [
                'productos' => $productos,
                'pager' => $producto->pager,
                'id_bodega' => $id_bodega,
                'unidades_medida' => $unidad_medida->getUnidadesBodega($id_bodega),
                'unidad' => $unidad_medida,
                'referenciaPartida' => $referenciaPartida,
                'control_expiracion' => $control_expiracion['control_expiracion']
            ];
            return view('producto/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $nombre_producto = $this->request->getPost('nombre_producto');
        $id_bodega = $this->request->getPost('id_bodega');
        $id_partida = $this->request->getPost('id_partida');
        $id_unidad_medida = $this->request->getPost('id_unidad_medida');

        $objPartida = new PartidaModel();
        $partida = $objPartida->getPartida($id_partida);

        $objProducto = new ProductoModel();
        $producto = $objProducto->getUltimoProductoByIdBodega($id_bodega);
        $nombre_codigo = explode("-", $producto['codigo']);
        $ultimo_numero_codigo = (int)$nombre_codigo[2];
        if($ultimo_numero_codigo>0){
            $codigo = 'B-'.$id_bodega.'-'.$ultimo_numero_codigo + 1;
        }else{
            $codigo = 'B-'.$id_bodega.'-1';
        }
            $postData = [
            'nombre_producto' => mb_strtoupper(trim($nombre_producto)),
            'estado_producto' => 1,            
            'id_bodega' => $id_bodega,
            'id_unidad_medida' => $id_unidad_medida,
            'id_partida' => $id_partida,
            'codigo' => $codigo,
            'log_producto' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $objProducto->createProducto($postData);
        
        return redirect()->to('producto/'.$id_bodega);
    }

    public function editar($id)
    {
        $producto = new ProductoModel();
        $prod=$producto->getProducto($id);
        $unidad_medida = new UnidadesMedidaModel();
        $bodega = new BodegasModel();
        $control_expiracion = $bodega->getBodega($prod['id_bodega']);

        $miReferenciaPartida = new RangosPartidaModel();
        $referenciaPartida = $miReferenciaPartida->findAll();

        $data = [
            'producto' => $producto->getProducto($id),
            'unidades_medida' => $unidad_medida->getUnidadesBodega($prod['id_bodega']),
            'referenciaPartida' => $referenciaPartida,
            'control_expiracion' => $control_expiracion['control_expiracion']
        ];

        return view('producto/editar', $data);
    }

    public function actualizar($id)
    {
        $producto = new ProductoModel();
        $log = $producto->getProducto($id);

        $nombre_producto = $this->request->getPost('nombre_producto_editar');
        $estado_producto = $this->request->getPost('estado_producto_editar');
        
        $id_partida = $this->request->getPost('id_partida_editar');
        $id_unidad_medida = $this->request->getPost('id_unidad_medida_editar');
        $postData = [
            'nombre_producto' => mb_strtoupper(trim($nombre_producto)),
            'estado_producto' => (int)$estado_producto,
            'id_partida' => $id_partida,
            'id_unidad_medida' => $id_unidad_medida,
            'log_producto' => $log['log_producto'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];

        $producto->updateProducto($id, $postData);
        return redirect()->to('producto/'.$log['id_bodega']);
    }

    public function gestionarBodega($id)
    {
        $proveedor = new BodegasModel();
        $recurso = new RecursosModel();
        
        $miBodega = $proveedor->getBodega($id);
        $usuario = $recurso->getRecurso($miBodega['recurso_username']);
        
        //si es ALMACENERO, esta habilitado y además el almacen esta asignado a el
        if((session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1) && (session()->isLoggedIn['username']==$usuario['username'])){
            $data['bodega'] = $miBodega;
            $data['usuario'] = $usuario;
            return view('proveedor/gestionarBodega', $data);
        }elseif((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1)){//si es admin pasa
            $data['bodega'] = $miBodega;
            $data['usuario'] = $usuario;
            return view('proveedor/gestionarBodega', $data);
        }else{//no cumple las condiciones
            return redirect('/');
        }
    }

    public function eliminar($id)
    {
        $producto = new ProductoModel();
        $prod=$producto->getProducto($id);
        $producto->deleteProducto($id);

        return redirect()->to('producto/'.$prod['id_bodega']);
    }

}
