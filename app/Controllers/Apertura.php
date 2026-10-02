<?php

namespace App\Controllers;
use App\Models\AperturasModel;
use App\Models\RecursosModel;

class Apertura extends BaseController
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
            $objApertura = new AperturasModel();
            $aperturas = $objApertura->getAperturas();

                $data = [
                    'aperturas' => $aperturas,
                ];

            return view('apertura/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $codigo_apertura = $this->request->getPost('codigo_apertura');
        $descripcion_apertura = $this->request->getPost('descripcion_apertura');
        $postData = [
            'codigo_apertura' => mb_strtoupper(trim($codigo_apertura)),
            'descripcion_apertura' => mb_strtoupper(trim($descripcion_apertura)),
            'estado_apertura' => 1
        ];
        $objApertura = new AperturasModel();
        $objApertura->insertar($postData);
        return redirect()->to('/apertura');
    }

    public function editar($id_apertura)
    {
        $objApertura = new AperturasModel();
        $apertura = $objApertura->find($id_apertura);
        $data['apertura'] = $apertura;
        return view('apertura/editar', $data);
    }

    public function actualizar($id_apertura)
    {
        $objApertura = new AperturasModel();
        $apertura = $objApertura->find($id_apertura);
        
        $codigo_apertura = $this->request->getPost('codigo_apertura_editar');
        $descripcion_apertura = $this->request->getPost('descripcion_apertura_editar');
        $estado_apertura = $this->request->getPost('estado_apertura_editar');

        $postData = [
            'codigo_apertura' => mb_strtoupper(trim($codigo_apertura)),
            'descripcion_apertura' => mb_strtoupper(trim($descripcion_apertura)),
            'estado_apertura' => $estado_apertura,
        ];
        $objApertura->actualizar($id_apertura, $postData);
        return redirect()->to('/apertura');
    }
}
