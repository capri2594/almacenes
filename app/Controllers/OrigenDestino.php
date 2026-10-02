<?php

namespace App\Controllers;
use App\Models\RecursosModel;
use App\Models\OrigenDestinoModel;

class OrigenDestino extends BaseController
{
    public function __construct()
    {
        if(!((session()->isLoggedIn['nivel']==1) && (session()->isLoggedIn['estado_recurso']==1))){
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
            $orig_dest = new OrigenDestinoModel();
                $data = [
                    'orig_dest' => $orig_dest->where('tipo', '1')->paginate(10),
                    'pager' => $orig_dest->pager,
                ];                
            return view('origen_destino/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $descripcion = $this->request->getPost('descripcion');
        $postData = [
            'descripcion' => mb_strtoupper(trim($descripcion)),
            'estado_origen_destino' => 1,
            'tipo' => 1,
            'log_origen_destino' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
            ];
            $orig_dest = new OrigenDestinoModel();
            $orig_dest->createOrigenDestino($postData);
            return redirect()->to('/origen_destino');
    }
            
            
    public function editar($id)
    {
        $origen_destino = new OrigenDestinoModel();
        $recurso = new RecursosModel();
        $data['orig_dest'] = $origen_destino->getOrigenDestinoById($id);
        $data['usuarios'] = $recurso->getRecursosResponsables();
        return view('origen_destino/editar', $data);
    }

    public function actualizar($id)
    {
        $origen_destino = new OrigenDestinoModel();
        $log = $origen_destino->getOrigenDestinoById($id);
        
        $descripcion = $this->request->getPost('descripcion_editar');
        $estado_origen_destino = $this->request->getPost('estado_origen_destino_editar');
        $postData = [
            'descripcion' => mb_strtoupper(trim($descripcion)),
            'estado_origen_destino' => (int)$estado_origen_destino,
            'log_origen_destino' => $log['log_origen_destino'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $origen_destino->updateOrigenDestino($id, $postData);
        return redirect()->to('/origen_destino');
    }

    //destinos
    public function index2()
    {
        try {
            $orig_dest = new OrigenDestinoModel();
                $data = [
                    'orig_dest' => $orig_dest->where('tipo', '2')->paginate(10),
                    'pager' => $orig_dest->pager,
                ];                
            return view('origen_destino/index2', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear2()
    {
        $descripcion = $this->request->getPost('descripcion');
        $postData = [
            'descripcion' => mb_strtoupper(trim($descripcion)),
            'estado_origen_destino' => 1,
            'tipo' => 2,
            'log_origen_destino' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
            ];
            $orig_dest = new OrigenDestinoModel();
            $orig_dest->createOrigenDestino($postData);
            return redirect()->to('/origen_destino2');
    }

    public function editar2($id)
    {
        $origen_destino = new OrigenDestinoModel();
        $recurso = new RecursosModel();
        $data['orig_dest'] = $origen_destino->getOrigenDestinoById($id);
        $data['usuarios'] = $recurso->getRecursosResponsables();
        return view('origen_destino/editar2', $data);
    }

    public function actualizar2($id)
    {
        $origen_destino = new OrigenDestinoModel();
        $log = $origen_destino->getOrigenDestinoById($id);
        
        $descripcion = $this->request->getPost('descripcion_editar');
        $estado_origen_destino = $this->request->getPost('estado_origen_destino_editar');
        $postData = [
            'descripcion' => mb_strtoupper(trim($descripcion)),
            'estado_origen_destino' => (int)$estado_origen_destino,
            'log_origen_destino' => $log['log_origen_destino'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $origen_destino->updateOrigenDestino($id, $postData);
        return redirect()->to('/origen_destino2');
    }

}
