<?php

namespace App\Controllers;

class Admin extends BaseController
{
    public function default()
    {
        $db = \Config\Database::connect();
        $query = $db->query('SET FOREIGN_KEY_CHECKS=0;');
        $query = $db->query('TRUNCATE TABLE `recursos`;');
        $query = $db->query('TRUNCATE TABLE `productos`;');
        $query = $db->query('TRUNCATE TABLE `unidades_medida`;');
        $query = $db->query('TRUNCATE TABLE `proveedores`;');
        $query = $db->query('TRUNCATE TABLE `bodegas`;');
        $query = $db->query('TRUNCATE TABLE `origen_destino`;');
        $query = $db->query('TRUNCATE TABLE `gestiones`;');
        $query = $db->query('TRUNCATE TABLE `nro_adquisicion`;');
        $query = $db->query('SET FOREIGN_KEY_CHECKS=1;');
        $query = $db->query('INSERT INTO recursos VALUES("travelnestor03@gmail.com", "DANIEL CANAZA", 1, 1,NULL,NULL)');
        $query = $db->query('INSERT INTO unidades_medida VALUES(1, "SIN UNIDAD", 1, 1, NULL)');
        $query = $db->query('INSERT INTO proveedores VALUES(1, "SIN RAZÓN SOCIAL","0", "OTRO",1,"SN","","SN", "", NULL, 0, NULL)');
        $query = $db->query('INSERT INTO gestiones VALUES(1, "2024", 1)');
        return redirect('/');
    }

}
