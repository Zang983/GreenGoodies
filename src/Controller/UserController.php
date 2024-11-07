<?php

namespace App\Controller;

use App\Form\SignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/signup', name: 'signup')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $form = $this->createForm(SignupType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setRoles(['ROLE_USER']);
            $user->setApiAccess(false);
            $user->setPassword(
                $passwordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('account');
        }

        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'UserController',
        ]);
    }


    #[Route('/account', name: 'account')]
    public function account(): Response
    {
        $orders = $this->getUser()->getOrders();

        return $this->render('user/account.html.twig', [
            'orders' => $orders,
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/toggleApi', name: 'toggle_api')]
    public function toggleApi(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $user->setApiAccess(!$user->isApiAccess());
        $entityManager->flush();

        return $this->redirectToRoute('account');
    }

    #[Route('delete', name: 'delete_account')]
    public function deleteAccount(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_logout');
    }
}
