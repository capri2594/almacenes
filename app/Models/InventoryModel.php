<?php namespace App\Models;

use CodeIgniter\Model;

class InventoryModel extends Model
{
    protected $table = 'adquisicion_producto';
    protected $primaryKey = 'id_adquisicion_producto';
    protected $allowedFields = [
        'tipo_movimiento',
        'id_orden',
        'id_producto',
        'id_bodega',
        'cant_ingreso',
        'cant_existente',
        'precio_adquisicion',
        'cant_salida',
        'ref_adq',
        'saldo_fisico',
        'ing_valorado',
        'sal_valorado',
        'saldo_valorado'
    ];

	//con ia
 	// public function getMovimientos($idProducto, $idBodega)
    // {
    //     return $this->where('id_producto', $idProducto)
    //                 ->where('id_bodega', $idBodega)
    //                 ->orderBy('id_adquisicion_producto', 'ASC')
    //                 ->findAll();
    // }

    // Buscar el ingreso PEPS disponible
    // public function buscarIngresoPEPS($idProducto, $idBodega, $idActual)
    // {
    //     return $this->where('id_producto', $idProducto)
    //                 ->where('id_bodega', $idBodega)
    //                 ->where('tipo_movimiento', 1)
    //                 ->where('cant_existente >', 0)
    //                 ->where('id_adquisicion_producto <', $idActual)
    //                 ->orderBy('id_adquisicion_producto', 'ASC')
    //                 ->first();
    // }

public function getMovimientos($idProducto, $idBodega)
    {
        return $this->where('id_producto', $idProducto)
                    ->where('id_bodega', $idBodega)
                    ->orderBy('id_adquisicion_producto', 'ASC') // orden cronológico
                    ->findAll();
    }

    /**
     * Buscar el ingreso PEPS disponible más cercano (inmediato superior)
     * => id < idActual y cant_existente > 0; ordenado por id DESC para obtener el más cercano.
     */
    public function buscarIngresoPEPS($idProducto, $idBodega, $idActual)
    {
        return $this->where('id_producto', $idProducto)
                    ->where('id_bodega', $idBodega)
                    ->where('tipo_movimiento', 1)
                    ->where('cant_existente >', 0)
                    ->where('id_adquisicion_producto <', $idActual)
                    ->orderBy('id_adquisicion_producto', 'DESC')
                    ->first();
    }

}
