<?php

namespace App\Models;

use CodeIgniter\Model;

class OrigenDestinoModel extends Model
{
	protected $table = 'origen_destino';
	protected $primaryKey = 'id_origen_destino';
	protected $allowedFields = ['descripcion', 'estado_origen_destino', 'tipo', 'log_origen_destino'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getOrigenDestino($orden=null)
	{
		if(is_null($orden))
			return $this->findAll();
		else{
			return $this->orderBy($orden, 'ASC')->findAll();
		}
	}

	public function getOrigenDestinoTipo($tipo)
	{
		return $this->orderBy('descripcion', 'ASC')->find($tipo);
	}

	public function getOrigenDestinoById($id)
	{
		return $this->find($id);
	}

	public function createOrigenDestino($data)
	{
		return $this->insert($data);
	}

	public function updateOrigenDestino($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteOrigenDestino($id)
	{
		return $this->delete($id);
	}
}
