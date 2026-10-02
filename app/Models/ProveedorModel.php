<?php

namespace App\Models;

use CodeIgniter\Model;

class ProveedorModel extends Model
{
	protected $table = 'proveedores';
	protected $primaryKey = 'id_proveedor';
	protected $allowedFields = ['razon_social', 'ci_nit', 'tipo_proveedor', 'estado_proveedor', 'nombre_contacto', 'celular_contacto', 'direccion_proveedor', 'telefono_proveedor', 'nota_proveedor', 'id_bodega', 'log_proveedor'];
	protected $returnType = 'array';
	
	protected $useTimestamps = false;
	
	public function getProveedores()
	{
		return $this->findAll();
	}

	public function getProveedoresHabilitados()
	{
		return $this->findAll();
	}

	public function getProveedorBodega($id_bodega)
	{
		return $this->where('id_bodega',$id_bodega)->orWhere('id_bodega',0)->orderBy('razon_social', 'asc')->findAll();
	}

	public function getProveedor($id)
	{
		return $this->find($id);
	}

	public function createProveedor($data)
	{
		return $this->insert($data);
	}

	public function updateProveedor($id, $data)
	{
		return $this->update($id, $data);
	}

	public function deleteProveedor($id)
	{
		return $this->delete($id);
	}
}
