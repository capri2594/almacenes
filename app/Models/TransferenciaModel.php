<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferenciaModel extends Model
{
	protected $table = 'transferencia';
	protected $primaryKey = 'id_transferencia';
	protected $allowedFields = ['id_apertura', 'id_sub_apertura', 'id_apertura_destino', 'id_sub_apertura_destino', 'recurso_username', 'fecha_transferencia', 'glosa', 'id_bodega', 'estado_transferencia'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getTransferencia($id)
	{
		return $this->find($id);
	}

	public function getTransferenciaByUsername($username)
	{
		$resultado = $this->where('recurso_username',$username)->orderBy('id_transferencia', 'desc')->findAll();
		return $resultado;
	}

	public function getTransferenciaByIdBodega($id_bodega)
	{
		$resultado = $this->where('id_bodega',$id_bodega)->orderBy('id_transferencia', 'desc')->findAll();
		return $resultado;
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
