<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/autoload.php");

header('Content-Type: application/json; charset=utf-8');

use Classes\DB;

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    header("HTTP/1.1 405 Method Not Allowed");
    exit;
}

try {
    $db = new DB(
        getenv("MYSQL_HOST"), 
    "db_campaigns", 
    "root", 
    getenv("MYSQL_ROOT_PASSWORD"));

    $values = $db->search($_GET["search"] ?? "");
    echo json_encode(["message" => $values]);


} catch (Exception $e) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    exit;
}
