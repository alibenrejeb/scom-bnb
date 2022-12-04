<?php

namespace App\Form;

use App\Entity\Role;
use App\Repository\RoleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr'=> [
                    'placeholder' => ''
                ]
            ])
            ->add('title', TextType::class, [
                'attr'=> [
                    'placeholder' => ''
                ]
            ])
        ;
        // $builder
        //     ->add('role', EntityType::class, [
        //         'class' => Role::class,
        //         'choice_label' => 'name',
        //         'query_builder' => function(RoleRepository $repo) {
        //             return $repo->createQueryBuilder('r')
        //                 ->andWhere('r.title != :role')
        //                 ->setParameter('role', 'ROLE_SUPER_ADMIN')
        //                 ->getQuery()
        //                 ->getResult();
        //         }
        //     ])
        // ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Role::class,
        ]);
    }
}
