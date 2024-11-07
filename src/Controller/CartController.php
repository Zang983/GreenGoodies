<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private RequestStack $requestStack)
    {
    }

    #[Route('/cart', name: 'cart')]
    public function index(): Response
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart');
        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'controller_name' => 'CartController',
        ]);
    }
}
