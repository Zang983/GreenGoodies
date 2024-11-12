<?php

namespace App\Controller;

use App\Entity\Product;
use CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(private RequestStack $requestStack)
    {
    }
    #[Route('/product/{id}', name: 'detail_product')]
    public function index(Product $product): Response
    {
        $cart = CartService::getCart($this->requestStack->getSession());
        return $this->render('product/detail.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product,
            'quantity'=> CartService::getProductQuantity($this->requestStack->getSession(),$product),
        ]);
    }
}
