<?php

namespace App\Models;

use CodeIgniter\Model;

class NroAdquisicionModel extends Model
{
	protected $table = 'nro_adquisicion';
	protected $primaryKey = 'id_nro_adquisicion';
	protected $allowedFields = ['id_bodega', 'fecha_adquisicion', 'id_proveedor', 'id_apertura_general', 'id_sub_apertura', 'hoja_ruta', 'tipo_documento', 'nro_tipo_documento', 'doc_constancia', 'nro_doc_constancia', 'observaciones', 'tipo_adquisicion', 'id_gestion', 'nro_correlativo', 'fecha_ingreso_sistema', 'doc_upload', 'estado_nro_adquisicion', 'log_nro_adquisicion'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getAll()
	{
		return $this->findAll();
	}

	public function getNroAdquisicion($id)
	{
		return $this->find($id);
	}

	public function getUltimoNroAdquisicion($id)
	{
		return $this->where('id_bodega', $id)->orderBy('id_nro_adquisicion', 'DESC')->first();
	}

	public function createNroAdquisicion($data)
	{
		return $this->insert($data);
	}

	public function updateNroAdquisicion($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteNroAdquisicion($id)
	{
		return $this->delete($id);
	}
}
