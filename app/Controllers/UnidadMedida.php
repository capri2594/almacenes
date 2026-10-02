<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\RecursosModel;
use App\Models\UnidadesMedidaModel;

class UnidadMedida extends BaseController
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

    public function index($id_bodega)
    {
        try {            
            $unidadesMedida = new UnidadesMedidaModel();
            $data = [
                'unidadesMedida' => $unidadesMedida->where('id_bodega', $id_bodega)->paginate(100),
                'pager' => $unidadesMedida->pager,
                'id_bodega' => $id_bodega
            ];
            return view('unidad_medida/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $nombre_unidad_medida = $this->request->getPost('nombre_unidad_medida');
        $id_bodega = $this->request->getPost('id_bodega');
        $postData = [
            'nombre_unidad_medida' => mb_strtoupper(trim($nombre_unidad_medida)),
            'estado_unidad_medida' => 1,
            'id_bodega' => $id_bodega,
            'log_unidad_medida' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $unidad_medida = new UnidadesMedidaModel();
        $unidad_medida->createUnidadMedida($postData);
        return redirect()->to('unidad_medida/'.$id_bodega);
    }

    public function editar($id)
    {
        $unidad_medida = new UnidadesMedidaModel();
        $data['unidad_medida'] = $unidad_medida->getUnidadMedida($id);
        return view('unidad_medida/editar', $data);
    }

    public function actualizar($id)
    {
        $unidad_medida = new UnidadesMedidaModel();
        $log = $unidad_medida->getUnidadMedida($id);
        $nombre_unidad_medida = $this->request->getPost('nombre_unidad_medida_editar');
        $estado_unidad_medida = $this->request->getPost('estado_unidad_medida_editar');
        $recurso_username = $this->request->getPost('recurso_username_editar');
        $postData = [
            'nombre_unidad_medida' => mb_strtoupper(trim($nombre_unidad_medida)),
            'estado_unidad_medida' => (int)$estado_unidad_medida,
            'recurso_username' => $recurso_username,
            'log_unidad_medida' => $log['log_unidad_medida'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $unidad_medida->updateUnidadMedida($id, $postData);
        return redirect()->to('unidad_medida/'.$log['id_bodega']);
    }

    public function gestionarBodega($id)
    {
        $unidad_medida = new BodegasModel();
        $recurso = new RecursosModel();
        
        $miBodega = $unidad_medida->getBodega($id);
        $usuario = $recurso->getRecurso($miBodega['recurso_username']);
        
        //si es ALMACENERO, esta habilitado y además el almacen esta asignado a el
        if((session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1) && (session()->isLoggedIn['username']==$usuario['username'])){
            $data['bodega'] = $miBodega;
            $data['usuario'] = $usuario;
            return view('unidad_medida/gestionarBodega', $data);
        }elseif((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1)){//si es admin pasa
            $data['bodega'] = $miBodega;
            $data['usuario'] = $usuario;
            return view('unidad_medida/gestionarBodega', $data);
        }else{//no cumple las condiciones
            return redirect('/');
        }

    }

    public function eliminar($id)
    {
        $unidad_medida = new UnidadesMedidaModel();
        $log = $unidad_medida->getUnidadMedida($id);
        $unidad_medida->deleteUnidadMedida($id);
        return redirect()->to('unidad_medida/'.$log['id_bodega']);
    }
}
