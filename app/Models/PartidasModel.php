<?php

namespace App\Models;

use CodeIgniter\Model;

class PartidasModel extends Model
{
	protected $table = 'partida';
	protected $primaryKey = 'id_partida';
	protected $allowedFields = ['id_partida', 'descripcion'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getPartidas()
	{
		return $this->findAll();
	}

	public function getpartida($id)
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
