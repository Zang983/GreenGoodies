<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\RedirectController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CartController extends AbstractController
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => CartService::getCart($this->requestStack->getSession()),
            'controller_name' => 'CartController',
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_product')]
    public function addProduct(Product $product, int $quantity = 1): RedirectResponse
    {
        $session = $this->requestStack->getSession();
        if ($quantity < 1) {
            $cart = CartService::removeProduct($product, $session);
        }
        else {
            $cart = CartService::addProduct($product, $quantity, $session);
        }
        CartService::saveCart($cart, $session);
        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/remove/{id}', name: 'remove_product')]
    public function removeProduct(Product $product): RedirectResponse
    {
        $cart = CartService::removeProduct($product, $this->requestStack->getSession());
        CartService::saveCart($cart, $this->requestStack->getSession());
        return $this->redirectToRoute('cart');
    }
    #[Route('/cart/clear', name: 'clear_cart')]
    public function clearCart(): RedirectResponse
    {
        CartService::clearCart($this->requestStack->getSession());
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/checkout', name: 'checkout')]
    public function checkout(): RedirectResponse
    {
        $cart = CartService::getCart($this->requestStack->getSession());
        if (empty($cart)) {
            return $this->redirectToRoute('cart');
        }
        $entityManager = $this->getDoctrine()->getManager();
        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus(0);
        $order->setUser($this->getUser());
        $amount = CartService::calcAmount($cart);
        $order->setAmount($amount);
        $order->setShippingAddress("Test address");
        foreach($cart as $item) {
            $orderHasProduct = new OrderHasProduct();
            $orderHasProduct->setProduct($item['product']);
            $orderHasProduct->setQuantity($item['quantity']);
            $orderHasProduct->setOrder($order);
            $entityManager->persist($orderHasProduct);
        }

        return $this->redirectToRoute('cart');
    }

}
