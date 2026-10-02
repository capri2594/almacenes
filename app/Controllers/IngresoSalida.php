<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\IngresoSalidaModel;
use App\Models\ProveedorModel;
use App\Models\AdquisicionProductoModel;
use App\Models\AperturasModel;
use App\Models\SubAperturasModel;
use App\Models\AdquisicionProductoTieneAppModel;
use App\Models\RangosPartidaModel;
use App\Models\UnidadesMedidaModel;
use App\Models\IngresoSalidaItemsModel;

class IngresoSalida extends BaseController
{
    protected $db;

    public function __construct()
    {
        if(!((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2) && (session()->isLoggedIn['estado_recurso']==1))){
            $session = session();
            $session->destroy();
        }else{            
            helper(['config']);
            helper('form');
            $this->db = db_connect();
        }
    }

    public function index($id_bodega)
    {
        try {
            $proveedor = new ProveedorModel();
            $bodega = new BodegasModel();
            $ingreso_salida = new IngresoSalidaModel();
            $ojbApertura = new AperturasModel();
            $dataAperturaGeneral = $ojbApertura->getAperturasHabilitadas();
            $data = [
                'ingresos_salidas' => $ingreso_salida->orderBy('nro_correlativo', 'DESC')->where('id_bodega', $id_bodega)->paginate(100),
                'pager' => $ingreso_salida->pager,
                'id_bodega' => $id_bodega,
                'dataAperturaGeneral' => $dataAperturaGeneral,
                'bodega' => $bodega->find($id_bodega),
                'proveedor' => $proveedor,
                'proveedores' => $proveedor->getProveedorBodega($id_bodega)
            ];
            
            return view('ingreso_salida/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    
    public function crear()
    {        
        $id_bodega = $this->request->getPost('id_bodega');
        $fecha_ingreso_salida = $this->request->getPost('fecha_ingreso_salida');
        $id_proveedor = $this->request->getPost('id_proveedor');
        $id_apertura = $this->request->getPost('id_apertura');
        $id_sub_apertura = $this->request->getPost('id_sub_apertura');
        $hoja_ruta = $this->request->getPost('hoja_ruta');
        $tipo_documento = $this->request->getPost('tipo_documento');
        $nro_tipo_documento = $this->request->getPost('nro_tipo_documento');
        $doc_constancia = $this->request->getPost('doc_constancia');
        $nro_doc_constancia = $this->request->getPost('nro_doc_constancia');
        $observaciones = $this->request->getPost('observaciones');
        $tipo_adquisicion = $this->request->getPost('tipo_adquisicion');
        
        //para el nro_correlativo
        $MiBodega = new BodegasModel();
        $bodega = $MiBodega->find($id_bodega);
        //fin nro_correlativo

        $postData = [
            'id_bodega' => $id_bodega,
            'fecha_ingreso_salida' => mb_strtoupper(trim($fecha_ingreso_salida)),
            'id_proveedor' => mb_strtoupper(trim($id_proveedor)),
            'id_apertura' => trim($id_apertura),
            'id_sub_apertura' => trim($id_sub_apertura),
            'hoja_ruta' => mb_strtoupper(trim($hoja_ruta)),
            'tipo_documento' => mb_strtoupper(trim($tipo_documento)),
            'nro_tipo_documento' => mb_strtoupper(trim($nro_tipo_documento)),
            'doc_constancia' => mb_strtoupper(trim($doc_constancia)),
            'nro_doc_constancia' => mb_strtoupper(trim($nro_doc_constancia)),
            'observaciones' => mb_strtoupper(trim($observaciones)),
            'tipo_adquisicion' => mb_strtoupper(trim($tipo_adquisicion)),
            'id_gestion' => 1,
            'nro_correlativo' => $bodega['contador_ingreso_salida'],
            'fecha_ingreso_sistema' => date('Y-m-d'),
            'estado_ingreso_salida' => 0,
            'log_ingreso_salida' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $ingreso_salida = new IngresoSalidaModel();
        $ingreso_salida->createIngresoSalida($postData);

        //Incrementando contador en la bodega
        $dataBodega = [
            'contador_ingreso_salida' => ($bodega['contador_ingreso_salida'] + 1)
        ];
        
        $MiBodega->updateBodega($id_bodega, $dataBodega);
        //Fin incremento

        return redirect()->to('ingreso_salida/'.$id_bodega);
    }

    public function editar($id)
    {
        $ingreso_salida = new IngresoSalidaModel();
        $in_out=$ingreso_salida->getIngresoSalida($id);

        $proveedor = new ProveedorModel();
        $data = [
            'ingreso_salida' => $in_out,
            'proveedor' => $proveedor,
            'proveedores' => $proveedor->getProveedorBodega($in_out['id_bodega']),

        ];
        return view('ingreso_salida/editar', $data);
    }

    public function actualizar($id)
    {
        $ingreso_salida = new IngresoSalidaModel();
        $log = $ingreso_salida->getIngresoSalida($id);

        $fecha_ingreso_salida = $this->request->getPost('fecha_ingreso_salida_editar');
        $id_proveedor = $this->request->getPost('id_proveedor_editar');
        $id_apertura = $this->request->getPost('id_apertura_editar');
        $id_sub_apertura = $this->request->getPost('id_sub_apertura_editar');
        $hoja_ruta = $this->request->getPost('hoja_ruta_editar');
        $tipo_documento = $this->request->getPost('tipo_documento_editar');
        $nro_tipo_documento = $this->request->getPost('nro_tipo_documento_editar');
        $doc_constancia = $this->request->getPost('doc_constancia_editar');
        $nro_doc_constancia = $this->request->getPost('nro_doc_constancia_editar');
        $observaciones = $this->request->getPost('observaciones_editar');
        $tipo_adquisicion = $this->request->getPost('tipo_adquisicion_editar');

        $postData = [
            'fecha_ingreso_salida' => mb_strtoupper(trim($fecha_ingreso_salida)),
            'id_proveedor' => mb_strtoupper(trim($id_proveedor)),
            'id_apertura' => mb_strtoupper(trim($id_apertura)),
            'id_sub_apertura' => mb_strtoupper(trim($id_sub_apertura)),
            'hoja_ruta' => mb_strtoupper(trim($hoja_ruta)),
            'tipo_documento' => mb_strtoupper(trim($tipo_documento)),
            'nro_tipo_documento' => mb_strtoupper(trim($nro_tipo_documento)),
            'doc_constancia' => mb_strtoupper(trim($doc_constancia)),
            'nro_doc_constancia' => mb_strtoupper(trim($nro_doc_constancia)),
            'observaciones' => mb_strtoupper(trim($observaciones)),
            'tipo_adquisicion' => mb_strtoupper(trim($tipo_adquisicion)),
            'log_ingreso_salida' => $log['log_ingreso_salida'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $ingreso_salida->updateIngresoSalida($id, $postData);
        return redirect()->to('ingreso_salida/'.$log['id_bodega']);
    }

    public function imprimir_ingreso_materiales($id_ingreso_salida){
        $objIngresoSalida = new IngresoSalidaModel();
        $ingreso_salida = $objIngresoSalida->getIngresoSalida($id_ingreso_salida);

        $objProveedor = new ProveedorModel();
        $proveedor = $objProveedor->getProveedor($ingreso_salida['id_proveedor']);

        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($ingreso_salida['id_bodega']);

        $objIngresoSalidaItems = new IngresoSalidaItemsModel();
        $items = $objIngresoSalidaItems->getIngresoSalidaItemsByIdIngresoSalida($id_ingreso_salida);

        $data = [
            'ingreso_salida' => $ingreso_salida,
            'proveedor' => $proveedor,
            'bodega' => $bodega,
            'items' => $items,
        ];
        return view('ingreso_salida/imprimir_ingreso_materiales',$data);
    }

    public function subir($id_ingreso_salida){
        $miNro_adquisicion = new IngresoSalidaModel();
        $ingreso_salida = $miNro_adquisicion->getIngresoSalida($id_ingreso_salida);
        
        $miBodega = new BodegasModel();
        $bodega = $miBodega->getBodega($ingreso_salida['id_bodega']);
        $data = [
            'nro_adquisicion' => $ingreso_salida,
            'bodega' => $bodega
        ];
        return view('ingreso_salida/subir',$data);
    }

    public function upload($id_ingreso_salida)
    {
        $validationRule = [
            'archivo_pdf' => [
                'label' => 'Archivo PDF',
                'rules' => [
                    'uploaded[archivo_pdf]',
                    'mime_in[archivo_pdf,application/pdf]',
                    'max_size[archivo_pdf,1024]',
                ],
            ],
        ];
        if (! $this->validateData([], $validationRule)) {
            $data = ['errors' => $this->validator->getErrors()];
            return view('ingreso_salida/upload_error', $data);
        }

        $archivo_subido = $this->request->getFile('archivo_pdf');

        if (! $archivo_subido->hasMoved()) {

            $filepath = ROOTPATH.'public/../../uploads/ingresos/';
            $archivo_subido->move($filepath, ($id_ingreso_salida.'.pdf'), true);

            $miNro_adquisicion = new IngresoSalidaModel();
            $dataAdq = [
                'doc_upload' => $id_ingreso_salida.'.pdf'
            ];
            $miNro_adquisicion->updateIngresoSalida($id_ingreso_salida,$dataAdq);
            $ingreso_salida = $miNro_adquisicion->find($id_ingreso_salida);

            $miBodega = new BodegasModel();
            $bodega = $miBodega->getBodega($ingreso_salida['id_bodega']);
            $dataNroAdq=[
                'nro_adquisicion' => $ingreso_salida,
                'bodega' => $bodega
            ];
            return view('ingreso_salida/upload_success', $dataNroAdq);
        }
    }

    public function eliminar_pdf($id_ingreso_salida){
        $miNro_adquisicion = new IngresoSalidaModel();
        $ingreso_salida = $miNro_adquisicion->getIngresoSalida($id_ingreso_salida);

        $filepath = ROOTPATH.'public/../../uploads/ingresos/';
        $archivo_pdf = $filepath.$ingreso_salida['doc_upload'];
        if(file_exists($archivo_pdf)){
            unlink($archivo_pdf);
        }

        $dataAdq = [
            'doc_upload' => null
        ];
        $miNro_adquisicion->updateIngresoSalida($id_ingreso_salida,$dataAdq);

        return redirect()->to('ingreso_salida/subir/'.$id_ingreso_salida);
    }

    public function finalizar($id_ingreso_salida){
        $objIngresoSalida = new IngresoSalidaModel();
        $ingreso_salida = $objIngresoSalida->getIngresoSalida($id_ingreso_salida);
        
        $objIngresoSalidaItems = new IngresoSalidaItemsModel();
        $items = $objIngresoSalidaItems->getIngresoSalidaItemsByIdIngresoSalida($id_ingreso_salida);

        if(empty($items)){
            return redirect()->to('ingreso_salida/'.$ingreso_salida['id_bodega'])->with('message', 'Su ingreso/salida no tiene items, no se puede finalizar.');
        }else{
            $dataIngresoSalida= [
                'estado_ingreso_salida' => 1
            ];
            $objIngresoSalida->updateIngresoSalida($id_ingreso_salida,$dataIngresoSalida);
            return redirect()->to('ingreso_salida/'.$ingreso_salida['id_bodega']);
        }
    }

    public function cargar_sub_apertura($id_apertura){
        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->getSubAperturaByIdApertura($id_apertura);

        $html='<select class="form-control" name="id_sub_apertura" id="id_sub_apertura">';
        $html.='<option value="0">Sin sub apertura</option>';

        foreach ($sub_apertura as $key => $value)
          $html.='<option value="'.$value['id_sub_apertura'].'">'.$value['codigo_sub_apertura'].'-'.$value['descripcion_sub_apertura'].' </option>';
        $html.='</select>';
        echo $html;
    }

    public function cambiar_estado($id){
        $objIngresoSalida = new IngresoSalidaModel();
        $ingreso_salida=$objIngresoSalida->getIngresoSalida($id);
        $data = [
            'ingreso_salida' => $ingreso_salida
        ];
        return view('ingreso_salida/cambiar_estado', $data);
    }

    public function actualizar_estado($id)
    {
        $objIngresoSalida = new IngresoSalidaModel();
        $ingreso_salida = $objIngresoSalida->getIngresoSalida($id);

        $estado_ingreso_salida = $this->request->getPost('estado_ingreso_salida');

        $postData = [
            'estado_ingreso_salida' => $estado_ingreso_salida,
            'log_ingreso_salida' => $ingreso_salida['log_ingreso_salida'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $objIngresoSalida->updateIngresoSalida($id, $postData);

        return redirect()->to('ingreso_salida/'.$ingreso_salida['id_bodega']);
    }

    public function ingreso_salida_items($id_bodega,$id_ingreso_salida){
        $ingreso_salida = new IngresoSalidaModel();
        $ing_salida=$ingreso_salida->getIngresoSalida($id_ingreso_salida);
        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $miReferenciaPartida = new RangosPartidaModel();
        $referenciaPartida = $miReferenciaPartida->findAll();
        
        $miUnidadMedida = new UnidadesMedidaModel();
        $unidades_medida = $miUnidadMedida->getUnidadesBodega($id_bodega);

        $objIngresoSalidaItems = new IngresoSalidaItemsModel();
        $ingreso_salida_items = $objIngresoSalidaItems->getIngresoSalidaItemsByIdIngresoSalida($id_ingreso_salida);
        
        $data = [
            'ing_salida' => $ing_salida,
            'id_bodega' => $id_bodega,
            'bodega' => $bodega,
            'unidades_medida' => $unidades_medida,
            'referenciaPartida' => $referenciaPartida,
            'id_ingreso_salida' => $id_ingreso_salida,
            'ingreso_salida_items' => $ingreso_salida_items
        ];
        return view('ingreso_salida_items/index', $data);
    }


}
