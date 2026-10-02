<?php

namespace App\Models;

use CodeIgniter\Model;

class UnidadesMedidaModel extends Model
{
	protected $table = 'unidades_medida';
	protected $primaryKey = 'id_unidad_medida';
	protected $allowedFields = ['nombre_unidad_medida', 'estado_unidad_medida', 'id_bodega', 'log_unidad_medida'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getUnidadesMedida()
	{
		return $this->findAll();
	}

	public function getUnidadesMedidaHabilitados()
	{
		return $this->findAll();
	}

	public function getUnidadesBodega($id_bodega)
	{
		return $this->where('id_bodega',$id_bodega)->orderBy('nombre_unidad_medida', 'asc')->findAll();
	}

	public function getUnidadMedida($id)
	{
		return $this->find($id);
	}

	public function createUnidadMedida($data)
	{
		return $this->insert($data);
	}

	public function updateUnidadMedida($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteUnidadMedida($id)
	{
		return $this->delete($id);
	}
}
