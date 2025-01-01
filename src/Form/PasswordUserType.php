<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class PasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('actualPassword', PasswordType::class, [
                'label' => 'Votre mot de passe actuel',
                'mapped' => false,

                'attr' => [
                    'placeholder' => 'Veuillez renseigner votre mot de passe actuel',
                    'autocomplete' => 'current-password' // Ajout de l'attribut autocomplete
                ]
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'constraints' => [
                    new Length([
                        'min' => 4,
                        'max' => 30
                    ])
                ],
                'first_options' => [
                    'label' => 'Votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez renseigner votre nouveau mot de passe',
                        'autocomplete' => 'new-password' // Ajout de l'attribut autocomplete
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer nouveau votre mot de passe',
                        'autocomplete' => 'new-password' // Ajout de l'attribut autocomplete
                    ],
                    'hash_property_path' => 'password'
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Mettre à jour votre mot de passe',
                'attr' => [
                    'class' => 'btn btn-success w-100'
                ]
            ])
            ->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
                /** @var \Symfony\Component\Form\FormInterface $form */
                $form = $event->getForm();
            
                /** @var \App\Entity\User $user */
                $user = $form->getConfig()->getOptions()['data'];
                // dd($user);
            
                /** @var \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $passwordHasher */
                $passwordHasher = $form->getConfig()->getOptions()['passwordHasher'];
            
                // 1. Récupérer le mot de passe saisi par l'utilisateur et le comparer au mot de passe en base de données.
                // $isValid = $passwordHasher->isPasswordValid($user, $submittedPassword);
                $isValid = $passwordHasher->isPasswordValid($user, $form->get('actualPassword')->getData());
                // 2 si c'est différent renvoyer une erreur
                if (!$isValid) {
                    $form->get('actualPassword')->addError(new FormError('Votre mot de passe actuel n\'est pas conforme. Veuillez vérifier votre saisie'));
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
