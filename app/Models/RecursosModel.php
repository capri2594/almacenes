<?php

namespace App\Models;

use CodeIgniter\Model;

class RecursosModel extends Model
{
	protected $table = 'recursos';
	protected $primaryKey = 'username';
	protected $allowedFields = ['username', 'nombre', 'nivel', 'estado_recurso', 'celular'];
	protected $returnType = 'array';
	
	protected $useTimestamps = true;
	protected $dateFormat    = 'datetime';
	protected $createdField  = 'created_at';
	protected $updatedField  = 'updated_at';

	public function getRecursos()
	{
		return $this->findAll();
	}

	public function getRecursosResponsables()
	{
		return $this->asArray()->where(['estado_recurso'=>1, 'nivel'=>2])->findAll();
	}

	public function getRecurso($username)
	{
		return $this->find($username);
	}

	public function createRecurso($data)
	{
		return $this->insert($data);
	}

	public function updateRecurso($username, $data)
	{
		return $this->update($username, $data);
	}

	public function deleteRecurso($id)
	{
		return $this->delete($id);
	}
}
