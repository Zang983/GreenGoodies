<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }
    #[Route('/product/{id}', name: 'detail_product', methods: ['GET'])]
    public function index(Product $product = null): Response
    {
        if ($product === null) {
            return $this->redirectToRoute('home');
        }
        return $this->render('product/detail.html.twig', [
            'product' => $product,
            'quantity'=> CartService::getProductQuantity($this->requestStack->getSession(),$product),
        ]);
    }
}
