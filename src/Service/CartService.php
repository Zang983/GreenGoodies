<?php

use App\Entity\OrderHasProduct;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Order;
use App\Entity\User;

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
        $cart = CartService::removeProduct($product, $session);
        if ($quantity > 0) {
            $cart[] = [
                "product" => $product,
                "quantity" => $quantity
            ];
        }
        return $cart;
    }

    static public function removeProduct(Product $product, SessionInterface $session): array
    {
        $cart = self::getCart($session);
        foreach ($cart as $key => $item) {
            if ($item['product']->getId() === $product->getId()) {
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

    static public function calcAmount($session): float
    {
        $cart = self::getCart($session);
        $amount = 0;
        foreach ($cart as $item) {
            $amount += $item['product']->getPrice() * $item['quantity'];
        }
        return $amount;
    }

    static public function getProductQuantity($session, Product $product): int
    {
        $cart = self::getCart($session);
        foreach ($cart as $item) {
            if ($item['product']->getId() === $product->getId()) {
                return $item['quantity'];
            }
        }
        return 0;
    }

    /* This method creates a new order*/
    static public function createOrder($session, User $user): Order
    {
        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus(0);
        $order->setUser($user);
        $amount = CartService::calcAmount($session);
        $order->setAmount($amount);
        $order->setShippingAddress("Test address");

        return $order;
    }

    /* This method creates an Order Has Product entity for the join table. */
    static public function createOrderHasProduct(Product $product, Order $order, int $quantity)
    {
        $orderHasProduct = new OrderHasProduct();
        $orderHasProduct->setProduct($product);
        $orderHasProduct->setPrice($product->getPrice());
        $orderHasProduct->setQuantity($quantity);
        $orderHasProduct->setOrderReference($order);
        return $orderHasProduct;
    }

}