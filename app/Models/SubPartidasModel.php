<?php

namespace App\Models;

use CodeIgniter\Model;

class SubPartidasModel extends Model
{
	protected $table = 'rangos_partida';
	protected $primaryKey = 'id_rangos_partida';
	protected $allowedFields = ['id_partida', 'descripcion'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAllSubPartidaByIdPartida($id_partida)
	{
		return $this->where('id_partida',$id_partida)->findAll();
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
