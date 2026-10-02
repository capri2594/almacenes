<?php

namespace App\Models;

use CodeIgniter\Model;

class IngresoSalidaItemsModel extends Model
{
	protected $table = 'ingreso_salida_items';
	protected $primaryKey = 'id_ingreso_salida_items';
	protected $allowedFields = ['id_bodega', 'id_unidad_medida', 'nombre_producto', 'id_partida', 'id_ingreso_salida', 'cantidad', 'precio_unitario'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getIngresoSalidaItems()
	{
		return $this->findAll();
	}

	public function getIngresoSalidaItemsHabilitados()
	{
		return $this->findAll();
	}

	public function getIngresoSalidaItemsByIdBodega($id_bodega)
	{
		return $this->where('id_bodega',$id_bodega)->findAll();
	}

	public function getIngresoSalidaItemsById($id)
	{
		return $this->find($id);
	}

	public function getUltimoIngresoSalidaItemsBy($id_partida)
	{
		return $this->where('id_partida', $id_partida)->orderBy('id_ingreso_salida_items', 'DESC')->limit(1)->findAll();
	}

	public function createIngresoSalidaItems($data)
	{
		return $this->insert($data);
	}

	public function updateIngresoSalidaItems($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteIngresoSalidaItems($id)
	{
		return $this->delete($id);
	}
}
