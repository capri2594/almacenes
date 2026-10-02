<?php

namespace App\Models;

use CodeIgniter\Model;

class BodegasModel extends Model
{
	protected $table = 'bodegas';
	protected $primaryKey = 'id_bodega';
	protected $allowedFields = ['nombre_bodega', 'direccion_bodega', 'estado_bodega', 'control_expiracion', 'recurso_username', 'contador', 'log_bodega', 'atender_solicitud', 'contador_ingreso_salida'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getBodegas($orden=null)
	{
		if(is_null($orden))
			return $this->findAll();
		else{
			return $this->orderBy($orden, 'ASC')->findAll();
		}
	}

	public function getBodegasHabilitadas()
	{
		return $this->where('estado_bodega',1)->findAll();
	}

	public function getBodegaByUsername($username)
	{
		$resultado = $this->where('recurso_username',$username)->limit(1)->findAll();
		if (!isset($resultado[0]))
			return false;
		else
			return $resultado[0];
	}

	public function getBodega($id)
	{
		return $this->find($id);
	}

	public function getBodegaById($id)
	{
		return $this->where('id_bodega', $id)->limit(1)->findAll();
	}

	public function createBodega($data)
	{
		return $this->insert($data);
	}

	public function updateBodega($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteBodega($id)
	{
		return $this->delete($id);
	}
	
	public function getBodegasR56()
	{
		return $this->select('id_bodega, nombre_bodega')
					->where('estado_bodega',1)
					->findAll();
	}
}
