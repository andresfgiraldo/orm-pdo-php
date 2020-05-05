<?php

require_once(dirname(dirname(__FILE__)) . '/model/mdl.pais.php');

class CtrPais
{

    static public function crear($alfa2, $alfa3, $numerico, $nombre)
    {

        if (
            isset($alfa2, $alfa3, $numerico, $nombre) &&
            $alfa2 != "" && $alfa2 != NULL &&
            $alfa3 != "" && $alfa3 != NULL &&
            $numerico != "" &&  $numerico != NULL &&
            $nombre != "" && $nombre != NULL
        ) {

            $obj_pais = new Pais();
            $obj_pais->setProperty('alfa2', $alfa2);
            $obj_pais->setProperty('alfa3', $alfa3);
            $obj_pais->setProperty('numerico', $numerico);
            $obj_pais->setProperty('nombre', $nombre);

            return $obj_pais->insert();
        } else {
            return array("success" => FALSE, "action" => "CREAR", "code" => "Debe ingresar todos los parámetros.");
        }
    }

    static public function actualizar($alfa2, $alfa3, $numerico, $nombre)
    {

        if (
            isset($alfa2, $alfa3, $numerico, $nombre) &&
            $alfa2 != "" && $alfa2 != NULL &&
            $alfa3 != "" && $alfa3 != NULL &&
            $numerico != "" &&  $numerico != NULL &&
            $nombre != "" && $nombre != NULL
        ) {
            $obj_pais = new Pais($alfa2);
            $obj_pais->setProperty('alfa3', $alfa3);
            $obj_pais->setProperty('numerico', $numerico);
            $obj_pais->setProperty('nombre', $nombre);

            return $obj_pais->update();
        } else {
            return array("success" => FALSE, "action" => "ACTUALIZAR", "code" => "Debe ingresar todos los parámetros.");
        }
    }

    static public function eliminar($alfa2)
    {

        if (isset($alfa2) && $alfa2 != "" && $alfa2 != NULL) {
            $obj_pais = new Pais($alfa2);
            return $obj_pais->delete();
        } else {
            return array("success" => FALSE, "action" => "ELIMINAR", "code" => "Debe ingresar todos los parámetros.");
        }
    }

    static public function consultar($alfa2)
    {

        if (isset($alfa2) && $alfa2 != "" && $alfa2 != NULL) {
            $obj_pais = new Pais($alfa2);
            return $obj_pais->getThisAllProperties();
        } else {
            return array("success" => FALSE, "action" => "CONSULTAR", "code" => "Debe ingresar todos los parámetros.");
        }
    }

    static public function consultarTodos()
    {
        $obj_pais = new Pais();
        return $obj_pais->selectAll();
    }
}
