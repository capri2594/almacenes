<?php

namespace App\Models;

use CodeIgniter\Model;

class PartidaModel extends Model
{
	protected $table = 'partida';
	protected $primaryKey = 'id_partida';
	protected $allowedFields = ['descripcion', 'contador'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getPartidas()
	{
		return $this->findAll();
	}

	public function getPartida($id)
	{
		return $this->find($id);
	}

	public function createPartida($data)
	{
		return $this->insert($data);
	}

	public function updatePartida($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deletePartida($id)
	{
		return $this->delete($id);
	}
}
