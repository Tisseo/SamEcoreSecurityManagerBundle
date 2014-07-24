<?php

namespace CanalTP\SamEcoreSecurityBundle\Form\Type\Role;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use CanalTP\SamEcoreSecurityBundle\Form\Type\RoleType;

/**
 * Description of RoleType
 *
 * @author David Quintanel <david.quintanel@canaltp.fr>
 */
class CreateRoleType extends AbstractType
{
    protected $roleListener;

    public function __construct($roleListener)
    {
        $this->roleListener = $roleListener;
    }

     /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('role', 'sam_role');

        $builder->addEventSubscriber($this->roleListener);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CanalTP\SamEcoreSecurityBundle\Form\Model\RegistrationRole'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sam_create_role';
    }
}
