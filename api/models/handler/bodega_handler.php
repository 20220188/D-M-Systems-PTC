<?php
require_once('../../helpers/database.php');


class BodegaHandler {
    protected $id = null;
    protected $nombre = null;
    protected $ubicacion = null;


    public function readAll()
    {
        $sql = 'SELECT id_bodega, bodega
                FROM tb_bodegas
                ORDER BY bodega';
        return Database::getRows($sql);
    }


}
