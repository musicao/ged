<?php

require '.././libs/Slim/Slim.php';
require_once 'dbHelper.php';

// Get Slim instance
\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app = \Slim\Slim::getInstance();

// call our own dbHelper class
$db = new dbHelper();

/* * ********** This can be called via http://localhost/angularcode-megamenu/api/v1/categories
  List all categories where parent=0 and its sub categories *********** */

$app->get('/produtos', function() {
    global $db;
    $rows = $db->select("produtos", "id,status, nome, estoque_minimo as minimo, estoque_maximo as maximo, descricao", array('status' => 'A'), "ORDER BY nome ASC");


    // parent categories node
    $categories = array();



    foreach ($rows["data"] as $row) {

        $category = array(); // temp array
        $category["id"] = $row["id"];
        $category["status"] = $row["status"];
        $category["nome"] = $row["nome"];
        $category["minimo"] = $row["minimo"];
        $category["maximo"] = $row["maximo"];
        $category["descricao"] = $row["descricao"];



        // pushing sinlge category into parent
        array_push($categories, $category);
    }

    echoResponse(200, $categories);
});

function Mask($mask, $str) {

    $str = str_replace(" ", "", $str);

    for ($i = 0; $i < strlen($str); $i++) {
        $mask[strpos($mask, "#")] = $str[$i];
    }

    return $mask;
}

function Telefone($str) {

    $tam = strlen($str);

    if ($tam == 10) {
        return Mask("(##) ####-####", $str);
    } else if ($tam == 11) {
        return Mask("(##) #####-####", $str);
    } else {
        return;
    }
}

$app->get('/voluntarios', function() {
    global $db;
    $rows = $db->select("v_voluntarios", "*", array('status' => 'A'), "ORDER BY nome ASC");
    $lista = array();



    foreach ($rows["data"] as $row) {

        $voluntario = array(); // temp array
        $voluntario["id"] = $row["id"];
        $voluntario["status"] = $row["status"];
        $voluntario["nome"] = $row["nome"];
        $voluntario["cpf"] = Mask("###.###.###-##", $row["cpf"]);
        $voluntario["descricao"] = $row["descricao"];



        array_push($lista, $voluntario);
    }

    echoResponse(200, $lista);
});

$app->get('/usuarios', function() {
    global $db;
    $rows = $db->select("v_usuarios", "*", array('status' => 'A'), "ORDER BY nome ASC");
    $lista = array();


    foreach ($rows["data"] as $row) {

        $usuario = array(); // temp array
        $usuario["id"] = $row["id"];
        $usuario["nome"] = $row["nome"];
        $usuario["telefone"] = Telefone($row["telefone"]);
        $usuario["cidade"] = $row["cod_cidades"];
        $usuario["cpf"] = Mask("###.###.###-##", $row["cpf"]);
        $usuario["idCadastro"] = $row["id_voluntario_cadastro"];
        $usuario["status"] = $row["status"];
        $usuario["nomeCidade"] = $row["nomeCidade"];
        $usuario["sigla"] = $row["sigla"];
        $usuario["nomeEstado"] = $row["nomeEstado"];




        array_push($lista, $usuario);
    }

    echoResponse(200, $lista);
});


$app->get('/cidades', function() {

    global $db;
    $rows = $db->select("cidades", " cod_cidades AS id, nome ", array('estados_cod_estados' => $_GET['estado']), "ORDER BY nome ASC");
    $lista = array();

    foreach ($rows["data"] as $row) {

        $cidade = array(); // temp array
        $cidade["id"] = $row["id"];
        $cidade["nome"] = $row["nome"];

        array_push($lista, $cidade);
    }

    echoResponse(200, $lista);
});

$app->get('/estoque', function() {

    global $db;
    $rows = $db->select("v_estoque", " * ", array(), "ORDER BY nome ASC");
    $lista = array();

    foreach ($rows["data"] as $row) {

        $estoque = array(); // temp array
        $estoque["id"] = $row["id"];
        $estoque["nome"] = $row["nome"];
        $estoque["qtde"] = $row["qtde"];
        $estoque["minimo"] = $row["minimo"];
        $estoque["maximo"] = $row["maximo"];



        array_push($lista, $estoque);
    }

    echoResponse(200, $lista);
});

function echoResponse($status_code, $response) {
    global $app;
    $app->status($status_code);
    $app->contentType('application/json');

    echo json_encode($response);
}

$app->run();
?>