<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderHasProduct;
use App\Entity\Product;
use CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
//        CartService::clearCart($this->requestStack->getSession());
        return $this->render('cart/index.html.twig', [
            'cart' => CartService::getCart($this->requestStack->getSession()),
            'controller_name' => 'CartController',
            'amount' => CartService::calcAmount($this->requestStack->getSession())
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_product')]
    public function addProduct(Product $product, Request $request): RedirectResponse
    {
        $quantity = $request->get('quantity');
        $session = $this->requestStack->getSession();
        if ($quantity >= 0) {
            $cart = CartService::addProduct($product, $quantity, $session);
            CartService::saveCart($cart, $session);
        }
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
    public function checkout(EntityManagerInterface $entityManager): RedirectResponse
    {
        $cart = CartService::getCart($this->requestStack->getSession());
        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('cart');
        }

        $order = new Order();
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setStatus(0);
        $order->setUser($this->getUser());
        $amount = CartService::calcAmount($this->requestStack->getSession());
        $order->setAmount($amount);
        $order->setShippingAddress("Test address");
        $entityManager->persist($order);
        $entityManager->flush();

        foreach ($cart as $item) {
            $orderHasProduct = new OrderHasProduct();
            $orderHasProduct->setProduct($item['product']);
            $orderHasProduct->setPrice($item['product']->getPrice());
            $orderHasProduct->setQuantity($item['quantity']);
            $orderHasProduct->setOrderReference($order);
            $entityManager->persist($orderHasProduct);
        }
        $entityManager->flush();
        CartService::clearCart($this->requestStack->getSession());

        $this->addFlash('success', 'Order successfully created.');
        $this->addFlash("info", "Order ID: " . $order->getId());
        return $this->redirectToRoute('account');
    }


}
