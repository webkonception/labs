<?php

namespace AppBundle\Form;

use AppBundle\Entity\Offer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CircleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ["label"=>"Nom du Cercle"])
            //->add('number_circle_users', TextType::class, ["label"=>"Nombre de membres", 'empty_data' => 6])
            ->add('number_circle_users', NumberType::class, [
                "label"=>"Nombre de membres",
                'attr' => [
                    'min' => 2,
                    'max' => 12
                ],
                //'data' => 6,
                'empty_data' => 6
            ])
            ->add('offer', EntityType::class,  [
                'class'=>Offer::class,
                'choice_label'=>'name',
                "label"=>"Choisir une Offre"
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Circle'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_circle';
    }


}
