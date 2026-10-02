<?php

namespace App\Models;

use CodeIgniter\Model;

class AdquisicionProductoTieneAppModel extends Model
{
	protected $table = 'adquisicion_producto_tiene_app';
	protected $primaryKey = 'id_adquisicion_producto_tiene_app';
	protected $allowedFields = ['id_bodega','id_adquisicion_producto', 'id_apertura', 'id_sub_apertura', 'id_producto', 'cantidad'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAdquisicionProductoTieneAppById($id_adquisicion_producto_tiene_app)
	{
		return $this->find($id_adquisicion_producto_tiene_app);
	}

	public function getByIdBodegaIdAperturaIdSubApertura($id_bodega, $id_apertura, $id_sub_apertura)
	{
		return $this->where('id_bodega', $id_bodega)
					->where('id_apertura', $id_apertura)
					->where('id_sub_apertura', $id_sub_apertura)
					->orderBy('cantidad', 'ASC')
					->findAll();
	}

	public function getByIdBodegaIdAperturaIdSubAperturaIdProducto($id_bodega, $id_apertura, $id_sub_apertura, $id_producto)
	{
		return $this->where('id_bodega', $id_bodega)
					->where('id_apertura', $id_apertura)
					->where('id_sub_apertura', $id_sub_apertura)
					->where('id_producto', $id_producto)
					->findAll();
	}

	public function getCantExistente($id_bodega, $id_apertura, $id_sub_apertura, $id_producto)
	{
		return $this->where('id_bodega', $id_bodega)
					->where('id_apertura', $id_apertura)
					->where('id_sub_apertura', $id_sub_apertura)
					->where('id_producto', $id_producto)
					->first();
	}

	public function getParaActualizarDevolucion($id_bodega, $id_apertura, $id_sub_apertura, $id_producto, $id_adquisicion_producto)
	{
		return $this->where('id_bodega', $id_bodega)
				->where('id_apertura', $id_apertura)
				->where('id_sub_apertura', $id_sub_apertura)
				->where('id_producto', $id_producto)
				->where('id_adquisicion_producto', $id_adquisicion_producto)
				->first();
	}

	public function insertar($data)
	{
		return $this->insert($data);
	}

	public function actualizar($id_adquisicion_producto_tiene_app, $data)
	{
		return $this->update($id_adquisicion_producto_tiene_app, $data);
	}

	public function eliminar($id_adquisicion_producto_tiene_app)
	{
		return $this->delete($id_adquisicion_producto_tiene_app);
	}
}
