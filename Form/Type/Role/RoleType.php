<?php

namespace CanalTP\SamEcoreSecurityBundle\Form\Type\Role;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Description of RoleType
 *
 * @author akambi <contact@akambi-fagbohoun.com>
 */
class RoleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            null,
            array(
                'constraints' => array(
                        new NotBlank(),
                        new Length(array('min' => 3, 'max' => 255))
                )
            )
        );
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CanalTP\SamCoreBundle\Entity\Role'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sam_role';
    }
}
