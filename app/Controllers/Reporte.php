<?php

namespace App\Controllers;
use App\Models\BodegasModel;
use App\Models\UsuarioBodegaModel;
use App\Models\AdquisicionProductoModel;
use App\Models\ProductoModel;
use App\Models\UnidadesMedidaModel;
use App\Services\InventarioService;

use Exception;
use ErrorException;

class Reporte extends BaseController
{
    public function __construct()
    {
        $this->service = new InventarioService();
        $this->db = db_connect();
        if(!((session()->isLoggedIn['nivel']==1 || session()->isLoggedIn['nivel']==2 || session()->isLoggedIn['nivel']==3 || session()->isLoggedIn['nivel']==4) && (session()->isLoggedIn['estado_recurso']==1))){
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
            $objBodega = new BodegasModel();
            $bodegas = $objBodega->getBodegasHabilitadas();
            $data = [
                'bodegas' =>$bodegas,
            ];
            
            return view('reporte/index', $data);

        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
    public function stockActual()
    {
        $id_bodega = $this->request->getGet('id_bodega');

        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $sql = 'SELECT DISTINCT id_producto FROM adquisicion_producto WHERE id_bodega='.$id_bodega;
        $resultados = $this->db->query($sql)->getResult();

        $idsProd = array();
        $objAdqProd = new AdquisicionProductoModel();
        
        // Procesar los resultados
        $i=0;
        foreach ($resultados as $adqProd){
            $idsProd[$i]['id_producto']=$adqProd->id_producto;
            $idsProd[$i]['suma']=$objAdqProd->getStockActual($adqProd->id_producto); ;
            $i++;
        }

        $data = [
            'idsProd' => $idsProd,
            'bodega' => $bodega,
        ];

        return view('reporte/imprimir_stock_actual', $data);
    }

    public function kardex()
    {
        $id_bodega = $this->request->getGet('id_bodega_kardex');
        $objProd = new ProductoModel();
        $productos = $objProd->getProductosByIdBodega($id_bodega);
        usort($productos, function($a, $b) {
            return strcmp($a['nombre_producto'], $b['nombre_producto']);
        });

        $bodega_id = $this->controla_bodega();

        $data = [
            'id_bodega' => $id_bodega,
            'bodega_id' => $bodega_id,
            'productos' => $productos,
        ];
        // var_dump($bodega_id);
        // var_dump($id_bodega);
        return view('reporte/generar_kardex', $data);
    }

    public function generar_kardex_producto()
    {
        $id_bodega = $this->request->getPost('id_bodega_kardex');
        $id_producto = $this->request->getPost('id_producto');

        $objAdqProd = new AdquisicionProductoModel();
        $adquisiciones = $objAdqProd->getAdquisicionesByIdProductoIdBodega($id_bodega, $id_producto);

        $objProd = new ProductoModel();
        $producto = $objProd->getProducto($id_producto);
        $data = [
            'id_bodega' => $id_bodega,
            'producto' => $producto,
            'adquisiciones' => $adquisiciones,
        ];
        return view('reporte/imprimir_kardex', $data);
    }
    
    public function consolidado()
    {
        $id_bodega = $this->request->getGet('id_bodega_consolidado');
        $mes = $this->request->getGet('mes_consolidado');
        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $objProducto = new ProductoModel();
        $productos = $objProducto->getProductosByIdBodega($id_bodega);
    
        $cant_inicial=0;
        $cant_ingreso=0;

        foreach ($productos as $key => $value) {
            $objUnidad = new UnidadesMedidaModel();
            $unidad = $objUnidad->getUnidadMedida($value['id_unidad_medida']);

            $objAdqProd = new AdquisicionProductoModel();
            
            $ultimaFila = $objAdqProd->getAdquisicionesByIdProductoIdBodegaLastRow($id_bodega, $value['id_producto']);
            $cant_ingreso = $objAdqProd->getSumaIngreso($value['id_producto'], ($mes+1))->ingresos;
            $cant_salida = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'cant_salida', ($mes+1))->cant_salida;
            $ing_valorado = $objAdqProd->getSumaIngresoValoradoProducto($value['id_producto'], ($mes+1))->ing_valorado;
            $sal_valorado = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'sal_valorado', ($mes+1))->sal_valorado;            

            /* para los iniciales*/
            $cant_inicial = $this->extrae_saldo_producto(($mes+1), $value['id_producto']);
            $cant_inicial_valorado = $this->extrae_saldo_producto_valorado(($mes+1), $value['id_producto']);
            /* fin para los iniciales */

            if(!is_null($ultimaFila)){
                $data[] = [
                    'id_producto' => $value['id_producto'],
                    'id_unidad_medida' => $value['id_unidad_medida'],
                    'codigo' => $value['codigo'],
                    'id_partida' => $value['id_partida'],
                    'nombre_producto' => $value['nombre_producto'],
                    'unidad' => $unidad['nombre_unidad_medida'],
                    'cant_inicial' => $cant_inicial,
                    'cant_ingreso' => $cant_ingreso,
                    'cant_salida' => $cant_salida,
                    'cant_inicial_valorado' => $cant_inicial_valorado,
                    'ing_valorado' => $ing_valorado,
                    'sal_valorado' => $sal_valorado,
                    'saldo_valorado' => $ultimaFila['saldo_valorado'],
                ];
            }
        }

        $bodega_id = $this->controla_bodega();

        $data2 = [
            'datos' => $data,
            'mes' => $mes,
            'bodega_id'=> $bodega_id,
            'id_bodega' => $id_bodega,
            'bodega' => $bodega,
        ];
        return view('reporte/imprimir_valorado', $data2);
        
    }

