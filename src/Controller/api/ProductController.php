<?php

namespace App\Controller\api;


use App\Entity\User;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class ProductController extends AbstractController
{
    #[Route('/api/products', name: 'api_products', methods: ['GET'])]
    function getProducts(ProductRepository $repo, SerializerInterface $serializer, Security $security): JsonResponse
    {
        /**
         * @var User $user
         */
        $user = $security->getUser();
        // Check if the user is authenticated
        if (!$security->isGranted('ROLE_USER') || !$user->isApiAccess()) {
            return new JsonResponse(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }
        $products = $serializer->serialize($repo->findAll(), 'json', ['groups' => 'product:read']);
        return new JsonResponse($products, Response::HTTP_OK, [], json: true);
    }
}