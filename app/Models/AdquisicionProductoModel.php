<?php

namespace App\Models;

use CodeIgniter\Model;

class AdquisicionProductoModel extends Model
{
	protected $table = 'adquisicion_producto';
	protected $primaryKey = 'id_adquisicion_producto';
	protected $allowedFields = ['tipo_movimiento', 'id_producto', 'id_bodega', 'id_nro_adquisicion', 'id_orden', 'cant_ingreso', 'cant_existente', 'precio_adquisicion', 'cant_salida', 'ref_adq', 'saldo_fisico', 'ing_valorado', 'sal_valorado', 'saldo_valorado', 'lock', 'publicado', 'id_apertura', 'id_sub_apertura', 'log_adquisicion_producto', 'fecha_movimiento'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAll()
	{
		return $this->findAll();
	}

	public function getAdquisicionProducto($id)
	{
		return $this->find($id);
	}

	public function getItemsAdqProd($id_nro_adq){
		return $this->where('id_nro_adquisicion', $id_nro_adq)->findAll();
	}
	public function getAdquisicionesByIdOrden($id_orden)
	{
		return $this->where('id_orden', $id_orden)->limit(1)->findAll();
	}
	public function getAdquisicionesByIdOrdenSalida($id_orden)
	{
		return $this->where('id_orden', $id_orden)->where('tipo_movimiento', 0)->findAll();
	}
	public function getCantExistenteByProducto($id_producto, $id_apertura, $id_sub_apertura)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('cant_existente')
						 ->where('id_producto', $id_producto)
						 ->where('id_apertura', $id_apertura)
						 ->where('id_sub_apertura', $id_sub_apertura)
						 ->where('publicado', 1)
                         ->get();

        $resultado = $query->getRow();
        return $resultado->cant_existente;
	}

	public function buscar_pivote($id_producto)
	{
        $query = $this->db->table($this->table)
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento', 1)
						 ->where('cant_existente is not', NULL)
                         ->get();

        $resultado = $query->getRowArray();
        return $resultado;
	}

	public function getPenultimoRegistro(int $idProducto)
    {
        return $this->where('id_producto', $idProducto)
                    ->orderBy('id_adquisicion_producto', 'DESC')
                    // En CI4: limit($limit, $offset)
                    ->limit(1, 1) 
                    ->get()
                    ->getRowArray(); // Retorna un array o null si no existe
                    // Usa ->getRow() si prefieres un objeto stdClass
    }	

	public function getStockActual($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('cant_existente')
						 ->where('id_producto', $id_producto)
						 ->where('publicado', 1)
                         ->get();

        $resultado = $query->getRow();
        return $resultado->cant_existente;
	}

	public function getLastAdquisicionByIdProducto($id_bodega, $id_producto)
	{
        $query = $this->db->table($this->table)
						->where('id_bodega', $id_bodega)
						->where('id_producto', $id_producto)
						->orderBy('id_adquisicion_producto', 'DESC')
                        ->get();
        $resultado = $query->getRowArray();
        return $resultado;
	}

	public function getFirstByIdProductoExistente($id_bodega, $id_producto)
	{
        $query = $this->db->table($this->table)
						->where('id_bodega', $id_bodega)
						->where('id_producto', $id_producto)
						->where('tipo_movimiento', 1)
						->where('cant_existente >=', 0)
                        ->get();
        $resultado = $query->getRowArray();
        return $resultado;
	}

	public function getAdquisicionesByIdProductoIdBodega($id_bodega, $id_producto)
	{
        $query = $this->db->table($this->table)
						->where('id_bodega', $id_bodega)
						->where('id_producto', $id_producto)
                        ->get();
        $resultado = $query->getResultArray();
        return $resultado;
	}

	public function getAdquisicionesByIdProductoIdBodegaLastRow($id_bodega, $id_producto)
	{
        $query = $this->db->table($this->table)
						->where('id_bodega', $id_bodega)
						->where('id_producto', $id_producto)
						->orderBy('id_adquisicion_producto', 'DESC')
						->limit(1)
                        ->get();
		$resultado = $query->getRowArray();
		return $resultado;
	}

