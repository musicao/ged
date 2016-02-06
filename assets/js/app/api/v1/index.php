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


$app->get('/voluntarios', function() {
    global $db;
    $rows = $db->select("v_voluntarios", "*", array('status' => 'A'), "ORDER BY nome ASC");
    $lista = array();

    function Mask($mask, $str) {

        $str = str_replace(" ", "", $str);

        for ($i = 0; $i < strlen($str); $i++) {
            $mask[strpos($mask, "#")] = $str[$i];
        }

        return $mask;
    }
    foreach ($rows["data"] as $row) {

        $voluntario = array(); // temp array
        $voluntario["id"] = $row["id"];
        $voluntario["status"] = $row["status"];
        $voluntario["nome"] = $row["nome"];
        $voluntario["cpf"] = Mask("###.###.###-##",$row["cpf"]);
        $voluntario["descricao"] = $row["descricao"];



        array_push($lista, $voluntario);
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