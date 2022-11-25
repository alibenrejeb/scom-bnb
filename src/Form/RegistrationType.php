<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'firstname',
                TextType::class,
                $this->getConfiguration("Prènom", "Votre prénom")
            )
            ->add(
                'lastname',
                TextType::class,
                $this->getConfiguration("Nom", "Votre nom de famille")
            )
            ->add(
                'email',
                EmailType::class,
                $this->getConfiguration("Email", "Votre adresse email")
            )
            ->add(
                'picture',
                UrlType::class,
                $this->getConfiguration("Photo de profil", "Url de votre avatar")
            )
            ->add(
                'password',
                PasswordType::class,
                $this->getConfiguration("Mot de passe", "Votre mot de passe")
            )
            ->add(
                'passwordConfirm',
                PasswordType::class,
                $this->getConfiguration("Confirmation de mot de passe", "Votre confirmez votre mot de passe")
            )
            ->add(
                'introduction',
                TextType::class,
                $this->getConfiguration("Introduction", "Présentez vous en quelque mots")
            )
            ->add(
                'description',
                TextareaType::class,
                $this->getConfiguration("Description détaillé", "C'est le moment de vous présenter en détails")
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