	public function getSumColumnByProducto($id_producto, $nombre_columna, $mes)
	{
        $query = $this->db->table($this->table)//cambios aqui
                        ->selectSum($nombre_columna)
						->join('ordenes', 'ordenes.id_orden = adquisicion_producto.id_orden')//
						->where('adquisicion_producto.tipo_movimiento', 0)
						->where('id_producto', $id_producto)
						->where('MONTH(ordenes.fecha_aprobado)', $mes) // Condición para el mes
						->get();

        $resultado = $query->getRow();
        return $resultado;
	}

	public function getPrimeraFilaInventarioInivial($id_bodega)
	{
		return $this->where('id_bodega', $id_bodega)->where('tipo_movimiento', 1)->where('ref_adq', 1)->findAll();
	}

	public function getPrimeraFilaSalvatore($id_bodega, $id_producto)
	{
		
        $query = $this->db->table($this->table)
						 ->where('id_bodega', $id_bodega)
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento', 1)
                         ->get();

        $resultado = $query->getRowArray();
        return $resultado;
	}

	public function getSumaInicial($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('cant_ingreso','inicial')
						 ->where('id_producto', $id_producto)
						 ->where('id_nro_adquisicion',1)
                         ->get();

        $resultado = $query->getRow();
        return $resultado;
	}

	public function getSumaInicialValorado($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('ing_valorado','inicial_valorado')
						 ->where('id_producto', $id_producto)
						 ->where('id_nro_adquisicion',1)
                         ->get();

        $resultado = $query->getRow();
        return $resultado;
	}

	public function getSumaIngreso($id_producto, $mes)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('cant_ingreso','ingresos')
						 ->join('nro_adquisicion', 'nro_adquisicion.id_nro_adquisicion = adquisicion_producto.id_nro_adquisicion')						 
						 ->where('estado_nro_adquisicion <>',2)
						 ->where('id_producto', $id_producto)
						 ->where('adquisicion_producto.id_nro_adquisicion <>',1)
						 ->where('MONTH(nro_adquisicion.fecha_adquisicion)', $mes) // Condición para el mes
                         ->get();

