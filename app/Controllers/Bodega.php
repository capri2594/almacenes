<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\RecursosModel;

class Bodega extends BaseController
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

    public function index()
    {
        try {
            $recursos = new RecursosModel();
            $bodegas = new BodegasModel();

            if((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1)){
                $data = [
                    'bodegas' => $bodegas->paginate(100),
                    'pager' => $bodegas->pager,
                    'usuarios' => $recursos->getRecursosResponsables()
                ];
            }else{
                $data = [
                    'bodegas' => $bodegas->where('recurso_username', session()->isLoggedIn['username'])->paginate(100),
                    'pager' => $bodegas->pager,
                    'usuarios' => $recursos->getRecursosResponsables()
                ];
            }

            return view('bodega/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $nombre_bodega = $this->request->getPost('nombre_bodega');
        $direccion_bodega = $this->request->getPost('direccion_bodega');
        
        $atender_solicitud = $this->request->getPost('atender_solicitud');
        $recurso_username = $this->request->getPost('recurso_username');
        $postData = [
            'nombre_bodega' => mb_strtoupper(trim($nombre_bodega)),
            'direccion_bodega' => mb_strtoupper(trim($direccion_bodega)),
            'estado_bodega' => 1,
            'control_expiracion' => 0,
            'atender_solicitud' => (int)$atender_solicitud,
            'recurso_username' => $recurso_username,
            'log_bodega' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $bodega = new BodegasModel();
        $bodega->createBodega($postData);
        return redirect()->to('/bodega');
    }

    public function editar($id)
    {
        $bodega = new BodegasModel();
        $recurso = new RecursosModel();
        $data['bodega'] = $bodega->getBodega($id);
        $data['usuarios'] = $recurso->getRecursosResponsables();
        return view('bodega/editar', $data);
    }

    public function actualizar($id)
    {
        $bodega = new BodegasModel();
        $log = $bodega->getBodega($id);
        
        $nombre_bodega = $this->request->getPost('nombre_bodega_editar');
        $direccion_bodega = $this->request->getPost('direccion_bodega_editar');
        $control_expiracion = $this->request->getPost('control_expiracion_editar');
        $atender_solicitud = $this->request->getPost('atender_solicitud_editar');
        $estado_bodega = $this->request->getPost('estado_bodega_editar');
        $recurso_username = $this->request->getPost('recurso_username_editar');
        $postData = [
            'nombre_bodega' => mb_strtoupper(trim($nombre_bodega)),
            'direccion_bodega' => mb_strtoupper(trim($direccion_bodega)),
            'estado_bodega' => (int)$estado_bodega,
            'control_expiracion' => (int)$control_expiracion,
            'atender_solicitud' => (int)$atender_solicitud,
            'recurso_username' => $recurso_username,
            'log_bodega' => $log['log_bodega'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $bodega->updateBodega($id, $postData);
        return redirect()->to('/bodega');
    }

    public function gestionarBodega($id)
    {
        $bodega = new BodegasModel();
        $recurso = new RecursosModel();
        
        $miBodega = $bodega->getBodega($id);
        $usuario = $recurso->getRecurso($miBodega['recurso_username']);
        
        //si es ALMACENERO, esta habilitado y además el almacen esta asignado a el
        if((session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1) && (session()->isLoggedIn['username']==$usuario['username'])){
            $data['bodega'] = $miBodega;
            $data['usuario'] = $usuario;
            return view('bodega/gestionarBodega', $data);
        }elseif((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1)){//si es admin pasa
            $data['bodega'] = $miBodega;
            $data['usuario'] = $usuario;
            return view('bodega/gestionarBodega', $data);
        }else{//no cumple las condiciones
            return redirect('/');
        }

    }

}
