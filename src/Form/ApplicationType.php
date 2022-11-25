<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType
{
    /**
     * Retourner la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array  $options
     *
     * @return array
     */
    protected function getConfiguration(string $label, string $placeholder, array $options = []): array
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }
}
