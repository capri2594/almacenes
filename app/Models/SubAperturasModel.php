<?php

namespace App\Models;

use CodeIgniter\Model;

class SubAperturasModel extends Model
{
	protected $table = 'sub_aperturas';
	protected $primaryKey = 'id_sub_apertura';
	protected $allowedFields = ['id_apertura', 'codigo_sub_apertura',  'descripcion_sub_apertura', 'estado_sub_apertura'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getSubAperturasHabilitadas()
	{
		return $this->where('estado_sub_apertura',1)->findAll();
	}

	public function getSubApertura($id)
	{
		return $this->find($id);
	}

	public function getSubAperturaByIdApertura($id_apertura)
	{
		return $this->where('id_apertura',$id_apertura)->where('estado_sub_apertura',1)->findAll();
	}

	public function getAllSubAperturaByIdApertura($id_apertura)
	{
		return $this->where('id_apertura',$id_apertura)->findAll();
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
