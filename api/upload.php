<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/autoload.php");

header('Content-Type: application/json; charset=utf-8');

use Classes\CSVLine;
use Classes\CSVReader;
use Classes\DB;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
   
    exit;
}
// Check if the file was uploaded without errors
if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
    header("HTTP/1.1 400 Bad Request");
    if($_FILES['file']['error'] === 1) {
        $message .= "Tamanho do arquivo excede o limite";
    }
    echo json_encode(["message" => 'Error ao enviar arquivo: ' . $message ]);
    exit;
}
$fileTmpPath = $_FILES['file']['tmp_name'];
$fileName = $_FILES['file']['name'];
$fileNameCmps = explode(".", $fileName);
$fileExtension = strtolower(end($fileNameCmps));

if ($fileExtension != 'csv') {
    header("HTTP/1.1 400 Bad Request");
   
    echo json_encode(["message" => 'Error ao enviar arquivo: Arquivo não é do tipo csv']);
    exit;
}

$reader = new CSVReader($fileTmpPath);

$data = $reader->getData();
$headerKeys = $reader->getHeaderKeys();

$values = [];
foreach ($data as $index => $arr) {
    try {
        $line = new CSVLine([
            "Nome" => array_key_exists('Nome', $headerKeys) ? $arr[$headerKeys["Nome"]] : null,
            "Sobrenome" => array_key_exists('Sobrenome', $headerKeys) ? $arr[$headerKeys["Sobrenome"]] : null,
            "Email" => array_key_exists('Email', $headerKeys) ? $arr[$headerKeys["Email"]] : null,
            "Telefone" => array_key_exists('Telefone', $headerKeys) ? $arr[$headerKeys["Telefone"]] : null,
            "Endereço" => array_key_exists('Endereço', $headerKeys) ? $arr[$headerKeys["Endereço"]] : null,
            "Cidade" => array_key_exists('Cidade', $headerKeys) ? $arr[$headerKeys["Cidade"]] : null,
            "CEP" => array_key_exists('CEP', $headerKeys) ? $arr[$headerKeys["CEP"]] : null,
            "Data de Nascimento" => array_key_exists('Data de Nascimento', $headerKeys) ? $arr[$headerKeys["Data de Nascimento"]] : null
        ], $index + 1);
        $values[] = $line;
    } catch (\Exception $exception) {
        header("HTTP/1.1 400 Bad Request");
        
        echo json_encode(["error" => $exception->getMessage()]);
        exit;
    }
}
// Example usage:
try {
    $db = new DB(
        getenv("MYSQL_HOST"), 
    "db_campaigns", 
    "root", 
    getenv("MYSQL_ROOT_PASSWORD"));

    $db->insertBulk($values, $_POST["campaignName"]);
    echo json_encode(["message" => "sucesso"]);


} catch (Exception $e) {
    header("HTTP/1.1 400 Bad Request");
   

    echo json_encode(["message" => "Error: " . $e->getMessage()]);
    exit;
}
