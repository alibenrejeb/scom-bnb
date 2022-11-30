<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\StringToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(StringToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'startDate',
                TextType::class,
                $this->getConfiguration(
                    "Date d'arrivée",
                    "La date à laquelle vous comptez arriver"
                )
            )
            ->add(
                'endDate',
                TextType::class,
                $this->getConfiguration(
                    "Date dd départ",
                    "La date à laquelle vous quittez"
                )
            )
            ->add(
                'comment',
                TextareaType::class,
                $this->getConfiguration(false, "Votre commentaire", ["required" => false])
            )
        ;

        $builder->get('startDate')
            ->addModelTransformer($this->transformer);
        $builder->get('endDate')
            ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
            'validation_groups' => [
                "Default",
                "front"
            ]
        ]);
    }
}
