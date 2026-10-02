<?php

namespace App\Models;

use CodeIgniter\Model;

class AperturasModel extends Model
{
	protected $table = 'aperturas';
	protected $primaryKey = 'id_apertura';
	protected $allowedFields = ['codigo_apertura', 'descripcion_apertura', 'estado_apertura'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAperturas()
	{
		return $this->findAll();
	}

	public function getAperturasHabilitadas()
	{
		return $this->where('estado_apertura',1)->findAll();
	}

	public function getBodegaByUsername($username)
	{
		$resultado = $this->where('recurso_username',$username)->limit(1)->findAll();
		if (!isset($resultado[0]))
			return false;
		else
			return $resultado[0];
	}

	public function getApertura($id)
	{
		return $this->find($id);
	}

	public function insertar($data)
	{
		return $this->insert($data);
	}

	public function actualizar($id, $data)
	{
		return $this->update($id, $data);
	}

	public function eliminar($id)
	{
		return $this->delete($id);
	}
}
