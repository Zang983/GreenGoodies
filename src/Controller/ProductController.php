<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'detail_product')]
    public function index(Product $product): Response
    {
        return $this->render('product/detail.html.twig', [
            'controller_name' => 'ProductController',
            'product' => $product
        ]);
    }
}
