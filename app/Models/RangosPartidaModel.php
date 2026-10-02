<?php

namespace App\Models;

use CodeIgniter\Model;

class RangosPartidaModel extends Model
{
	protected $table = 'rangos_partida';
	protected $primaryKey = 'id_rangos_partida';
	protected $allowedFields = ['descripcion', 'id_partida'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getRangosPartidas()
	{
		return $this->findAll();
	}

	public function getRangosPartida($id)
	{
		return $this->find($id);
	}

	public function createRangosPartida($data)
	{
		return $this->insert($data);
	}

	public function updateRangosPartida($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteRangosPartida($id)
	{
		return $this->delete($id);
	}
}
