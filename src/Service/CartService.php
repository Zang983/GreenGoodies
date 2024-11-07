<?php

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{

    static public function getCart(SessionInterface $session): array
    {
        $cart = $session->get('cart');
        if ($cart === null) {
            $cart = [];
        }
        return $cart;
    }

    static public function addProduct(Product $product, int $quantity, SessionInterface $session): array
    {
        $cart = self::getCart($session);
        foreach ($cart as $key => $item) {
            if ($item['product'] === $product->getId()) {
                $cart[$key]['quantity'] += $quantity;
                return $cart;
            }
        }
        $cart[] = [
            "product" => $product->getId(),
            "quantity" => 1
        ];
        return $cart;
    }

    static public function removeProduct(Product $product, SessionInterface $session): array
    {
        $cart = self::getCart($session);
        foreach ($cart as $key => $item) {
            if ($item['product'] === $product->getId()) {
                unset($cart[$key]);
            }
        }
        return $cart;
    }

    static public function saveCart($cart, $session): void
    {
        $session->set('cart', $cart);
    }

    static public function clearCart($session): void
    {
        $session->remove('cart');
    }

    static public function calcAmount($cart): float
    {
        $amount = 0;
        foreach ($cart as $item) {
            $amount += $item['product']->getPrice() * $item['quantity'];
        }
        return $amount;
    }

}