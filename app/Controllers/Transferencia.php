<?php

namespace App\Controllers;
use App\Models\TransferenciaModel;
use App\Models\ContenidoTransferenciaModel;
use App\Models\AdquisicionProductoTieneAppModel;
use App\Models\SubAperturasModel;
use App\Models\AperturasModel;

class Transferencia extends BaseController
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
            $aperturas = new AperturasModel();
            $objTransferencia = new TransferenciaModel();
            if(session()->isLoggedIn['nivel']==1){// si es administrador
                $transferencias = $objTransferencia->getTransferenciaByIdBodega($id_bodega);
            }else{
                $transferencias = $objTransferencia->getTransferenciaByUsername(session()->isLoggedIn['username']);
            }

            $data = [
                'id_bodega' => $id_bodega,
                'aperturas' => $aperturas->getAperturasHabilitadas(),
                'transferencias' => $transferencias,
            ];
            return view('transferencia/index', $data);
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

    public function cargar_sub_apertura_destino($id_apertura){
        $objSubApertura = new SubAperturasModel();
        $sub_apertura = $objSubApertura->getSubAperturaByIdApertura($id_apertura);

        $html='<select class="form-control" name="id_sub_apertura_destino" id="id_sub_apertura_destino">';
        $html.='<option value="">Seleccione</option>';

        foreach ($sub_apertura as $key => $value)
          $html.='<option value="'.$value['id_sub_apertura'].'">'.$value['codigo_sub_apertura'].'-'.$value['descripcion_sub_apertura'].' </option>';
        $html.='</select>';
        echo $html;
    }

    public function crear_transferencia (){
        $id_bodega = $this->request->getPost('id_bodega');
        $id_apertura = $this->request->getPost('id_apertura');
        $id_sub_apertura = $this->request->getPost('id_sub_apertura');
        $id_apertura_destino = $this->request->getPost('id_apertura_destino');
        $id_sub_apertura_destino = $this->request->getPost('id_sub_apertura_destino');
        $recurso_username = session()->isLoggedIn['username'];
        $glosa = mb_strtoupper(trim($this->request->getPost('glosa')));

        $dataTransferencia = [
            'id_bodega' => $id_bodega,
            'id_apertura' => $id_apertura,
            'id_sub_apertura' => $id_sub_apertura,
            'id_apertura_destino' => $id_apertura_destino,
            'id_sub_apertura_destino' => $id_sub_apertura_destino,
            'recurso_username' => $recurso_username, 
            'fecha_transferencia' => date('Y-m-d'),
            'glosa' => $glosa,
            'estado_transferencia' => 1, // 1 generado, 2 finalizado
        ];
        $objTransferencia = new TransferenciaModel();
        $objTransferencia->insertar($dataTransferencia);
        
        return redirect()->to('transferencia/'.$id_bodega);
    }

    public function ejecutar_transferencia($id_transferencia){
        $objTransferencia = new TransferenciaModel();
        $transferencia = $objTransferencia->getTransferencia($id_transferencia);

        $objContenidoTransferencia = new ContenidoTransferenciaModel();
        $items_transferencia = $objContenidoTransferencia->getContenidoTransferenciaByIdTransferencia($id_transferencia);

        if((count($items_transferencia)!=0) &&($transferencia['estado_transferencia']==1)){
            foreach ($items_transferencia as $key => $item) {
                $objAdquisicionProductoTieneApp = new AdquisicionProductoTieneAppModel();
                $adq_prod_tiene_app = $objAdquisicionProductoTieneApp->getByIdBodegaIdAperturaIdSubAperturaIdProducto($transferencia['id_bodega'], $transferencia['id_apertura'], $transferencia['id_sub_apertura'], $item['id_producto']);
                $cantidad_actual = $adq_prod_tiene_app[0]['cantidad'];
                $dataAdqProdTieneApp = [
                    'cantidad' => $cantidad_actual-$item['cantidad_transferencia'],
                ];
                //actulizando el registro adquisicion_producto_tiene_app
                $objAdquisicionProductoTieneApp->actualizar($adq_prod_tiene_app[0]['id_adquisicion_producto_tiene_app'], $dataAdqProdTieneApp);
                $existe = $objAdquisicionProductoTieneApp->getCantExistente($transferencia['id_bodega'], $transferencia['id_apertura_destino'], $transferencia['id_sub_apertura_destino'], $item['id_producto']);
                var_dump($existe);
                if(is_null($existe)){
                    // SI NO EXISTE se - insertando en la tabla adquisicion_producto_tiene_app del destino
                    $dataAdqProdTieneAppDestino = [
                        'id_bodega' => $transferencia['id_bodega'],
                        'id_apertura' => $transferencia['id_apertura_destino'],
                        'id_sub_apertura' => $transferencia['id_sub_apertura_destino'],
                        'id_producto' => $item['id_producto'],
                        'cantidad' => $item['cantidad_transferencia'],
                    ];
                    $objAdquisicionProductoTieneApp->insertar($dataAdqProdTieneAppDestino);
                }else{//sumar y actualizar el producto
                    $dataAdqProdTieneAppDestino = [
                        'cantidad' => $existe['cantidad'] + $item['cantidad_transferencia'],
                    ];
                    $objAdquisicionProductoTieneApp->actualizar($existe['id_adquisicion_producto_tiene_app'], $dataAdqProdTieneAppDestino);
                }
            }// fin foreach

            //actualizando estado de transferencia
            $dataTransferencia = [
                'estado_transferencia' => 2, // 1 generado, 2 finalizado
            ];
            $objTransferencia->actualizar($id_transferencia,$dataTransferencia);
            return redirect()->to('transferencia/'.$transferencia['id_bodega']);
        }else{
            return redirect()->to('transferencia/'.$transferencia['id_bodega'])->with('error_transferencia', 'Error al realizar transferencia, quiza ya se haya realizado.');
        }
    } 


}
