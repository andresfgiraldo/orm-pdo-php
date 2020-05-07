<?php

require_once(dirname(dirname(__FILE__)) . '/controller/ctr.pais.php');

// PARA EFECTOS DE PRUEBAS, SE ASIGNA LA VARIABLE
$_SESSION['app']['user']['username'] = 'andresfgiraldo';


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    extract($_GET, EXTR_PREFIX_ALL, "v");

    switch ($v_source) {
        case "listar":
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(200);

            $respuesta_pais = CtrPais::consultarTodos();
            echo json_encode($respuesta_pais);

            return;
            break;

        case "info":
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(200);

            $respuesta_pais = CtrPais::consultar($v_id);
            echo json_encode($respuesta_pais);

            return;
            break;

        default:
            /*
            * Si no se llama algun metodo autorizado: 400 Bad Request
            */
            http_response_code(400);
            return;
            break;
    }
} else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    extract($_POST, EXTR_PREFIX_ALL, "v");

    switch ($v_source) {
        case "agregar":
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(200);

            $respuesta_pais = CtrPais::crear($v_alfa2, $v_alfa3, $v_numerico, $v_nombre);
            echo json_encode($respuesta_pais);

            return;
            break;

        case "actualizar":
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(200);

            $respuesta_pais = CtrPais::actualizar($v_alfa2, $v_alfa3, $v_numerico, $v_nombre);
            echo json_encode($respuesta_pais);

            return;
            break;

        case "eliminar":
            header("Content-Type: application/json; charset=UTF-8");
            http_response_code(200);

            $respuesta_pais = CtrPais::eliminar($v_alfa2);
            echo json_encode($respuesta_pais);

            return;
            break;

        default:
            /*
            * Si no se llama algun metodo autorizado: 400 Bad Request
            */
            http_response_code(400);
            return;
            break;
    }
}
