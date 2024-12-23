<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                    ])
                ],
                'label' => 'Votre prénom',
                'attr' => [
                    'placeholder' => 'Veuillez renseigner votre prénom'
                ]
            ])
            ->add('lastName', TextType::class, [
                'constraints' => [
                    new Length([
                        'min' => 2,
                        'max' => 30
                    ])
                ],
                'label' => 'Votre nom',
                'attr' => [
                    'placeholder' => 'Veuillez renseigner votre nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Votre Email',
                'attr' => [
                    'placeholder' => 'Veuillez renseigner votre email'
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
                    'label' => 'Votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez renseigner votre mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'second_options' => [
                    'label' => 'Confirmez votre mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer votre mot de passe'
                    ],
                    'hash_property_path' => 'password'
                ],
                'mapped' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Inscription',
                'attr' => [
                    'class' => 'btn btn-success w-100'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email'
                ])
            ],
            'data_class' => User::class,
        ]);
    }
}
