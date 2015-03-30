<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // custom fields
        $builder->add('gender', 'choice', array(
            'choices'   => array('1' => 'Male', '0' => 'Female'),
        ));
        $builder->add('birthDate', 'birthday');
    }

    public function getName()
    {
        return 'appbundle_user_registration';
    }
}