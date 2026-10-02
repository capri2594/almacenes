<?php

namespace App\Models;

use CodeIgniter\Model;

class IngresoSalidaModel extends Model
{
	protected $table = 'ingreso_salida';
	protected $primaryKey = 'id_ingreso_salida';
	protected $allowedFields = ['id_bodega', 'fecha_ingreso_salida', 'id_proveedor', 'hoja_ruta', 'tipo_documento', 'nro_tipo_documento', 'doc_constancia', 'nro_doc_constancia', 'observaciones', 'tipo_adquisicion', 'id_gestion', 'nro_correlativo', 'fecha_ingreso_sistema', 'doc_upload', 'estado_ingreso_salida','id_apertura', 'id_sub_apertura', 'log_ingreso_salida'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAll()
	{
		return $this->findAll();
	}

	public function getIngresoSalida($id)
	{
		return $this->find($id);
	}

	public function createIngresoSalida($data)
	{
		return $this->insert($data);
	}

	public function updateIngresoSalida($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteIngresoSalida($id)
	{
		return $this->delete($id);
	}
}
