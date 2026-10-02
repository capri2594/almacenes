<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemsOrdenModel extends Model
{
	protected $table = 'items_orden';
	protected $primaryKey = 'id_items_orden';
	protected $allowedFields = ['id_producto', 'id_orden', 'cant_requerida', 'cant_aprobada'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAll()
	{
		return $this->findAll();
	}

	public function getItemsOrden($id)
	{
		return $this->find($id);
	}

	public function getItemsOrdenByIdOrden($id_orden)
	{
		return $this->where('id_orden', $id_orden)->findAll();
	}

	public function verificaDuplicidad($id_orden, $id_producto)
	{
		return $this->where('id_orden', $id_orden)->where('id_producto', $id_producto)->findAll();
	}

	public function deleteItemsOrdenByIdOrden($id_orden)
	{
		return $this->where('id_orden', $id_orden)->delete();
	}

	public function createItemsOrden($data)
	{
		return $this->insert($data);
	}

	public function updateItemsOrden($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteItemsOrden($id)
	{
		return $this->delete($id);
	}
}
