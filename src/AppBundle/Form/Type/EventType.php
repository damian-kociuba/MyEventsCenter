<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EventType extends AbstractType {
    public function getName() {
        return 'eventForm';
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('name', 'text')
                ->add('address', 'text')
                ->add('description', 'text')
                ->add('maxMembersNumber', 'number')
                ->add('isPublic', 'choice', array(
                    'choices' => array('1' => 'Yes', '0' => 'No'),
                ))
                ->add('startDate', 'date')
                ->add('endDate', 'date')
                ->add('endRegistrationDate', 'date')
                ->add('latitude', 'hidden')
                ->add('longitude', 'hidden')
                ->add('save', 'submit');
    }
}
