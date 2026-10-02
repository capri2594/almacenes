<?php

namespace App\Controllers;
use App\Models\PartidasModel;
use App\Models\SubPartidasModel;

class SubPartida extends BaseController
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

    public function index($id_partida)
    {
        try {
            $objPartida = new PartidasModel();
            $partida = $objPartida->getPartida($id_partida);

            $objSubPartida = new SubPartidasModel();
            $sub_partidas = $objSubPartida->getAllSubPartidaByIdPartida($id_partida);

                $data = [
                    'partida' => $partida,
                    'sub_partidas' => $sub_partidas,
                ];

            return view('sub_partida/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $id_partida = $this->request->getPost('id_partida');
        $descripcion = $this->request->getPost('descripcion');
        $postData = [
            'id_partida' => trim($id_partida),
            'descripcion' => trim($descripcion)
        ];
        $objSubPartida = new SubPartidasModel();
        $objSubPartida->insertar($postData);
        return redirect()->to('/sub_partida//'.$id_partida);
    }

    public function editar($id_rangos_partida)
    {
        $objSubPartida = new SubPartidasModel();
        $sub_partida = $objSubPartida->find($id_rangos_partida);

        $data['sub_partida'] = $sub_partida;
        return view('sub_partida/editar', $data);
    }

    public function actualizar($id_rangos_partida)
    {
        $objSubPartida = new SubPartidasModel();
        $sub_partida = $objSubPartida->find($id_rangos_partida);
        
        $descripcion = $this->request->getPost('descripcion_editar');

        $postData = [
            'descripcion' => trim($descripcion)
        ];
        $objSubPartida->actualizar($id_rangos_partida, $postData);
        return redirect()->to('/sub_partida//'.$sub_partida['id_partida']);
    }

}
