<?php
require_once('../../helpers/database.php');


class TipoDocumentoHandler {
    protected $id = null;
    protected $nombre = null;

  

    public function readAll()
    {
        $sql = 'SELECT id_tipo_documento, tipo_documento
                FROM tb_tipos_documento
                ORDER BY tipo_documento';
        return Database::getRows($sql);
    }


}
