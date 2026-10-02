<?php namespace App\Controllers;

use App\Services\InventoryService;

class Inventory extends BaseController
{
    public function procesar($idProducto, $idBodega)
    {
        $servicio = new InventoryService();
        $servicio->procesarPEPS($idProducto, $idBodega);

        return "Cálculo PEPS finalizado.";
    }
}
