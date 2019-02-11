<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;

//ApplicationType permet d'alléger le code de nos formulaires registrationType et annonceType
class ApplicationType extends AbstractType {

    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    protected function getConfiguration($label,$placeholder, $options = [] ) {
        return array_merge_recursive([
            'label' => $label,
                'attr' => [
                    'placeholder' => $placeholder
                ]
        ], $options);
    }
}