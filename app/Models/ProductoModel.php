<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductoModel extends Model
{
	protected $table = 'productos';
	protected $primaryKey = 'id_producto';
	protected $allowedFields = ['id_bodega', 'id_unidad_medida', 'nombre_producto', 'estado_producto', 'log_producto', 'id_partida', 'codigo'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getProductos()
	{
		return $this->findAll();
	}

	public function getProductosHabilitados()
	{
		return $this->findAll();
	}

	public function getProductosByIdBodega($id_bodega)
	{
		return $this->where('id_bodega',$id_bodega)->findAll();
	}

	public function getProducto($id)
	{
		return $this->find($id);
	}

	public function getUltimoProductoBy($id_partida)
	{
		return $this->where('id_partida', $id_partida)->orderBy('id_producto', 'DESC')->limit(1)->findAll();
	}

	public function getUltimoProductoByIdBodega($id_bodega)
	{
		return $this->where('id_bodega', $id_bodega)->orderBy('id_producto', 'DESC')->first();
	}

	public function createProducto($data)
	{
		return $this->insert($data);
	}

	public function updateProducto($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteProducto($id)
	{
		return $this->delete($id);
	}

	public function getProductosByIdBodegaR56($id_bodega)
	{
		return $this->select('productos.id_producto, productos.nombre_producto, unidades_medida.nombre_unidad_medida, id_partida, productos.id_bodega')
					->where('productos.id_bodega', $id_bodega)
					->join('unidades_medida', 'productos.id_unidad_medida = unidades_medida.id_unidad_medida')
					->orderBy('productos.id_producto', 'ASC')
					->findAll();
	}

}