    public function extrae_saldo_producto($mes, $id_producto)
    {
        $objAdqProd = new AdquisicionProductoModel();
        if($mes == 1){
            $cant_inicial = $objAdqProd->getSumaInicial($id_producto)->inicial;
        }else{
            $cant_inicial = $objAdqProd->getSumaInicial($id_producto)->inicial;
            for ($i=2; $i < $mes ; $i++) { 
                $cant_inicial += $objAdqProd->getSumaIngreso($id_producto, $i)->ingresos - $objAdqProd->getSumColumnByProducto($id_producto, 'cant_salida', $i)->cant_salida;
            }
        }
        // $ultimaFila = $objAdqProd->getAdquisicionesByIdProductoIdBodegaLastRow($id_bodega, $id_producto);
        return $cant_inicial;
    }

    public function extrae_saldo_producto_valorado($mes, $id_producto)
    {
        $objAdqProd = new AdquisicionProductoModel();
        if($mes == 1){
            $cant_inicial_valorado = $objAdqProd->getSumaInicialValorado($id_producto)->inicial_valorado;
        }else{
            $cant_inicial_valorado = $objAdqProd->getSumaInicialValorado($id_producto)->inicial_valorado;
            for ($i=2; $i < $mes ; $i++) { 
                $cant_inicial_valorado += $objAdqProd->getSumaIngresoValoradoProducto($id_producto, $i)->ing_valorado - $objAdqProd->getSumColumnByProducto($id_producto, 'sal_valorado', $i)->sal_valorado;
            }
        }
        // $ultimaFila = $objAdqProd->getAdquisicionesByIdProductoIdBodegaLastRow($id_bodega, $id_producto);
        return $cant_inicial_valorado;
    }

