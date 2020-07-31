<?php

namespace App\data;

use App\Product;

class DataService {

    private array $currencies;

    private array $rates;

    public function __construct()
    {
        $this->currencies = constant('currencies');
        $this->rates = constant('rates');
    }

    public function getTotalSum(array $cart): string
    {
        $sum = 0;
        foreach ($cart as $product) {
            if ($product->getCurrency() !== 'EUR') {
                $converted = $product->getPrice() * $this->rates[$product->getCurrency()];
                $sum += $converted * $product->getQuantity();
            } else {
                $sum += $product->getPrice() * $product->getQuantity();
            }
        }

        return number_format($sum, 2);
    }

    /**
     * @param Product $product
     * @param array $cart
     *
     * @return array
     */
    public function formatProduct(Product $product, array $cart): array
    {
        if (empty($cart)) {
            $cart[] = $product;

            return $cart;
        }

        if (!in_array($product->getCurrency(), $this->currencies) && $product->getQuantity() >= 1) {
            echo "Product currency is not supported!\n";

            return $cart;
        } elseif ($product->getQuantity() >= 1) {
            $cart[] = $this->addProduct($product, $cart);
        } elseif ($product->getQuantity() <= -1) {
            $cart = $this->removeProduct($product, $cart);
        }

        return $cart;
    }

    /**
     * @param array $product
     *
     * @return Product
     */
    public function processClass(array $product): Product
    {
        $productObject = new Product();
        $productObject->setId($product[0]);
        $productObject->setName($product[1]);
        $productObject->setQuantity($product[2]);
        $productObject->setPrice($product[3]);
        $productObject->setCurrency($product[4]);

        return $productObject;
    }

    /**
     * @param Product $product
     * @param array $cart
     *
     * @return product
     */
    private function addProduct(Product $product, array $cart): Product
    {
        $isAddedAlready = $this->isAddedProduct($product, $cart);
        if (is_int($isAddedAlready)) {
            return $this->updateProduct($product, $cart[$isAddedAlready]);
        }

        return $product;
    }

    /**
     * @param Product $product
     * @param array $cart
     *
     * @return array
     */
    private function removeProduct(Product $product, array $cart): array
    {
        $quantityRemove = abs($product->getQuantity());
        $itemCount = count($cart);
        foreach (array_reverse($cart) as $index => $item) {
            $index = $itemCount - 1 - $index;
            if ($item->getId() === $product->getId()) {
                [$cart, $quantityRemove] = $this->removeCounter($cart, $item, $quantityRemove, $index);

                if ($quantityRemove == 0) {
                    break;
                }
            }
        }

        return $cart;
    }

    /**
     * @param array $cart
     * @param Product $item
     * @param int $quantityRemove
     * @param int $index
     *
     * @return array
     */
    private function removeCounter(array $cart, Product $item, int $quantityRemove, int $index): array
    {
        if ($quantityRemove >= $item->getQuantity()) {
            unset($cart[$index]);
            $quantityRemove -= $item->getQuantity();
        } else {
            $left = $item->getQuantity() - $quantityRemove;
            $item->setQuantity($left);
        }

        return [$cart, $quantityRemove];
    }

    /**
     * @param Product $product
     * @param array $cart
     *
     * @return bool|integer
     */
    private function isAddedProduct(Product $product, array $cart)
    {
        foreach ($cart as $index => $item) {
            if ($item->getId() === $product->getId()) {
                return $index;
            }
        }

        return false;
    }

    /**
     * @param Product $product
     * @param Product $cartProduct
     *
     * @return Product
     */
    private function updateProduct(Product $product, Product $cartProduct): Product
    {
        if ($product->getCurrency() !== $cartProduct->getCurrency()) {
            $this->convertCurrenciesAndPrices($product, $cartProduct);
        }

        return $product;
    }

    /**
     * @param Product $product
     * @param Product $cartProduct
     */
    private function convertCurrenciesAndPrices(Product $product, Product $cartProduct): void
    {
        if ($cartProduct->getCurrency() != 'EUR') {
            $amount = intval($product->getPrice() * $this->rates[$cartProduct->getCurrency()]);
        } else {
            $amount = intval($product->getPrice() / $this->rates[$product->getCurrency()]);
        }

        $product->setPrice($amount);
        $product->setCurrency($cartProduct->getCurrency());
    }

}