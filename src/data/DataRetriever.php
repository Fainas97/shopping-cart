<?php

namespace App\data;

class DataRetriever {

    /**
     * @param string $line
     * @param array $cart
     *
     * @return array
     */
    public function processProduct(string $line, array $cart)
    {
        $dataService = new DataService();
        $product = explode(';', $line);
        $product = $dataService->processClass($product);

        return $dataService->formatProduct($product, $cart);
    }

}