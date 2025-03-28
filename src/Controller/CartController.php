<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderHasProduct;
use App\Entity\Product;
use App\Entity\User;
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
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    #[Route('/cart', name: 'cart', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'cart' => CartService::getCart($this->requestStack->getSession()),
            'controller_name' => 'CartController',
            'amount' => CartService::calcAmount($this->requestStack->getSession())
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_product', methods: ['POST'])]
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

    #[Route('/cart/remove/{id}', name: 'remove_product', methods: ['GET'])]
    public function removeProduct(Product $product): RedirectResponse
    {
        $cart = CartService::removeProduct($product, session: $this->requestStack->getSession());
        CartService::saveCart($cart, $this->requestStack->getSession());
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/clear', name: 'clear_cart', methods: ['GET'])]
    public function clearCart(): RedirectResponse
    {
        CartService::clearCart($this->requestStack->getSession());
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/checkout', name: 'checkout', methods: ['GET'])]
    public function checkout(EntityManagerInterface $entityManager): RedirectResponse
    {
        $cart = CartService::getCart($this->requestStack->getSession());
        if (empty($cart)) {
            $this->addFlash('warning', 'Your cart is empty.');
            return $this->redirectToRoute('cart');
        }
        //If cart isn't empty, create an order
        $user = $this->getUser();
        /** @var User $user */
        $order = CartService::createOrder($this->requestStack->getSession(), $user);
        $entityManager->persist($order);
        $entityManager->flush();

        //When order is created add products to orderHasProduct
        foreach ($cart as $item) {
            $orderHasProduct = CartService::createOrderHasProduct($item["product"], $order, $item['quantity']);
            $entityManager->persist($orderHasProduct);
        }
        $entityManager->flush();
        CartService::clearCart($this->requestStack->getSession());

        $this->addFlash('success', 'Order successfully created.');
        $this->addFlash("orderId", $order->getId());
        return $this->redirectToRoute('account');
    }
}
