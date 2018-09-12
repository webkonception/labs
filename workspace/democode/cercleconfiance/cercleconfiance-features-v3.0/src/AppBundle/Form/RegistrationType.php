<?php
// src/AppBundle/Form/RegistrationType.php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('relation', TextType::class, ['label' => 'Lien principal avec la personne qui est au centre du cercle', 'required' => false])
            /*->add('relation', ChoiceType::class, [
                'label' => 'Lien principal avec la personne qui est au centre du cercle',
                'required' => false,
                'choices' => [
                    'Famille' => [
                        'époux' => 'epoux',
                        'épouse' => 'épouse',
                        'conjoint(e)' => 'conjoint',
                        'grand-père' => 'grand_pere',
                        'grand-mère' => 'grand_mere',
                        'père' => 'pere',
                        'mère' => 'mère',
                        'oncle' => 'oncle',
                        'tante' => 'tante',
                        'frère' => 'frere',
                        'soeur' => 'soeur',
                        'enfant' => 'enfant',
                        'petit-enfant' => 'petit_enfant',
                    ],
                    'Externe' => [
                        'assistant(e) social(e)' => 'assistant_social',
                        'tuteur' => 'tuteur',
                        'ami(e)' => 'ami',
                        'voisin' => 'voisin',
                    ],
                    'Intervenant médical' => [
                        'médecin' => 'medecin',
                        'infirmier(e)' => 'infirmier',
                        'aidant' => 'aidant',
                        'aide soignant' => 'aide_soignant',
                        'personnel de santé' => 'personnel_de_santé',
                    ],
                    'Autre' => 'autre'
                ],
            ])*/
            ->add('firstname', TextType::class, ['label'=>'Prénom'])
            ->add('name', TextType::class, ['label'=>'Nom'])
            ->add('address', AddressType::class, ['label'=>false, 'required' => false])
            ->add('phone_number', TextType::class, ['label'=>'Numéro de téléphone', 'required' => false])
            ->add('avatar', FileType::class, ['label' => 'Photo', 'data_class' => null, 'required' => false]);
    }

    public function getParent()
    {
        return 'UserBundle\Form\Type\RegistrationFormType';

    }

    public function getBlockPrefix()
    {
        return 'user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}