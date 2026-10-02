<?php

namespace App\Controllers;
use App\Models\AperturasModel;
use App\Models\SubAperturasModel;

class SubApertura extends BaseController
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

    public function index($id_apertura)
    {
        try {
            $objApertura = new AperturasModel();
            $apertura = $objApertura->getApertura($id_apertura);

            $objSubApertura = new SubAperturasModel();
            $sub_aperturas = $objSubApertura->getAllSubAperturaByIdApertura($id_apertura);

                $data = [
                    'apertura' => $apertura,
                    'sub_aperturas' => $sub_aperturas,
                ];

            return view('sub_apertura/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $id_apertura = $this->request->getPost('id_apertura');
        $codigo_sub_apertura = $this->request->getPost('codigo_sub_apertura');
        $descripcion_sub_apertura = $this->request->getPost('descripcion_sub_apertura');
        $postData = [
            'id_apertura' => trim($id_apertura),
            'codigo_sub_apertura' => mb_strtoupper(trim($codigo_sub_apertura)),
            'descripcion_sub_apertura' => mb_strtoupper(trim($descripcion_sub_apertura)),
            'estado_sub_apertura' => 1
        ];
        $objSubApertura = new SubAperturasModel();
        $objSubApertura->insertar($postData);
        return redirect()->to('/sub_apertura//'.$id_apertura);
    }

    public function editar($id_sub_apertura)
    {
        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->getSubApertura($id_sub_apertura);

        $data['sub_apertura'] = $sub_apertura;
        return view('sub_apertura/editar', $data);
    }

    public function actualizar($id_sub_apertura)
    {
        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->find($id_sub_apertura);
        
        $codigo_sub_apertura = $this->request->getPost('codigo_sub_apertura_editar');
        $descripcion_sub_apertura = $this->request->getPost('descripcion_sub_apertura_editar');
        $estado_sub_apertura = $this->request->getPost('estado_sub_apertura_editar');

        $postData = [
            'codigo_sub_apertura' => mb_strtoupper(trim($codigo_sub_apertura)),
            'descripcion_sub_apertura' => mb_strtoupper(trim($descripcion_sub_apertura)),
            'estado_sub_apertura' => $estado_sub_apertura,
        ];
        $objSubApertura->actualizar($id_sub_apertura, $postData);
        return redirect()->to('/sub_apertura//'.$sub_apertura['id_apertura']);
    }

}
