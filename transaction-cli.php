<?php
include_once './src/controllers/TransactionController.php';
$lib = new TransactionController();

$referenceId = $argv[1];
$status = $argv[2];

$result = $lib->update($referenceId, $status);
print_r($result);