    public function consolidado_fisico()
    {
        $id_bodega = $this->request->getGet('id_bodega_consolidado_fisico');

        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $objProducto = new ProductoModel();
        $productos = $objProducto->getProductosByIdBodega($id_bodega);

        foreach ($productos as $key => $value) {
            $objUnidad = new UnidadesMedidaModel();
            $unidad = $objUnidad->getUnidadMedida($value['id_unidad_medida']);

            $objAdqProd = new AdquisicionProductoModel();
            
            $ultimaFila = $objAdqProd->getAdquisicionesByIdProductoIdBodegaLastRow($id_bodega, $value['id_producto']);
            $cant_ingreso = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'cant_ingreso')->cant_ingreso;
            $cant_salida = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'cant_salida')->cant_salida;
            if(!is_null($ultimaFila)){
                $data[] = [
                    'codigo' => $value['codigo'],
                    'id_partida' => $value['id_partida'],
                    'nombre_producto' => $value['nombre_producto'],
                    'unidad' => $unidad['nombre_unidad_medida'],
                    'cant_ingreso' => $cant_ingreso,
                    'cant_salida' => $cant_salida,
                    'saldo_fisico' => $ultimaFila['saldo_fisico'],
                ];
            }
        }
        
        $data2 = [
            'datos' => $data,
            'bodega' => $bodega,
        ];
        return view('reporte/imprimir_consolidado_fisico', $data2);
    }

    public function catalogo()
    {
        $id_bodega = $this->request->getGet('id_bodega_catalogo');
        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $objProducto = new ProductoModel();
        $productos = $objProducto->getProductosByIdBodega($id_bodega);
        
        $bodega_id = $this->controla_bodega();

        $data = [
            'id_bodega'=> $id_bodega,
            'bodega_id'=> $bodega_id,
            'productos' => $productos,
            'bodega' => $bodega,
        ];
        
        return view('reporte/imprimir_catalogo', $data);
    }

    public function catalogo_usuario()
    {
        $username = session()->isLoggedIn['username'];
        $objUsuarioBodega = new UsuarioBodegaModel();
        $bodegas = $objUsuarioBodega->getBodegaByUsername($username);
        $data = [
            'bodegas' => $bodegas,
        ];
        return view('reporte/imprimir_catalogo_usuario', $data);
    }

    public function imprimir_catalogo_usuario($id_bodega)
    {
        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $objProducto = new ProductoModel();
        $productos = $objProducto->getProductosByIdBodega($id_bodega);

        $data = [
            'productos' => $productos,
            'bodega' => $bodega,
        ];
        
        return view('reporte/imprimir_catalogo', $data);
    }
    public function r6()
    {
        $id_bodega = $this->request->getGet('id_bodega_r6');

        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);

        $objProducto = new ProductoModel();
        $productos = $objProducto->getProductosByIdBodega($id_bodega);

        foreach ($productos as $key => $value) {
            $objUnidad = new UnidadesMedidaModel();
            $unidad = $objUnidad->getUnidadMedida($value['id_unidad_medida']);

            $objAdqProd = new AdquisicionProductoModel();
            
            $ultimaFila = $objAdqProd->getAdquisicionesByIdProductoIdBodegaLastRow($id_bodega, $value['id_producto']);
            $cant_ingreso = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'cant_ingreso')->cant_ingreso;
            $cant_salida = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'cant_salida')->cant_salida;
            $ing_valorado = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'ing_valorado')->ing_valorado;
            $sal_valorado = $objAdqProd->getSumColumnByProducto($value['id_producto'], 'sal_valorado')->sal_valorado;
            //$precio_adquisicion 
            if(!is_null($ultimaFila)){
                $data[] = [
                    'codigo' => $value['codigo'],
                    'nombre_producto' => $value['nombre_producto'],
                    'unidad' => $unidad['nombre_unidad_medida'],
                    'cant_ingreso' => $cant_ingreso,
                    'cant_salida' => $cant_salida,
                    'saldo_fisico' => $ultimaFila['saldo_fisico'],
                    //'precio_adquisicion' => $precio_adquisicion,
                    'ing_valorado' => $ing_valorado,
                    'sal_valorado' => $sal_valorado,
                    'saldo_valorado' => $ultimaFila['saldo_valorado'],
                ];
            }
        }
        $data2 = [
            'datos' => $data,
            'bodega' => $bodega,
        ];
        return view('reporte/imprimir_r6', $data2);
    }

    public function inventario_inicial()
    {
        $id_bodega = $this->request->getGet('id_bodega_inventario_inicial');
        $objBodega = new BodegasModel();
        $bodega = $objBodega->getBodega($id_bodega);
                
        $objAdqProd = new AdquisicionProductoModel();
        $resultado = $objAdqProd->getPrimeraFilaInventarioInivial($id_bodega);
        foreach ($resultado as $key => $value) {
            $objProducto = new ProductoModel();
            $producto = $objProducto->getProducto($value['id_producto']);
            $objUnidad = new UnidadesMedidaModel();
            $unidad = $objUnidad->getUnidadMedida($producto['id_unidad_medida']);
            
            $data[] = [
                'codigo' => $producto['codigo'],
                'nombre_producto' => $producto['nombre_producto'],
                'unidad' => $unidad['nombre_unidad_medida'],
                'precio_adquisicion' => $value['precio_adquisicion'],
                'cant_inicial' => $value['cant_ingreso'],
                'cant_ingreso' => $value['cant_ingreso'],
                'cant_salida' => 0,
                'saldo_fisico' => $value['saldo_fisico'],
                'ing_valorado' => $value['ing_valorado'],
                'sal_valorado' => 0,
                'saldo_valorado' => $value['saldo_valorado']
            ];
        }
        $bodega_id = $this->controla_bodega();
        $data2 = [
            'bodega_id' => $bodega_id,
            'id_bodega' => $id_bodega,
            'datos' => $data,
            'bodega' => $bodega,
        ];
        return view('reporte/imprimir_inventario_inicial', $data2);
    }//fin inventario_inicial

    function kardex_insumos()
    {
        $mes = $this->request->getGet('mes_kardex_insumos')+1;        
        $objAdqProd = new AdquisicionProductoModel();
        $bodegas = $objAdqProd->getBodegas();
        $iniciales_fisico = array();
        $iniciales_valorado = array();
        $ingresos_fisicos = array();
        $salidas_fisicos = array();
        $ingresos_valorados = array();
        $salidas_valorados = array();
        for ($mes_i=1; $mes_i <=$mes ; $mes_i++) {
                //fisico
                foreach ($bodegas as $value) {
                    if($mes_i == 1){
                        $iniciales_fisico[$value['id_bodega']] = $salidaBodegas[$value['id_bodega']]['saldo_fisico_inicial'] = $objAdqProd->getSumaInventarioInicialFisicoBodega($value['id_bodega']);
                    }
                        $ingresos_fisicos[$mes_i][$value['id_bodega']] = $salidaBodegas[$value['id_bodega']]['ingreso_fisico'] = $objAdqProd->getSumaIngresoFisico($value['id_bodega'], $mes_i);
                        $salidas_fisicos[$mes_i][$value['id_bodega']] = $salidaBodegas[$value['id_bodega']]['salida_fisico'] = $objAdqProd->getSumaSalidaFisico($value['id_bodega'], $mes_i);
                }
                //valorado
                foreach ($bodegas as $value) {
                    if($mes_i == 1){
                        $iniciales_valorado[$value['id_bodega']] = $salidaBodegas[$value['id_bodega']]['saldo_valorado_inicial'] = $objAdqProd->getSumaInventarioInicialBodega($value['id_bodega']);
                    }
                        $ingresos_valorados[$mes_i][$value['id_bodega']] = $salidaBodegas[$value['id_bodega']]['ingreso'] = $objAdqProd->getSumaIngresoValorado($value['id_bodega'], $mes_i);
                        $salidas_valorados[$mes_i][$value['id_bodega']] = $salidaBodegas[$value['id_bodega']]['egreso'] = $objAdqProd->getSumaSalidaValorado($value['id_bodega'], $mes_i);
                }
        }//fin for principal

        foreach ($bodegas as $value) {
            $sumatoria_ingreso_fisico = 0;
            $sumatoria_salida_fisico = 0;
            $sumatoria_ingreso_valorado = 0;
            $sumatoria_salida_valorado = 0;
            for ($mes_i=1; $mes_i <=$mes ; $mes_i++) {
                $sumatoria_ingreso_fisico+= $ingresos_fisicos[$mes_i][$value['id_bodega']];
                $sumatoria_salida_fisico+= $salidas_fisicos[$mes_i][$value['id_bodega']];
                $sumatoria_ingreso_valorado+= $ingresos_valorados[$mes_i][$value['id_bodega']];
                $sumatoria_salida_valorado+= $salidas_valorados[$mes_i][$value['id_bodega']];
            }
            $sumatoria_ingreso_fisico+=$iniciales_fisico[$value['id_bodega']];
            $sumatoria_ingreso_valorado+=$iniciales_valorado[$value['id_bodega']];
            $salidaBodegas[$value['id_bodega']]['ingreso_fisico'] = $ingresos_fisicos[$mes][$value['id_bodega']];
            $salidaBodegas[$value['id_bodega']]['salida_fisico'] = $salidas_fisicos[$mes][$value['id_bodega']];
            $salidaBodegas[$value['id_bodega']]['saldo_fisico'] = $sumatoria_ingreso_fisico-$sumatoria_salida_fisico;//saldo fisico
            $salidaBodegas[$value['id_bodega']]['saldo_fisico_inicial'] = $salidaBodegas[$value['id_bodega']]['saldo_fisico']-$salidaBodegas[$value['id_bodega']]['ingreso_fisico']+$salidaBodegas[$value['id_bodega']]['salida_fisico'];

            $salidaBodegas[$value['id_bodega']]['ingreso'] = $ingresos_valorados[$mes][$value['id_bodega']];
            $salidaBodegas[$value['id_bodega']]['egreso'] = $salidas_valorados[$mes][$value['id_bodega']];
            $salidaBodegas[$value['id_bodega']]['saldo_valorado'] = $sumatoria_ingreso_valorado-$sumatoria_salida_valorado;//saldo valorado
            $salidaBodegas[$value['id_bodega']]['saldo_valorado_inicial'] = $salidaBodegas[$value['id_bodega']]['saldo_valorado']-$salidaBodegas[$value['id_bodega']]['ingreso']+$salidaBodegas[$value['id_bodega']]['egreso'];
        }
        $data = [
            'bodegas' => $bodegas,
            'salidaBodegas' => $salidaBodegas,
            'mes' => $mes,
        ];        
        return view('reporte/imprimir_kardex_insumos', $data);
    }

    /**
     * Genera el reporte de kardex de insumos fisico 
     * 
     * @return void
     */
    function kardex_insumos_fisico()
    {
        $mes = 2;
        //$mes = $this->request->getGet('mes_kardex_insumos')+1;        
        $objAdqProd = new AdquisicionProductoModel();
        $bodegas = $objAdqProd->getBodegas();

        foreach ($bodegas as $value) {
            $salidaBodegas[$value['id_bodega']]['suma_total_saldo_fisico'] = $objAdqProd->getSumaInventarioInicialFisicoBodega($value['id_bodega']);
            $salidaBodegas[$value['id_bodega']]['suma_total_ingresado_fisico'] = $objAdqProd->getSumaIngresoFisico($value['id_bodega'], $mes);
            $salidaBodegas[$value['id_bodega']]['suma_total_salida_fisico'] = $objAdqProd->getSumaSalidaFisico($value['id_bodega'], $mes);
        }
        $data = [
            'bodegas' => $bodegas,
            'salidaBodegas' => $salidaBodegas,
            'mes' => $mes,
        ];
        return view('reporte/imprimir_kardex_insumos_fisico', $data);
    }

    function kardexValorado()
    {
        $objAdqProd = new AdquisicionProductoModel();
        $bodegas = $objAdqProd->getBodegas();

        foreach ($bodegas as $value) {
            $salidaBodegas[$value['id_bodega']]['suma_inventario_inicial'] = $objAdqProd->getSumaInventarioInicialBodega($value['id_bodega']);
            $salidaBodegas[$value['id_bodega']]['suma_ing_valorado'] = $objAdqProd->getSumaIngresoValorado($value['id_bodega'],1);
            $salidaBodegas[$value['id_bodega']]['suma_sal_valorado'] = $objAdqProd->getSumaSalidaValorado($value['id_bodega'],1);
        }
        $data = [
            'bodegas' => $bodegas,
            'salidaBodegas' => $salidaBodegas,
        ];

        return view('reporte/imprimir_kardex_valorado', $data);
    }

    function r5_r6(){
        $objBodega = new BodegasModel();
        $bodegas = $objBodega->getBodegasR56();
        $productosBodega = array();
        $dataSalida = array();
        
        foreach ($bodegas as $key => $bodega) {
            $objProducto = new ProductoModel();
            $productosBodega[$bodega['id_bodega']] = $objProducto->getProductosByIdBodegaR56($bodega['id_bodega']);
        }
        foreach ($productosBodega as $key => $producto) {
            foreach ($producto as $key => $value) {
                $objAdqProd = new AdquisicionProductoModel();
                //$precioPromedio = $objAdqProd->getPrecioPromedioAdquisicion($value['id_producto']);
                $saldo_inicial = $objAdqProd->getSaldoInicialFisicoValoradoProducto($value['id_producto']);
                $cantIngreso = $objAdqProd->getCantIngresoR56($value['id_producto']);
                $cantSalida = $objAdqProd->getCantSalidaR56($value['id_producto']);
                $saldoFisico = (is_null($saldo_inicial) ? 0 : $saldo_inicial['cant_ingreso']) + (is_null($cantIngreso) ? 0 : $cantIngreso) - (is_null($cantSalida) ? 0 : $cantSalida);
                $credito = $objAdqProd->getIngValoradoR56($value['id_producto']);
                $debito = $objAdqProd->getSalValoradoR56($value['id_producto']);
                $saldoValorado = ((is_null($saldo_inicial) ? 0 : $saldo_inicial['ing_valorado']) + $credito) - $debito;
                if($saldoValorado==0 || $saldoFisico==0){
                    $precioPromedio = 0;
                }else{
                    $precioPromedio = round(($saldoValorado/$saldoFisico), 2);
                }
                //var_dump($cantIngreso);

                $dataSalida[$value['id_producto']]['id_producto'] = $value['id_producto'];
                $dataSalida[$value['id_producto']]['nombre_producto'] = $value['nombre_producto'];
                $dataSalida[$value['id_producto']]['id_bodega'] = $value['id_bodega'];
                $dataSalida[$value['id_producto']]['id_partida'] = $value['id_partida'];
                $dataSalida[$value['id_producto']]['precio_promedio'] = $precioPromedio;
                $dataSalida[$value['id_producto']]['saldo_inicial_fisico'] = is_null($saldo_inicial) ? 0 : $saldo_inicial['cant_ingreso'];
                $dataSalida[$value['id_producto']]['total_cant_ingreso'] = is_null($cantIngreso) ? 0 : $cantIngreso;
                $dataSalida[$value['id_producto']]['total_cant_salida'] = is_null($cantSalida) ? 0 : $cantSalida;
                $dataSalida[$value['id_producto']]['total_saldo_fisico'] = is_null($saldoFisico) ? 0 : $saldoFisico;

                $dataSalida[$value['id_producto']]['saldo_inicial_valorado'] = is_null($saldo_inicial) ? 0 : $saldo_inicial['ing_valorado'];
                $dataSalida[$value['id_producto']]['credito'] = is_null($credito) ? 0 : $credito;
                $dataSalida[$value['id_producto']]['debito'] = is_null($debito) ? 0 : $debito;
                $dataSalida[$value['id_producto']]['saldo_valorado'] = is_null($saldoValorado) ? 0 : $saldoValorado;

                $dataSalida[$value['id_producto']]['nombre_unidad_medida'] = $value['nombre_unidad_medida'];
                
            }
            //break;
        }

        $superData = [
            'dataSalida' => $dataSalida,
            'bodegas' => $bodegas,
            ];
        //var_dump($superData['dataSalida']);
            
        return view('reporte/imprimir_r5_r6', $superData);
    }

    function salvatore($id_bodega, $id_producto)
    {
        $objAdqProd = new AdquisicionProductoModel();        
        $rows = $objAdqProd->getAdquisicionesByIdProductoIdBodega($id_bodega, $id_producto);
        $i=0;
        if(count($rows)>0){
            $puntero = array();
            foreach ($rows as $row) {
                
                if($i==0){//solo la primera fila
                    $firstRow = $objAdqProd->getPrimeraFilaSalvatore($id_bodega, $id_producto);
                    $puntero = $firstRow;
                    
                    if(!is_null($firstRow)){
                        $firstPrecioAdquisicion = $firstRow['precio_adquisicion'];
                        $firstCantIngreso = $firstRow['cant_ingreso'];
                        $dataUpdateFirstRow = [
                            'cant_existente' => $firstCantIngreso,
                            'saldo_fisico' => $firstCantIngreso,
                            'ing_valorado' => round ($firstCantIngreso*$firstPrecioAdquisicion, 2),
                            'saldo_valorado' => round($firstCantIngreso*$firstPrecioAdquisicion, 2),
                        ];
                    }
                    $objAdqProd->update($firstRow['id_adquisicion_producto'], $dataUpdateFirstRow);
                }else{//resto de las filas
                    if($row['tipo_movimiento']==='1'){// es INGRESO
                        $dataIngreso = [
                            'cant_existente' => $row['cant_ingreso'],
                            'saldo_fisico' => $rows[$i-1]['saldo_fisico'] + $row['cant_ingreso'],
                            'ing_valorado' => round(($row['cant_ingreso'] * $row['precio_adquisicion']),2),
                            'saldo_valorado' => round(($rows[$i-1]['saldo_valorado'] + round(($row['cant_ingreso'] * $row['precio_adquisicion']),2)),2),
                        ];
                        $objAdqProd->update($row['id_adquisicion_producto'], $dataIngreso);//actualizando el row
                    }else{//ES SALIDA
                        
                        if($row['cant_salida']<=$puntero['cant_existente']){
                            $dataUpdateRow = [
                                'precio_adquisicion' => $puntero['precio_adquisicion'],
                                'saldo_fisico' => $rows[$i-1]['saldo_fisico'] - $row['cant_salida'],
                                'sal_valorado' => round(($puntero['precio_adquisicion'] * $row['cant_salida']),2),
                                'saldo_valorado' => round((($rows[$i-1]['saldo_valorado']) - (round(($puntero['precio_adquisicion'] * $row['cant_salida']),2))),2),
                            ];
                            $objAdqProd->update($row['id_adquisicion_producto'], $dataUpdateRow);//actualizando el row

                            $dataPuntero = [
                                'cant_existente' => $puntero['cant_existente'] - $row['cant_salida'],
                            ];
                            $objAdqProd->update($puntero['id_adquisicion_producto'], $dataPuntero);//actualizando el puntero
                        }
                    }
                }
                $i++;
            }//fin foreach
        }
    }//fin funcion

    function salvatore2($id_bodega, $id_producto){

        $objAdqProd = new AdquisicionProductoModel();
        $rows = $objAdqProd->getAdquisicionesByIdProductoIdBodega($id_bodega, $id_producto);
        $pivote = array_shift($rows);
        $servicio = new InventoryService();
        if(count($rows)>1){
            foreach ($rows as $fila) {
                if($fila['tipo_movimiento']==='1'){
                    $this->es_ingreso($fila, $pivote);
                }else{
                    $this->es_salida($fila, $pivote);
//                    $servicio->procesarPEPS($id_producto, $id_bodega);
                }
            }
        }
        
    }

    function es_ingreso($fila, $pivote){
        $cant_existente = $fila['cant_ingreso'];
        $saldo_fisico = $pivote['saldo_fisico']+$fila['cant_ingreso'];
        $ing_valorado = $fila['cant_ingreso']*$fila['precio_adquisicion'];
        $saldo_valorado = $pivote['saldo_valorado']+$ing_valorado;
        $data=[
            'cant_existente'=>$cant_existente,
            'saldo_fisico'=>$saldo_fisico,
            'ing_valorado'=>$ing_valorado,
            'saldo_valorado'=>$saldo_valorado,
        ];
        $objAdqProd = new AdquisicionProductoModel();
        $objAdqProd->update($fila['id_adquisicion_producto'], $data);
    }
    
    function es_salida($fila, $pivote){
        $objAdqProd = new AdquisicionProductoModel();
//        if($fila['cant_salida']<=$pivote['cant_existente']){
            $saldo_fisico = $pivote['saldo_fisico']-$fila['cant_salida'];
            $saldo_valorado = $pivote['saldo_valorado']-($fila['cant_salida']*$pivote['precio_adquisicion']);
            $data=[
                'saldo_fisico'=>$saldo_fisico,
                'saldo_valorado'=>$saldo_valorado,
            ];
            $objAdqProd->update($fila['id_adquisicion_producto'], $data);
    }
    
    function contadorProductos()
    {
        $id_bodega = 20;
        //$id_producto = 9;
        $objAdqProd = new AdquisicionProductoModel();        
        $productos = $objAdqProd->getProductosDistintosPorBodega($id_bodega);
        foreach ($productos as $value) {
            $this->recalcular($id_bodega, $value['id_producto']);
            // try {
            //     $id_producto = $value['id_producto'];
            //     //para la solucion
            //     $rows = $objAdqProd->getAdquisicionesByIdProductoIdBodega($id_bodega, $id_producto);
            //     for ($i=0; $i < count($rows) ; $i++) { 
            //         $this->salvatore2($id_bodega, $id_producto);
            //     }
            //     echo 'producto: '.$id_producto.' finalizado actualizacion kardex<br>';
                      
            //     throw new ErrorException("Este es un ejemplo de ErrorException");
            //   } catch (ErrorException $e) {
            //     // Captura la excepción y maneja el error
            //     echo "id producto: " . $id_producto . "<br>";
            //     // Puedes registrar el error en un archivo de log o realizar otras acciones
            //   }
        }
        
        echo '<br>Todos los kardex de la bodega '.$id_bodega.' han sido actualizados.';
        // return;
    }

    public function controla_bodega(){
        if (session()->isLoggedIn['nivel']==1){//controla bodegas
            $bodega_id = 0;
        }elseif(session()->isLoggedIn['nivel']==2){
            $bodega = new BodegasModel();
            $bodega = $bodega->getBodegaByUsername(session()->isLoggedIn['username']);
            $bodega_id = $bodega['id_bodega'];

        }else{
            $bodega_id = null;
        }//controla bodegas
        return $bodega_id;
    }

