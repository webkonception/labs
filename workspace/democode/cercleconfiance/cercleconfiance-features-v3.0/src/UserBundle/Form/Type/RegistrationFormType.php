<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Form\Type;

use AppBundle\Form\AddressType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use UserBundle\Entity\User;


class RegistrationFormType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class = User::class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\EmailType'),
                array('label' => 'Email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\HiddenType')
//                , array('label' => 'Nom d\'utilisateur', 'translation_domain' => 'FOSUserBundle')
                , ['label' => ' ', 'translation_domain' => 'FOSUserBundle','required' => false]
            )
            ->add('plainPassword', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\RepeatedType'),
                array(
                'type' => LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\PasswordType'),
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'Mot de passe'),
                'second_options' => array('label' => 'Confirmation du mot de passe'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('firstname', RegistrationType::class, ['label' => 'Prénom'])
            ->add('name', RegistrationType::class, ['label' => 'Nom'])
            ->add('address', AddressType::class, ['label'=>false, 'required' => false])
            ->add('phone_number', TextType::class, ['label'=>'Numéro de téléphone', 'required' => false])
            ->add('avatar', FileType::class, ['label' => 'Photo', 'data_class' => null, 'required' => false]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'csrf_token_id' => 'registration',
            // BC for SF < 2.8
            'intention' => 'registration',
        ));
    }

    // BC for SF < 3.0

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fos_user_registration';
    }
}
