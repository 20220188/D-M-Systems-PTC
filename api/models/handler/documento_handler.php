<?php
require_once('../../helpers/database.php');


class DocumentoHandler {
    protected $id = null;
    protected $codigo = null;
    protected $descripcion = null;

   


    public function readAll()
    {
        $sql = 'SELECT id_documento, documento
                FROM tb_documentos
                ORDER BY documento';
        return Database::getRows($sql);
    }
}
