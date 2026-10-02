<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\NroAdquisicionModel;
use App\Models\ProveedorModel;
use App\Models\AdquisicionProductoModel;
use App\Models\AperturasModel;
use App\Models\SubAperturasModel;
use App\Models\AdquisicionProductoTieneAppModel;
//use App\Controllers\Reporte;

class NroAdquisicion extends BaseController
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
            $nro_adquisicion = new NroAdquisicionModel();
            $ojbApertura = new AperturasModel();
            $dataAperturaGeneral = $ojbApertura->getAperturasHabilitadas();
            $data = [
                'nro_adquisiciones' => $nro_adquisicion->orderBy('nro_correlativo', 'DESC')->where('id_bodega', $id_bodega)->paginate(100),
                'pager' => $nro_adquisicion->pager,
                'id_bodega' => $id_bodega,
                'dataAperturaGeneral' => $dataAperturaGeneral,
                'bodega' => $bodega->find($id_bodega),
                'proveedor' => $proveedor,
                'proveedores' => $proveedor->getProveedorBodega($id_bodega)
            ];
            
            return view('nro_adquisicion/index', $data);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function crear()
    {        
        $id_bodega = $this->request->getPost('id_bodega');
        $fecha_adquisicion = $this->request->getPost('fecha_adquisicion');
        $id_proveedor = $this->request->getPost('id_proveedor');
        $id_apertura_general = $this->request->getPost('id_apertura_general');
        $id_sub_apertura = $this->request->getPost('id_sub_apertura');
        $hoja_ruta = $this->request->getPost('hoja_ruta');
        $tipo_documento = $this->request->getPost('tipo_documento');
        $nro_tipo_documento = $this->request->getPost('nro_tipo_documento');
        $doc_constancia = $this->request->getPost('doc_constancia');
        $nro_doc_constancia = $this->request->getPost('nro_doc_constancia');
        $observaciones = $this->request->getPost('observaciones');
        $tipo_adquisicion = $this->request->getPost('tipo_adquisicion');
        
        //para el nro_correlativo
        // $MiBodega = new BodegasModel();
        // $bodega = $MiBodega->find($id_bodega);
        //fin nro_correlativo
        $objNroAdquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $objNroAdquisicion->getUltimoNroAdquisicion($id_bodega);
        if(is_null($nro_adquisicion)){
            $nro_correlativo_actual = 0;
        }else{
            $nro_correlativo_actual = $nro_adquisicion['nro_correlativo'];
        }
        $postData = [
            'id_bodega' => $id_bodega,
            'fecha_adquisicion' => mb_strtoupper(trim($fecha_adquisicion)),
            'id_proveedor' => mb_strtoupper(trim($id_proveedor)),
            'id_apertura_general' => trim($id_apertura_general),
            'id_sub_apertura' => trim($id_sub_apertura),
            'hoja_ruta' => mb_strtoupper(trim($hoja_ruta)),
            'tipo_documento' => mb_strtoupper(trim($tipo_documento)),
            'nro_tipo_documento' => mb_strtoupper(trim($nro_tipo_documento)),
            'doc_constancia' => mb_strtoupper(trim($doc_constancia)),
            'nro_doc_constancia' => mb_strtoupper(trim($nro_doc_constancia)),
            'observaciones' => mb_strtoupper(trim($observaciones)),
            'tipo_adquisicion' => mb_strtoupper(trim($tipo_adquisicion)),
            'id_gestion' => 1,
            'nro_correlativo' => $nro_correlativo_actual+1,
            'fecha_ingreso_sistema' => date('Y-m-d'),
            'log_nro_adquisicion' => 'CREADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $objNroAdquisicion->createNroAdquisicion($postData);

        // $dataBodega = [
        //     'contador' => ($bodega['contador'] + 1)
        // ];
        // $MiBodega->updateBodega($id_bodega, $dataBodega);

        return redirect()->to('ingreso/'.$id_bodega);
    }

    public function editar($id)
    {
        $nro_adquisicion = new NroAdquisicionModel();
        $n_ad=$nro_adquisicion->getNroAdquisicion($id);

        $proveedor = new ProveedorModel();
        $data = [
            'nro_adquisicion' => $n_ad,
            'proveedor' => $proveedor,
            'proveedores' => $proveedor->getProveedorBodega($n_ad['id_bodega']),

        ];
        return view('nro_adquisicion/editar', $data);
    }

    public function actualizar($id)
    {
        $nro_adquisicion = new NroAdquisicionModel();
        $log = $nro_adquisicion->getNroAdquisicion($id);

        $fecha_adquisicion = $this->request->getPost('fecha_adquisicion_editar');
        $id_proveedor = $this->request->getPost('id_proveedor_editar');
        $id_apertura_general = $this->request->getPost('id_apertura_general_editar');
        $id_sub_apertura = $this->request->getPost('id_sub_apertura_editar');
        $hoja_ruta = $this->request->getPost('hoja_ruta_editar');
        $tipo_documento = $this->request->getPost('tipo_documento_editar');
        $nro_tipo_documento = $this->request->getPost('nro_tipo_documento_editar');
        $doc_constancia = $this->request->getPost('doc_constancia_editar');
        $nro_doc_constancia = $this->request->getPost('nro_doc_constancia_editar');
        $observaciones = $this->request->getPost('observaciones_editar');
        $tipo_adquisicion = $this->request->getPost('tipo_adquisicion_editar');

        $postData = [
            'fecha_adquisicion' => mb_strtoupper(trim($fecha_adquisicion)),
            'id_proveedor' => mb_strtoupper(trim($id_proveedor)),
            'id_apertura_general' => mb_strtoupper(trim($id_apertura_general)),
            'id_sub_apertura' => mb_strtoupper(trim($id_sub_apertura)),
            'hoja_ruta' => mb_strtoupper(trim($hoja_ruta)),
            'tipo_documento' => mb_strtoupper(trim($tipo_documento)),
            'nro_tipo_documento' => mb_strtoupper(trim($nro_tipo_documento)),
            'doc_constancia' => mb_strtoupper(trim($doc_constancia)),
            'nro_doc_constancia' => mb_strtoupper(trim($nro_doc_constancia)),
            'observaciones' => mb_strtoupper(trim($observaciones)),
            'tipo_adquisicion' => mb_strtoupper(trim($tipo_adquisicion)),
            'log_nro_adquisicion' => $log['log_nro_adquisicion'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $nro_adquisicion->updateNroAdquisicion($id, $postData);
        /* actualiza adquisiones con el nro_adquisicion*/
        // $sql = 'SELECT * FROM adquisicion_producto WHERE id_nro_adquisicion='.$id;
        // $row = $this->db->query($sql)->getRowArray();
        // if(!is_null($row['publicado'])){
        //     $sql2 = 'UPDATE `adquisicion_producto` SET `id_apertura`='.$id_apertura_general.',`id_sub_apertura`='.$id_sub_apertura.' WHERE id_nro_adquisicion='.$id;            
        //     $this->db->query($sql2);
        // }        
        /* FIN actualiza adquisiones con el nro_adquisicion*/
        
        return redirect()->to('ingreso/'.$log['id_bodega']);
    }

    public function imprimir_ingreso_materiales($id_nro_adquisicion){
        $miNro_adquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $miNro_adquisicion->getNroAdquisicion($id_nro_adquisicion);

        $miProveedor = new ProveedorModel();
        $proveedor = $miProveedor->getProveedor($nro_adquisicion['id_proveedor']);

        $miBodega = new BodegasModel();
        $bodega = $miBodega->getBodega($nro_adquisicion['id_bodega']);

        $miAdquisionProducto = new AdquisicionProductoModel();
        $items = $miAdquisionProducto->getItemsAdqProd($id_nro_adquisicion);

        $data = [
            'nro_adquisicion' => $nro_adquisicion,
            'proveedor' => $proveedor,
            'bodega' => $bodega,
            'items' => $items,
        ];
        return view('nro_adquisicion/imprimir_ingreso_materiales',$data);
    }

    public function subir($id_nro_adquisicion){
        $miNro_adquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $miNro_adquisicion->getNroAdquisicion($id_nro_adquisicion);
        
        $miBodega = new BodegasModel();
        $bodega = $miBodega->getBodega($nro_adquisicion['id_bodega']);
        $data = [
            'nro_adquisicion' => $nro_adquisicion,
            'bodega' => $bodega
        ];
        return view('nro_adquisicion/subir',$data);
    }
    public function upload($id_nro_adquisicion)
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
            return view('nro_adquisicion/upload_error', $data);
        }

        $archivo_subido = $this->request->getFile('archivo_pdf');

        if (! $archivo_subido->hasMoved()) {

            $filepath = ROOTPATH.'public/../../uploads/ingresos/';
            $archivo_subido->move($filepath, ($id_nro_adquisicion.'.pdf'), true);

            $miNro_adquisicion = new NroAdquisicionModel();
            $dataAdq = [
                'doc_upload' => $id_nro_adquisicion.'.pdf'
            ];
            $miNro_adquisicion->updateNroAdquisicion($id_nro_adquisicion,$dataAdq);
            $nro_adquisicion = $miNro_adquisicion->find($id_nro_adquisicion);

            $miBodega = new BodegasModel();
            $bodega = $miBodega->getBodega($nro_adquisicion['id_bodega']);
            $dataNroAdq=[
                'nro_adquisicion' => $nro_adquisicion,
                'bodega' => $bodega
            ];
            return view('nro_adquisicion/upload_success', $dataNroAdq);
        }
    }

    public function eliminar_pdf($id_nro_adquisicion){
        $miNro_adquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $miNro_adquisicion->getNroAdquisicion($id_nro_adquisicion);

        $filepath = ROOTPATH.'public/../../uploads/ingresos/';
        $archivo_pdf = $filepath.$nro_adquisicion['doc_upload'];
        if(file_exists($archivo_pdf)){
            unlink($archivo_pdf);
        }

        $dataAdq = [
            'doc_upload' => null
        ];
        $miNro_adquisicion->updateNroAdquisicion($id_nro_adquisicion,$dataAdq);

        return redirect()->to('ingreso/subir/'.$id_nro_adquisicion);
    }

    public function finalizar($id_nro_adquisicion){
        $objNroAdquisicion = new NroAdquisicionModel();
        $nro_adquisicion = $objNroAdquisicion->getNroAdquisicion($id_nro_adquisicion);
        
        $objAdquisicionProducto = new AdquisicionProductoModel();
        $items = $objAdquisicionProducto->getItemsAdqProd($id_nro_adquisicion);

        if(empty($items)){
            return redirect()->to('ingreso/'.$nro_adquisicion['id_bodega'])->with('message', 'Su ingreso no tiene items, no se puede finalizar.');
        }elseif(is_null($nro_adquisicion['doc_upload'])){
            return redirect()->to('ingreso/'.$nro_adquisicion['id_bodega'])->with('message', 'Su ingreso no tiene pdf, no se puede finalizar.');
        }else{
            $dataNroAdq = [
                'estado_nro_adquisicion' => 1
            ];
            $objNroAdquisicion->updateNroAdquisicion($id_nro_adquisicion,$dataNroAdq);
    
            // Cargar la base de datos
            $db = \Config\Database::connect();
            // Ejecutar una consulta UPDATE
            $sql = 'UPDATE `adquisicion_producto` SET `publicado`=1, `id_apertura`='.$nro_adquisicion['id_apertura_general'].' ,`id_sub_apertura`='.$nro_adquisicion['id_sub_apertura'].' ,`fecha_movimiento`="'.date('Y-m-d').'"  WHERE `id_nro_adquisicion` = ?;';
            $db->query($sql, [$id_nro_adquisicion]);
            
            // para la tabla adq_prd_tiene_app
            if(!empty($items)){
                foreach ($items as $key => $item) {

                    $objAdqProdTieneApp = new AdquisicionProductoTieneAppModel();
                    $producto_existe = $objAdqProdTieneApp->getByIdBodegaIdAperturaIdSubAperturaIdProducto($nro_adquisicion['id_bodega'],$nro_adquisicion['id_apertura_general'],$nro_adquisicion['id_sub_apertura'],$item['id_producto']);
                    if(count($producto_existe)==0){//producto no existe entonces insertamos
                        $dataAdqProdTieneApp = [
                            'id_bodega' => $nro_adquisicion['id_bodega'],
                            'id_adquisicion_producto' => $item['id_adquisicion_producto'],
                            'id_apertura' => $nro_adquisicion['id_apertura_general'],
                            'id_sub_apertura' => $nro_adquisicion['id_sub_apertura'],
                            'id_producto' => $item['id_producto'],
                            'cantidad' => $item['cant_existente'],
                        ];
                        $objAdqProdTieneApp->insertar($dataAdqProdTieneApp);
                    }else{//producto existe actulizamos
                        $cantidad_actual = $producto_existe[0]['cantidad'];
                        $dataAdqProdTieneApp = [
                            'cantidad' => $item['cant_existente']+$cantidad_actual,
                        ];
                        $objAdqProdTieneApp->actualizar($producto_existe[0]['id_adquisicion_producto_tiene_app'], $dataAdqProdTieneApp);
                    }
                }
            }

            return redirect()->to('ingreso/'.$nro_adquisicion['id_bodega']);
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
        
        $nro_adquisicion = new NroAdquisicionModel();
        $n_ad=$nro_adquisicion->getNroAdquisicion($id);

        $proveedor = new ProveedorModel();
        $data = [
            'nro_adquisicion' => $n_ad
        ];
        return view('nro_adquisicion/cambiar_estado', $data);
    }

    public function actualizar_estado($id)
    {
        $nro_adquisicion = new NroAdquisicionModel();
        $nro_adq = $nro_adquisicion->getNroAdquisicion($id);

        $estado_nro_adquisicion = $this->request->getPost('estado_nro_adquisicion');

        $postData = [
            'estado_nro_adquisicion' => $estado_nro_adquisicion,
            'log_nro_adquisicion' => $nro_adq['log_nro_adquisicion'].',MODIFICADO|'.date('d/m/Y H:i:s').'|'.session()->isLoggedIn['username']
        ];
        $nro_adquisicion->updateNroAdquisicion($id, $postData);

        $objAdqProd = new AdquisicionProductoModel();
        $items = $objAdqProd->getItemsAdqProd($id);

        //para actualizar los kardex
        //$objReporte = new Reporte();
        
        foreach ($items as $key => $value) {
            if($estado_nro_adquisicion=="0"){
                $objAdqProdTieneApp = new AdquisicionProductoTieneAppModel();
                $prodTieneActual = $objAdqProdTieneApp->getByIdBodegaIdAperturaIdSubAperturaIdProducto($nro_adq['id_bodega'],$nro_adq['id_apertura_general'],$nro_adq['id_sub_apertura'],$value['id_producto']);
                $cant_actual=$prodTieneActual[0]['cantidad'];
    
                $cant_nueva=$cant_actual-$value['cant_ingreso'];
                $dataAdqProdTieneApp = [
                    'cantidad' => $cant_nueva,
                ];
                $objAdqProdTieneApp->actualizar($prodTieneActual[0]['id_adquisicion_producto_tiene_app'], $dataAdqProdTieneApp);
            }elseif($estado_nro_adquisicion=="1"){
                break;
            }elseif($estado_nro_adquisicion=="2"){
                $objAdqProd=new AdquisicionProductoModel();
                $objAdqProd->deleteAdquisicionProducto($value['id_adquisicion_producto']);
                //$objReporte->salvatore($nro_adq['id_bodega'], $value['id_producto']);

            }// aqui me quede
        }

        //actualizando datos de adquisicion producto
        $db = \Config\Database::connect();
        $sql = 'UPDATE `adquisicion_producto` SET `publicado`=0, `fecha_movimiento`=NULL WHERE `id_nro_adquisicion` = ?;';
        $db->query($sql, [$nro_adq['id_nro_adquisicion']]);
        
        return redirect()->to('ingreso/'.$nro_adq['id_bodega']);
    }


}
