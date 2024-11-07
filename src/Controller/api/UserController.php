<?php

namespace App\Controller\api;


use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController {
    #[Route('/api/auth', name: 'api_login', methods: ['POST'])] public function login(JWTTokenManagerInterface $jwtManager
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user instanceof UserInterface) {
            return new JsonResponse(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }
        $token = $jwtManager->create($user);
        return new JsonResponse(['token' => $token], Response::HTTP_OK);
    } }