        $resultado = $query->getRow();
        return $resultado;
	}

	public function getSumaIngresoValoradoProducto($id_producto, $mes)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('ing_valorado','ing_valorado')
						 ->join('nro_adquisicion', 'nro_adquisicion.id_nro_adquisicion = adquisicion_producto.id_nro_adquisicion')						 
						 ->where('estado_nro_adquisicion <>',2)
						 ->where('id_producto', $id_producto)
						 ->where('adquisicion_producto.id_nro_adquisicion <>',1)
						 ->where('MONTH(nro_adquisicion.fecha_adquisicion)', $mes) // Condición para el mes
                         ->get();

        $resultado = $query->getRow();
        return $resultado;
	}

	public function getSumaIngresoValoradoFinal($id_bodega)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('ing_valorado','ing_valorado')
						 ->where('id_bodega', $id_bodega)
						 ->where('tipo_movimiento', 1)
						 ->where('id_nro_adquisicion  <>',1)
                         ->get();
		$resultado = $query->getRowArray();
        return $resultado['ing_valorado'];
	}

	public function getSumaEgresoValoradoFinal($id_bodega)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('sal_valorado','sal_valorado')
						 ->where('id_bodega', $id_bodega)
						 ->where('tipo_movimiento', 0)
                         ->get();
		$resultado = $query->getRowArray();
        return $resultado['sal_valorado'];
	}

	public function getBodegas(): array
	{
		return $this->distinct()
                    ->select('id_bodega')
                    ->orderBy('id_bodega', 'ASC')
                    ->findAll();
	}

	public function getSumaInventarioInicialBodega(int $idBodega): float
    {
        $result = $this->selectSum('saldo_valorado', 'total_saldo_valorado') // Selecciona la suma y le da un alias
                       ->where('id_bodega', $idBodega)
                       ->where('tipo_movimiento', 1)
                       ->where('ref_adq', 1)
                       ->where('fecha_movimiento IS NULL')
                       ->get() // Ejecuta la consulta
                       ->getRow(); // Obtiene la primera fila del resultado

        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0
        return $result ? (float) $result->total_saldo_valorado : 0.0;
    }

	public function getSumaInventarioInicialFisicoBodega(int $idBodega): float
    {
        $result = $this->selectSum('cant_ingreso', 'total_saldo_fisico') // Selecciona la suma y le da un alias
                       ->where('id_bodega', $idBodega)
                       ->where('tipo_movimiento', 1)
                       ->where('ref_adq', 1)
                       ->where('fecha_movimiento IS NULL')
                       ->get() // Ejecuta la consulta
                       ->getRow(); // Obtiene la primera fila del resultado

        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0
        return $result ? (float) $result->total_saldo_fisico : 0.0;
    }

	public function getSumaIngresoValorado(int $idBodega, int $mes): float
    {
		$result = $this->selectSum('ing_valorado', 'total_ingresado') // Selecciona la suma y le da un alias
					->join('nro_adquisicion', 'nro_adquisicion.id_nro_adquisicion = adquisicion_producto.id_nro_adquisicion')
					->where('adquisicion_producto.id_bodega', $idBodega)
					->where('adquisicion_producto.tipo_movimiento', 1)
					->where('MONTH(nro_adquisicion.fecha_adquisicion)', $mes) // Condición para el mes
					->get() // Ejecuta la consulta
					->getRow(); // Obtiene la primera fila del resultado

        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0
		if($mes != 1){
			return $result ? (float) $result->total_ingresado : 0.0;
		}else{
			return 0.0;
		}
    }

	public function getSumaIngresoFisico(int $idBodega, int $mes): float{
        $result = $this->selectSum('cant_ingreso', 'total_ingresado_fisico') // Selecciona la suma y le da un alias
					  ->join('nro_adquisicion', 'nro_adquisicion.id_nro_adquisicion = adquisicion_producto.id_nro_adquisicion')
					  ->where('adquisicion_producto.id_bodega', $idBodega)
					  ->where('adquisicion_producto.tipo_movimiento', 1)
					  ->where('MONTH(nro_adquisicion.fecha_adquisicion)', $mes) // Condición para el mes
					  ->get() // Ejecuta la consulta
					  ->getRow(); // Obtiene la primera fila del resultado
  
        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0

		if($mes != 1){
			return $result ? (float) $result->total_ingresado_fisico : 0.0;
		}else{
			return 0.0;
		}

    }

	public function getSumaIngresoFisicoConsolidado(int $idBodega, int $mes, $id_producto): float{
        $result = $this->selectSum('cant_ingreso', 'total_ingresado_fisico') // Selecciona la suma y le da un alias
					  ->join('nro_adquisicion', 'nro_adquisicion.id_nro_adquisicion = adquisicion_producto.id_nro_adquisicion')
					  ->where('adquisicion_producto.id_producto', $id_producto)
					  ->where('adquisicion_producto.id_bodega', $idBodega)
					  ->where('adquisicion_producto.tipo_movimiento', 1)
					  ->where('MONTH(nro_adquisicion.fecha_adquisicion)', $mes) // Condición para el mes
					  ->get() // Ejecuta la consulta
					  ->getRow(); // Obtiene la primera fila del resultado
  
        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0
		if($mes != 1){
			return $result ? (float) $result->total_ingresado_fisico : 0.0;
		}else{
			return 0.0;
		}

    }

	public function getSumaSalidaValorado(int $idBodega, int $mes): float
    {
        $result = $this->selectSum('sal_valorado', 'total_egresado') // Selecciona la suma y le da un alias
						->join('ordenes', 'ordenes.id_orden = adquisicion_producto.id_orden')
						 ->where('adquisicion_producto.id_bodega', $idBodega)
						 ->where('adquisicion_producto.tipo_movimiento', 0)
						 ->where('MONTH(ordenes.fecha_aprobado)', $mes) // Condición para el mes
                         ->get()
                         ->getRow(); // Obtiene la primera fila del resultado

        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0
        return $result ? (float) $result->total_egresado : 0.0;
    }

	public function getSumaSalidaFisico(int $idBodega, int $mes): float
    {
        $result = $this->selectSum('cant_salida', 'total_salida_fisico') // Selecciona la suma y le da un alias
						 ->join('ordenes', 'ordenes.id_orden = adquisicion_producto.id_orden')
						 ->where('adquisicion_producto.id_bodega', $idBodega)
						 ->where('adquisicion_producto.tipo_movimiento', 0)
						 ->where('MONTH(ordenes.fecha_aprobado)', $mes) // Condición para el mes
                         ->get()
                         ->getRow(); // Obtiene la primera fila del resultado

        // Si hay un resultado, devuelve el valor sumado; de lo contrario, devuelve 0.0
        return $result ? (float) $result->total_salida_fisico : 0.0;
    }

	public function createAdquisicionProducto($data)
	{
		return $this->insert($data);
	}



	public function updateAdquisicionProducto($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteAdquisicionProducto($id)
	{
		return $this->delete($id);
	}

	public function getAdquisicionesByIdBodega($id_bodega)
	{
		return $this->where('id_bodega', $id_bodega)->findAll();
	}

	public function getProductosDistintosPorBodega(int $idBodega)
    {
        return $this->select('id_producto')
                    ->distinct()
                    ->where('id_bodega', $idBodega)
                    ->findAll();
    }

	//para R5 R6
	public function getPrecioPromedioAdquisicion(int $id_producto)
    {

        $result = $this->selectAvg('precio_adquisicion', 'precioPromedio') // Selecciona la suma y le da un alias
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento', 1)
                         ->get()
                         ->getRow(); // Obtiene la primera fila del resultado

		return $result ? (float) $result->precioPromedio : 0.0;
    }

	public function getSaldoInicialFisicoValoradoProducto(int $id_producto)
    {
        $result = $this ->select('id_producto, cant_ingreso, ing_valorado')
						->where('id_producto', $id_producto)
						->where('tipo_movimiento', 1)
						->where('id_nro_adquisicion', 1)
                        ->first(); // Obtiene la primera fila del resultado
		return $result;
    }

	public function getCantIngresoR56($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('cant_ingreso','cant_ingreso')
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento',1)
						 ->where('id_nro_adquisicion <>',1)
                         ->get();

        $resultado = $query->getRow();
        return $resultado->cant_ingreso;
	}

	public function getCantSalidaR56($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('cant_salida','cant_salida')
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento',0)
                         ->get();

        $resultado = $query->getRow();
        return $resultado->cant_salida;
	}

	public function getIngValoradoR56($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('ing_valorado','ing_valorado')
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento',1)
						 ->where('id_nro_adquisicion <>',1)
                         ->get();

        $resultado = $query->getRow();
        return $resultado->ing_valorado;
	}

	public function getSalValoradoR56($id_producto)
	{
        $query = $this->db->table($this->table)
                         ->selectSum('sal_valorado','sal_valorado')
						 ->where('id_producto', $id_producto)
						 ->where('tipo_movimiento',0)
                         ->get();

        $resultado = $query->getRow();
        return $resultado->sal_valorado;
	}


	//fin funciones para R5 R6

	/// ia
	public function obtenerMovimientosOrdenados($idBodega, $idProducto)
    {
        return $this->where('id_bodega', $idBodega)
                    ->where('id_producto', $idProducto)
                    ->orderBy('id_adquisicion_producto', 'ASC')
                    ->findAll();
    }	
}
