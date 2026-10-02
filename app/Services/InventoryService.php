<?php namespace App\Services;

use App\Models\InventoryModel;
use Config\Database;

class InventoryService
{
    protected $mov;

    public function __construct()
    {
        $this->mov = new InventoryModel();
    }

    /**
     * Procesa movimientos PEPS (FIFO) para un producto y bodega.
     */
    public function procesarPEPS(int $idProducto, int $idBodega)
    {
        $db = Database::connect();
        $db->transStart();

        $movimientos = $this->mov->getMovimientos($idProducto, $idBodega);

        // runningSaldo: saldo_valorado acumulado en la pasada
        $runningSaldo = 0.0;

        foreach ($movimientos as $m) {
            $id = (int) $m['id_adquisicion_producto'];
            $tipo = (int) $m['tipo_movimiento'];

            if ($tipo === 1) {
                // INGRESO: calcular ing_valorado y sumar al runningSaldo
                $cantIngreso = (float) ($m['cant_ingreso'] ?? 0.0);
                // si cant_ingreso está vacío, intentar usar cant_existente o saldo_fisico
                if ($cantIngreso <= 0) {
                    $cantIngreso = (float) ($m['cant_existente'] ?? 0.0);
                }

                $precio = (float) ($m['precio_adquisicion'] ?? 0.0);
                $ingVal = $cantIngreso * $precio;

                $runningSaldo += $ingVal;

                // Actualizar fila de ingreso
                $this->mov->update($id, [
                    'ing_valorado'  => $ingVal,
                    'saldo_valorado' => $runningSaldo
                ]);

            } else {
                // SALIDA: calcular sal_valorado usando el ingreso PEPS inmediato anterior disponible
                $cantSalida = (float) ($m['cant_salida'] ?? 0.0);
                if ($cantSalida <= 0) {
                    // nada que hacer si no hay cantidad de salida
                    // pero aún así fijamos saldo_valorado al runningSaldo actual
                    $this->mov->update($id, [
                        'sal_valorado'   => 0,
                        'saldo_valorado' => $runningSaldo
                    ]);
                    continue;
                }

                // Buscar ingreso PEPS disponible (el más cercano hacia arriba)
                $ingreso = $this->mov->buscarIngresoPEPS($idProducto, $idBodega, $id);

                if (!$ingreso) {
                    // No hay ingreso disponible: no se puede valorar correctamente.
                    // Alicuota la salida a 0 o saltear — aquí ponemos sal_valorado = 0 y mantenemos saldo.
                    $this->mov->update($id, [
                        'sal_valorado'   => 0,
                        'saldo_valorado' => $runningSaldo
                    ]);
                    continue;
                }

                // precio del ingreso encontrado
                $precioIngreso = (float) ($ingreso['precio_adquisicion'] ?? 0.0);

                // Calculamos sal_valorado usando la regla indicada: salida * precio del ingreso inmediato.
                $salVal = $cantSalida * $precioIngreso;

                // Restamos del runningSaldo
                $runningSaldo -= $salVal;

                // Actualizamos la fila de salida con sal_valorado y nuevo saldo
                $this->mov->update($id, [
                    'sal_valorado'   => $salVal,
                    'saldo_valorado' => $runningSaldo
                ]);

                // Reducir el cant_existente del ingreso que se consumió
                $nuevoExistente = (float) $ingreso['cant_existente'] - $cantSalida;
                if ($nuevoExistente < 0) {
                    // Si por alguna razón queda negativo, lo limitamos a 0.
                    $nuevoExistente = 0;
                }

                $this->mov->update($ingreso['id_adquisicion_producto'], [
                    'cant_existente' => $nuevoExistente
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            throw new \RuntimeException('Error al procesar PEPS: transacción falló.');
        }

        return true;
    }
}
