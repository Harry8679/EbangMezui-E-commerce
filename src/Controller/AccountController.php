<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressUserType;
use App\Form\PasswordUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/compte', name: 'app_account')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/modifier-votre-mot-de-passe', name: 'app_account_modify_password')]
    public function password(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(PasswordUserType::class, $user, [
            'passwordHasher' => $passwordHasher
        ]);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            // Redirection après soumission réussie
            $this->addFlash('success', 'Votre mot de passe a été mis à jour avec succès.');

            return $this->redirectToRoute('app_account'); // Redirigez vers une route appropriée
        }

        return $this->render('account/password.html.twig', [
            'updatePasswordForm' => $form->createView(),
        ]);
    }

    #[Route('/compte/adresse', name: 'app_account_address')]
    public function address(): Response
    {
        return $this->render('account/addresses.html.twig');
    }

    #[Route('/compte/adresse/ajouter', name: 'app_account_address_form')]
    public function addressForm(Request $request): Response
    {
        $address = new Address();
        $address->setUser($this->getUser());

        $form = $this->createForm(AddressUserType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($address);
            $this->em->flush();

            $this->addFlash('success', 'Votre adresse a bien été enregistrée.');

            $this->redirectToRoute('app_account');
        }

        return $this->render('account/addressForm.html.twig', [
            'addressForm' => $form->createView()
        ]);
    }
}
