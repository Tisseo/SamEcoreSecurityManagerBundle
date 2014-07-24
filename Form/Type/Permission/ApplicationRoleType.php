<?php

namespace CanalTP\SamEcoreSecurityBundle\Form\Type\Permission;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ApplicationRoleType extends AbstractType
{
     /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('roles', 'collection',
            array(
                'type' => 'sam_business_right',
                'options' => array('application' => $builder->getData()),
            )
        );
        
        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CanalTP\SamCoreBundle\Entity\Application',
            'attr' => array('novalidate' => 'novalidate')
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sam_application_right';
    }
}
