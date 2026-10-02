<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioBodegaModel extends Model
{
	protected $table = 'usuario_bodega';
	protected $primaryKey = 'id_usuario_bodega';
	protected $allowedFields = ['username', 'id_bodega'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getUsuarioBodega($id)
	{
		return $this->find($id);
	}

	public function getBodegaByUsername($username)
	{
		$resultado = $this->where('username',$username)->findAll();
		return $resultado;
	}
	public function getIdBodegaUsername($id_bodega, $username)
	{
		$resultado = $this->where('id_bodega',$id_bodega)->where('username',$username)->findAll();
		return $resultado;
	}

	public function crear($data)
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
