<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\AperturasModel;
use App\Models\SubAperturasModel;
use App\Models\AdquisicionProductoTieneAppModel;

class AdquisicionProductoTieneApp extends BaseController
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
            $miBodega = new BodegasModel();
            $bodega = $miBodega->find($id_bodega);
            
            $data = [
                'id_bodega' => $id_bodega,
                'bodega' => $bodega,
            ];
            return view('adquisicion_producto_tiene_app/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public function modificar($id_bodega)
    {        
        try {
            $miBodega = new BodegasModel();
            $bodega = $miBodega->find($id_bodega);
            
            $data = [
                'id_bodega' => $id_bodega,
                'bodega' => $bodega,
            ];
            return view('adquisicion_producto_tiene_app/modificar', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
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

    public function generar_saldos($id_bodega){
        $id_apertura = $this->request->getGet('id_apertura')!=''?$this->request->getGet('id_apertura'):0;
        $id_sub_apertura = $this->request->getGet('id_sub_apertura')!=''?$this->request->getGet('id_sub_apertura'):0;

        $objApertura = new AperturasModel();
        $apertura = $objApertura->find($id_apertura);

        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->find($id_sub_apertura);

        $objBodega = new BodegasModel();
        $bodega = $objBodega->find($id_bodega);

        $objAdquisicionProductoTieneApp = new AdquisicionProductoTieneAppModel();
        $adquisicion_producto_tiene_app = $objAdquisicionProductoTieneApp->getByIdBodegaIdAperturaIdSubApertura($id_bodega, $id_apertura, $id_sub_apertura);
        $data = [
            'adquisicion_producto_tiene_app' => $adquisicion_producto_tiene_app,
            'id_bodega' => $id_bodega,
            'apertura' => $apertura,
            'sub_apertura' => $sub_apertura,
            'bodega' => $bodega
        ];
        return view('adquisicion_producto_tiene_app/generar_saldos_reporte', $data);
    }

    public function generar_saldos_modificar($id_bodega){
        $id_apertura = $this->request->getGet('id_apertura')!=''?$this->request->getGet('id_apertura'):0;
        $id_sub_apertura = $this->request->getGet('id_sub_apertura')!=''?$this->request->getGet('id_sub_apertura'):0;

        $objApertura = new AperturasModel();
        $apertura = $objApertura->find($id_apertura);

        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->find($id_sub_apertura);

        $objBodega = new BodegasModel();
        $bodega = $objBodega->find($id_bodega);

        $objAdquisicionProductoTieneApp = new AdquisicionProductoTieneAppModel();
        $adquisicion_producto_tiene_app = $objAdquisicionProductoTieneApp->getByIdBodegaIdAperturaIdSubApertura($id_bodega, $id_apertura, $id_sub_apertura);
        $data = [
            'adquisicion_producto_tiene_app' => $adquisicion_producto_tiene_app,
            'id_bodega' => $id_bodega,
            'apertura' => $apertura,
            'sub_apertura' => $sub_apertura,
            'bodega' => $bodega
        ];
        return view('adquisicion_producto_tiene_app/generar_saldos_reporte_modificar', $data);
    }

    public function modificar_saldos($id_bodega){
        
        $objAdquisicionProductoTieneApp = new AdquisicionProductoTieneAppModel();
                
        $total_items = $this->request->getPost('total_items');
        for ($i=1; $i <= $total_items; $i++) { 
            $objAdquisicionProductoTieneApp->update($this->request->getPost('id_i_'.$i), ['cantidad' => $this->request->getPost('cant_i_'.$i)]);
        }
        return redirect()->to(base_url('adquisicion_producto_tiene_app/modificar/'.$id_bodega));
    }


}
