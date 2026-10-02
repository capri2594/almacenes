<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\RecursosModel;
use App\Models\ProveedorModel;

class Proveedor extends BaseController
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
            $proveedor = new ProveedorModel();
            $data = [
                'proveedores' => $proveedor->where('id_bodega', $id_bodega)->paginate(100),
                'pager' => $proveedor->pager,
                'id_bodega' => $id_bodega
            ];
            return view('proveedor/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function crear()
    {
        $razon_social = $this->request->getPost('razon_social');
        $ci_nit = $this->request->getPost('ci_nit');
        $tipo_proveedor = $this->request->getPost('tipo_proveedor');
        $nombre_contacto = $this->request->getPost('nombre_contacto');
        $celular_contacto = $this->request->getPost('celular_contacto');
        $direccion_proveedor = $this->request->getPost('direccion_proveedor');
        $telefono_proveedor = $this->request->getPost('telefono_proveedor');
        $nota_proveedor = $this->request->getPost('nota_proveedor');
        $id_bodega = $this->request->getPost('id_bodega');
        $postData = [
            'razon_social' => mb_strtoupper(trim($razon_social)),
            'ci_nit' => mb_strtoupper(trim($ci_nit)),
            'tipo_proveedor' => mb_strtoupper(trim($tipo_proveedor)),
            'estado_proveedor' => 1,
            'nombre_contacto' => mb_strtoupper(trim($nombre_contacto)),
            'celular_contacto' => mb_strtoupper(trim($celular_contacto)),
            'direccion_proveedor' => mb_strtoupper(trim($direccion_proveedor)),
            'telefono_proveedor' => mb_strtoupper(trim($telefono_proveedor)),
            'nota_proveedor' => mb_strtoupper(trim($nota_proveedor)),
            'id_bodega' => $id_bodega,
            'log_proveedor' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $proveedor = new ProveedorModel();
        $proveedor->createProveedor($postData);
        return redirect()->to('proveedor/'.$id_bodega);
    }

    public function editar($id)
    {
        $proveedor = new ProveedorModel();
        $data['proveedor'] = $proveedor->getProveedor($id);
        return view('proveedor/editar', $data);
    }

    public function actualizar($id)
    {
        $proveedor = new ProveedorModel();
        $log = $proveedor->getProveedor($id);

        $razon_social = $this->request->getPost('razon_social_editar');
        $ci_nit = $this->request->getPost('ci_nit_editar');
        $tipo_proveedor = $this->request->getPost('tipo_proveedor_editar');
        $nombre_contacto = $this->request->getPost('nombre_contacto_editar');
        $celular_contacto = $this->request->getPost('celular_contacto_editar');
        $direccion_proveedor = $this->request->getPost('direccion_proveedor_editar');
        $telefono_proveedor = $this->request->getPost('telefono_proveedor_editar');
        $nota_proveedor = $this->request->getPost('nota_proveedor_editar');
        $estado_proveedor = (int)$this->request->getPost('estado_proveedor_editar');
        
        $postData = [
            'razon_social' => mb_strtoupper(trim($razon_social)),
            'ci_nit' => mb_strtoupper(trim($ci_nit)),
            'tipo_proveedor' => mb_strtoupper(trim($tipo_proveedor)),
            'estado_proveedor' => $estado_proveedor,
            'nombre_contacto' => mb_strtoupper(trim($nombre_contacto)),
            'celular_contacto' => mb_strtoupper(trim($celular_contacto)),
            'direccion_proveedor' => mb_strtoupper(trim($direccion_proveedor)),
            'telefono_proveedor' => mb_strtoupper(trim($telefono_proveedor)),
            'nota_proveedor' => mb_strtoupper(trim($nota_proveedor)),
            'log_proveedor' => $log['log_proveedor'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $proveedor->updateProveedor($id, $postData);
        return redirect()->to('proveedor/'.$log['id_bodega']);
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
        $proveedor = new ProveedorModel();
        $log = $proveedor->getProveedor($id);
        $proveedor->deleteProveedor($id);
        return redirect()->to('proveedor/'.$log['id_bodega']);
    }

}
