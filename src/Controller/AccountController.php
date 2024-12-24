<?php

namespace App\Controller;

use App\Form\PasswordUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-votre-mot-de-passe', name: 'app_account_modify_password')]
    public function password(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }
        
        return $this->render('account/password.html.twig', [
            'updatePasswordForm' => $form->createView()
        ]);
    }
}
