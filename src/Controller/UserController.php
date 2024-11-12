<?php

namespace App\Controller;

use App\Form\SignupType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class UserController extends AbstractController
{
    #[Route('/signup', name: 'signup')]

    public function index(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager,

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
        else
        {
            $this->addFlash('error', 'Invalid data');
        }
        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'ProductController',
        ]);
    }


    #[Route('/account', name: 'account')]
    #[IsGranted('ROLE_USER')]
    public function account(SessionInterface $session): Response
    {
        $orders = $this->getUser()->getOrders();
        $flashMessages = $session->getFlashBag()->get('orderId');

        return $this->render('user/account.html.twig', [
            'orders' => $orders,
            'controller_name' => 'ProductController',
            'flashMessages'=>$flashMessages[0] ?? null
        ]);
    }

    #[Route('/toggleApi', name: 'toggle_api')]
    #[IsGranted('ROLE_USER')]
    public function toggleApi(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $user->setApiAccess(!$user->isApiAccess());
        $entityManager->flush();

        return $this->redirectToRoute('account');
    }

    #[Route('delete', name: 'delete_account')]
    #[IsGranted('ROLE_USER')]
    public function deleteAccount(EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $this->getUser();
        $security->logout(false);
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
