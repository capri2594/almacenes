<?php

namespace App\Controllers;
use App\Models\PartidasModel;


class Partida extends BaseController
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
            $objPartida = new PartidasModel();
            $partidas = $objPartida->getPartidas();

                $data = [
                    'partidas' => $partidas,
                ];

            return view('partida/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $id_partida = $this->request->getPost('id_partida');
        $descripcion = $this->request->getPost('descripcion');
        $postData = [
            'id_partida' => mb_strtoupper(trim($id_partida)),
            'descripcion' => trim($descripcion)
        ];
        $objPartida = new PartidasModel();
        $objPartida->insertar($postData);
        return redirect()->to('/partida');
    }

    public function editar($id_partida)
    {
        $objPartida = new PartidasModel();
        $partida = $objPartida->find($id_partida);
        $data['partida'] = $partida;
        return view('partida/editar', $data);
    }

    public function actualizar($id_partida)
    {
        $objPartida = new PartidasModel();        
        $id_partida_nueva = $this->request->getPost('id_partida_editar');
        $descripcion = $this->request->getPost('descripcion_editar');

        $postData = [
            'id_partida' => mb_strtoupper(trim($id_partida_nueva)),
            'descripcion' => trim($descripcion)
        ];
        $objPartida->actualizar($id_partida, $postData);
        return redirect()->to('/partida');
    }
}
