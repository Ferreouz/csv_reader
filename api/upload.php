<?php

require_once($_SERVER['DOCUMENT_ROOT'] . "/autoload.php");

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');

use Classes\CSVLine;
use Classes\CSVReader;
use Classes\DB;

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("HTTP/1.1 405 Method Not Allowed");
    echo json_encode(["message" => 'Method not allowed']);
    exit;
}
// Check if the file was uploaded without errors
if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
    header("HTTP/1.1 400 Bad Request");
    echo json_encode(["message" => 'Error ao enviar arquivo: ' . $_FILES['file']['error'] . '']);
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
        $arg = [];

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
        $values[] = $line->getData();
    } catch (\Exception $exception) {
        echo json_encode(["error" => $exception->getMessage()]);
        exit;
    }
}
/////TODO: insert in db
echo json_encode(
    $values
);
