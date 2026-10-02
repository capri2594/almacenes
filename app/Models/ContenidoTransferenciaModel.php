<?php

namespace App\Models;

use CodeIgniter\Model;

class ContenidoTransferenciaModel extends Model
{
	protected $table = 'contenido_transferencia';
	protected $primaryKey = 'id_contenido_transferencia';
	protected $allowedFields = ['id_producto', 'cantidad_transferencia', 'id_transferencia'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getContenidoTransferencia($id)
	{
		return $this->find($id);
	}

	public function getContenidoTransferenciaByIdTransferencia($id_transferencia)
	{
		return $this->where('id_transferencia', $id_transferencia)->findAll();
	}

	public function getItemTransferenciaByIdTransferenciaIdProducto($id_transferencia, $id_producto)
	{
		return $this->where('id_transferencia', $id_transferencia)->where('id_producto', $id_producto)->findAll();
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