/////ia
    public function recalcular($idBodega, $idProducto)
    {
        $this->service->recalcularPeps($idBodega, $idProducto);
        // return $this->response->setJSON([
        //     'status' => 'ok',
        //     'message' => 'Recalculo PEPS completado correctamente.'
        // ]);
    }

    function kardex_final()
    {
        $objAdqProd = new AdquisicionProductoModel();
        $bodegas = $objAdqProd->getBodegas();
        $salidaBodegas = array();
        
        foreach ($bodegas as $value) {
            $salidaBodegas[$value['id_bodega']]['id_bodega'] = $value['id_bodega'];
            $salidaBodegas[$value['id_bodega']]['iniciales_valorado'] = $objAdqProd->getSumaInventarioInicialBodega($value['id_bodega']);
            $salidaBodegas[$value['id_bodega']]['ing_valorado'] = $objAdqProd->getSumaIngresoValoradoFinal($value['id_bodega']);
            $salidaBodegas[$value['id_bodega']]['sal_valorado'] = $objAdqProd->getSumaEgresoValoradoFinal($value['id_bodega']);
            $salidaBodegas[$value['id_bodega']]['saldo_final'] = ($salidaBodegas[$value['id_bodega']]['iniciales_valorado'] + $salidaBodegas[$value['id_bodega']]['ing_valorado']) - $salidaBodegas[$value['id_bodega']]['sal_valorado'];
        }
        //var_dump($salidaBodegas);

        $data = [
            'salidaBodegas' => $salidaBodegas,
        ];
        return view('reporte/imprimir_kardex_final', $data);
    }


    function actualizacion_saldo(){
        $objBodega = new BodegasModel();
        $bodegas = $objBodega->getBodegaById($this->request->getGet('id_bodega_actualizacion_saldo'));
        $productosBodega = array();
        $dataSalida = array();
        
        foreach ($bodegas as $key => $bodega) {
            $objProducto = new ProductoModel();
            $productosBodega[$bodega['id_bodega']] = $objProducto->getProductosByIdBodegaR56($bodega['id_bodega']);
        }
        foreach ($productosBodega as $key => $producto) {
            foreach ($producto as $key => $value) {
                $objAdqProd = new AdquisicionProductoModel();
                //$precioPromedio = $objAdqProd->getPrecioPromedioAdquisicion($value['id_producto']);
                $saldo_inicial = $objAdqProd->getSaldoInicialFisicoValoradoProducto($value['id_producto']);
                $cantIngreso = $objAdqProd->getCantIngresoR56($value['id_producto']);
                //$apertura = $objAdqProd->apertura($value['id_producto']);
                $cantSalida = $objAdqProd->getCantSalidaR56($value['id_producto']);
                $saldoFisico = (is_null($saldo_inicial) ? 0 : $saldo_inicial['cant_ingreso']) + (is_null($cantIngreso) ? 0 : $cantIngreso) - (is_null($cantSalida) ? 0 : $cantSalida);
                $credito = $objAdqProd->getIngValoradoR56($value['id_producto']);
                $debito = $objAdqProd->getSalValoradoR56($value['id_producto']);
                $saldoValorado = ((is_null($saldo_inicial) ? 0 : $saldo_inicial['ing_valorado']) + $credito) - $debito;
                if($saldoValorado==0 || $saldoFisico==0){
                    $precioPromedio = 0;
                }else{
                    $precioPromedio = round(($saldoValorado/$saldoFisico), 2);
                }
                //var_dump($apertura);
                //break;
                $dataSalida[$value['id_producto']]['id_producto'] = $value['id_producto'];
                //$dataSalida[$value['id_producto']]['id_apertura'] = $apertura['id_apertura'] ?? 0;
                $dataSalida[$value['id_producto']]['nombre_producto'] = $value['nombre_producto'];
                $dataSalida[$value['id_producto']]['id_bodega'] = $value['id_bodega'];
                $dataSalida[$value['id_producto']]['id_partida'] = $value['id_partida'];
                $dataSalida[$value['id_producto']]['precio_promedio'] = $precioPromedio;
                $dataSalida[$value['id_producto']]['saldo_inicial_fisico'] = is_null($saldo_inicial) ? 0 : $saldo_inicial['cant_ingreso'];
                $dataSalida[$value['id_producto']]['total_cant_ingreso'] = is_null($cantIngreso) ? 0 : $cantIngreso;
                $dataSalida[$value['id_producto']]['total_cant_salida'] = is_null($cantSalida) ? 0 : $cantSalida;
                $dataSalida[$value['id_producto']]['total_saldo_fisico'] = is_null($saldoFisico) ? 0 : $saldoFisico;

                $dataSalida[$value['id_producto']]['saldo_inicial_valorado'] = is_null($saldo_inicial) ? 0 : $saldo_inicial['ing_valorado'];
                $dataSalida[$value['id_producto']]['credito'] = is_null($credito) ? 0 : $credito;
                $dataSalida[$value['id_producto']]['debito'] = is_null($debito) ? 0 : $debito;
                $dataSalida[$value['id_producto']]['saldo_valorado'] = is_null($saldoValorado) ? 0 : $saldoValorado;

                $dataSalida[$value['id_producto']]['nombre_unidad_medida'] = $value['nombre_unidad_medida'];
                
            }
            //break;
        }

        $superData = [
            'dataSalida' => $dataSalida,
            'bodegas' => $bodegas,
            ];
        
            
        return view('reporte/imprimir_actualizacion_saldo', $superData);
    }

}
