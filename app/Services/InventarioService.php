<?php

namespace App\Services;

use App\Models\AdquisicionProductoModel;

class InventarioService
{
    protected $model;

    public function __construct()
    {
        $this->model = new AdquisicionProductoModel();
    }

    public function recalcularPeps($idBodega, $idProducto)
    {
        $movimientos = $this->model->obtenerMovimientosOrdenados($idBodega, $idProducto);

        $saldoFisicoAnterior   = 0;
        $saldoValoradoAnterior = 0;

        foreach ($movimientos as $index => $mov) {

            $id = $mov['id_adquisicion_producto'];

            // ============================================
            // =============== INGRESO =====================
            // ============================================
            if ($mov['tipo_movimiento'] == 1) {

                $cantIngreso  = floatval($mov['cant_ingreso']);
                $precio       = floatval($mov['precio_adquisicion']);

                // Cant existente = cantidad del ingreso
                $cantExistente = $cantIngreso;

                // Primer registro
                if ($index == 0) {
                    $saldoFisico  = $cantIngreso;
                    $ingValorado  = $cantIngreso * $precio;
                    $saldoValorado = $ingValorado;
                } 
                else {
                    $saldoFisico  = $saldoFisicoAnterior + $cantIngreso;
                    $ingValorado  = $cantIngreso * $precio;
                    $saldoValorado = $saldoValoradoAnterior + $ingValorado;
                }

                // Guardar valores
                $this->model->update($id, [
                    'cant_existente' => $cantExistente,
                    'saldo_fisico' => $saldoFisico,
                    'ing_valorado' => $ingValorado,
                    'saldo_valorado' => $saldoValorado
                ]);

                // Actualizar variables acumuladas
                $saldoFisicoAnterior   = $saldoFisico;
                $saldoValoradoAnterior = $saldoValorado;
            }

            // ============================================
            // =============== SALIDA ======================
            // ============================================
            if ($mov['tipo_movimiento'] == 0) {

                $cantSalida = floatval($mov['cant_salida']);

                // Se descuenta sobre capas PEPS
                $capas = $this->model->where('tipo_movimiento', 1)
                                     ->where('id_producto', $idProducto)
                                     ->where('id_bodega', $idBodega)
                                     ->orderBy('id_adquisicion_producto', 'ASC')
                                     ->findAll();

                $cantidadPendiente = $cantSalida;
                $salValorado = 0;

                foreach ($capas as $capa) {
                    if ($cantidadPendiente <= 0) break;

                    $existenteCapa = floatval($capa['cant_existente']);

                    if ($existenteCapa <= 0) continue;

                    $idCapa = $capa['id_adquisicion_producto'];
                    $precio = floatval($capa['precio_adquisicion']);

                    // → Si alcanza la capa
                    if ($existenteCapa >= $cantidadPendiente) {

                        // Restar de esa capa
                        $nuevoExistente = $existenteCapa - $cantidadPendiente;

                        // Valor PEPS
                        $salValorado += $cantidadPendiente * $precio;

                        // Actualizar capa
                        $this->model->update($idCapa, [
                            'cant_existente' => $nuevoExistente
                        ]);

                        $cantidadPendiente = 0;
                    }
                    else {

                        // → La capa no alcanza, usar todo y buscar la siguiente
                        $salValorado += $existenteCapa * $precio;

                        $this->model->update($idCapa, [
                            'cant_existente' => 0
                        ]);

                        $cantidadPendiente -= $existenteCapa;
                    }
                }

                // Nuevo saldo físico
                $saldoFisico = $saldoFisicoAnterior - $cantSalida;

                // Saldo valorado
                $saldoValorado = $saldoValoradoAnterior - $salValorado;

                // Actualizar salida
                $this->model->update($id, [
                    'saldo_fisico'  => $saldoFisico,
                    'sal_valorado'  => $salValorado,
                    'saldo_valorado'=> $saldoValorado
                ]);

                // Actualizar acumuladores
                $saldoFisicoAnterior   = $saldoFisico;
                $saldoValoradoAnterior = $saldoValorado;
            }
        }

        return true;
    }
}
