<?php

use App\data\DataService;
use App\Product;
use App\data\DataRetriever;

require_once realpath('vendor/autoload.php');

define('currencies', ['EUR', 'USD', 'GBP']);
define('rates', ['USD' => 1.14, 'GBP' => 0.88]);

$cart = [];
$product = new Product();
$dataRetriever = new DataRetriever();
$dataService = new DataService();

$fp = @fopen('data.txt', 'r');
$array = explode(PHP_EOL, fread($fp, filesize('data.txt')));

foreach ($array as $line) {
    $cart = $dataRetriever->processProduct($line, $cart);
    $totalSum = $dataService->getTotalSum($cart);
    echo 'Total cart sum: ' . $totalSum . "€\n";
}