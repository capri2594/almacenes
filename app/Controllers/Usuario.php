<?php

namespace App\Controllers;
use App\Models\RecursosModel;
use App\Models\BodegasModel;
use App\Models\UsuarioBodegaModel;
use App\Models\AperturasModel;
use App\Models\SubAperturasModel;
use App\Models\UsuarioAperturaModel;

class Usuario extends BaseController
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
            $recursos = new recursosModel();
                $data = [
                    'recursos' => $recursos->paginate(100),
                    'pager' => $recursos->pager,
                ];                
            return view('usuario/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function editar($username)
    {
        $recurso = new recursosModel();
        $data['usuario'] = $recurso->getRecurso($username);
        return view('usuario/editar', $data);
    }

    public function actualizar($username)
    {        
        $nivel = $this->request->getPost('nivel');
        $estado_recurso = $this->request->getPost('estado_recurso');
        $postData = [
            'nivel' => mb_strtoupper(trim($nivel)),
            'estado_recurso' => (int)$estado_recurso
        ];

        $recurso = new recursosModel();
        $recurso->updateRecurso($username, $postData);
        return redirect()->to('/usuario');
    }

    public function asignar_bodega($username){
        $recurso = new recursosModel();
        $data['usuario'] = $recurso->getRecurso($username);

        $bodegas = new BodegasModel();
        $data['bodegas'] = $bodegas->getBodegasHabilitadas();

        $objUsuarioBodega = new UsuarioBodegaModel();
        $data['usuario_bodega'] = $objUsuarioBodega->getBodegaByUsername($username);

        return view('usuario/asignar_bodega', $data);
    }

    public function asignar_bodega_guardar(){
        $id_bodega = $this->request->getPost('id_bodega');
        $username = $this->request->getPost('username');

        $data = [
            'id_bodega' => $id_bodega,
            'username' => $username,
        ];
        $objUsuarioBodega = new UsuarioBodegaModel();
        $objUsuarioBodega->crear($data);
        return redirect()->to('usuario/asignar_bodega/'.$username);
    }

    public function eliminar_acceso($id_usuario_bodega){
        $objUsuarioBodega = new UsuarioBodegaModel();
        $usuario_bodega = $objUsuarioBodega->getUsuarioBodega($id_usuario_bodega);
        $objUsuarioBodega->eliminar($id_usuario_bodega);
        
        return redirect()->to('usuario/asignar_bodega/'.$usuario_bodega['username']);
    }

    public function asignar_apertura($username){
        $recurso = new recursosModel();
        $data['usuario'] = $recurso->getRecurso($username);

        $aperturas = new AperturasModel();
        $data['aperturas'] = $aperturas->getAperturasHabilitadas();

        $objUsuarioApertura = new UsuarioAperturaModel();
        $data['usuario_apertura'] = $objUsuarioApertura->getAperturaByUsername($username);

        return view('usuario/asignar_apertura', $data);
    }

    public function asignar_apertura_guardar(){
        $id_apertura = $this->request->getPost('id_apertura');
        $id_sub_apertura = $this->request->getPost('id_sub_apertura');
        $username = $this->request->getPost('username');

        $objUsuarioApertura = new UsuarioAperturaModel();

        //verificar si el usuario ya tiene una aperta
        $usuario_apertura = $objUsuarioApertura->getAperturaByUsername($username);
        if(count($usuario_apertura)==0){
            $data = [
                'username' => $username,
                'id_apertura' => $id_apertura,
                'id_sub_apertura' => $id_sub_apertura
            ];
            $objUsuarioApertura->crear($data);
            return redirect()->to('usuario/asignar_apertura/'.$username);
        }else{
            return redirect()->to('usuario/asignar_apertura/'.$username)->with('error_usuario_con_app', 'El usuario ya tiene una apertura asignada, solo puede tener 1 apertura.');
        }
    }

    public function eliminar_acceso_apertura($id_usuario_apertura){
        $objUsuarioApertura = new UsuarioAperturaModel();
        $usuario_apertura = $objUsuarioApertura->getUsuarioApertura($id_usuario_apertura);
        $objUsuarioApertura->eliminar($id_usuario_apertura);
        
        return redirect()->to('usuario/asignar_apertura/'.$usuario_apertura['username']);
    }

    public function cargar_sub_apertura($id_apertura){
        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->getSubAperturaByIdApertura($id_apertura);

        $html='<select class="form-control" name="id_sub_apertura" id="id_sub_apertura">';
        $html.='<option value="">Seleccione</option>';

        foreach ($sub_apertura as $key => $value)
          $html.='<option value="'.$value['id_sub_apertura'].'">'.$value['codigo_sub_apertura'].'-'.$value['descripcion_sub_apertura'].' </option>';
        $html.='</select>';
        echo $html;
    }

}
