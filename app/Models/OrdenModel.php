<?php

namespace App\Models;

use CodeIgniter\Model;

class OrdenModel extends Model
{
	protected $table = 'ordenes';
	protected $primaryKey = 'id_orden';
	protected $allowedFields = ['recurso_username', 'fecha_orden', 'estado_orden', 'glosa', 'obj_glosa', 'celular', 'contador', 'id_bodega', 'log_orden', 'fecha_solicitado', 'fecha_aprobado', 'fecha_atendido', 'fecha_devuelto'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAll()
	{
		return $this->findAll();
	}

	public function getOrden($id)
	{
		return $this->find($id);
	}

	public function getMisOrdenes($recurso_username)
	{
		return $this->where('recurso_username', $recurso_username)->orderBy('fecha_orden', 'DESC')->findAll();
	}

	public function getOrdenesSolicitados($id_bodega)
	{
		return $this->groupStart()
						->where('id_bodega', $id_bodega)
						->where('estado_orden', 2)
					->groupEnd()
					->orGroupStart()
						->where('id_bodega', $id_bodega)
						->where('estado_orden', 3)
					->groupEnd()
					->orderBy('contador', 'ASC')
					->findAll();
	}

	public function getOrdenesAtendidas($id_bodega)
	{
		return $this->where('id_bodega', $id_bodega)
						->groupStart()
						->where('estado_orden', 4)
						->orwhere('estado_orden', 5)
					->groupEnd()
					->orderBy('contador', 'ASC')
					->findAll();
	}

	public function ultimoContadorbyIdBodega($id_bodega){
		return $this->where('id_bodega', $id_bodega)->selectMax('contador')->first();
	}
	public function createOrden($data)
	{
		return $this->insert($data);
	}

	public function updateOrden($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteOrden($id)
	{
		return $this->delete($id);
	}
}
