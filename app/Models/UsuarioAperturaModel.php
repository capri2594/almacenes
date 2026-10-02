<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioAperturaModel extends Model
{
	protected $table = 'usuario_apertura';
	protected $primaryKey = 'id_usuario_apertura';
	protected $allowedFields = ['username', 'id_apertura', 'id_sub_apertura'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getUsuarioApertura($id)
	{
		return $this->find($id);
	}

	public function getAperturaByUsername($username)
	{
		$resultado = $this->where('username',$username)->findAll();
		return $resultado;
	}
	public function getIdAperturaUsername($id_apertura, $username)
	{
		$resultado = $this->where('id_apertura',$id_apertura)->where('username',$username)->findAll();
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
