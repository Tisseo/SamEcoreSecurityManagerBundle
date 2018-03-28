<?php

namespace CanalTP\SamEcoreSecurityBundle\Form\Type\Permission;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\ChoiceList\ObjectChoiceList;
use Symfony\Component\Form\ChoiceList\ArrayChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CanalTP\SamEcoreApplicationManagerBundle\Component\BusinessComponentRegistry;
use CanalTP\SamEcoreApplicationManagerBundle\Permission\BusinessPermission;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class BusinessRightType extends AbstractType
{
    private $businessComponentRegistry;
    protected $authorization;

    public function __construct(BusinessComponentRegistry $businessComponentRegistry, AuthorizationChecker $authorization)
    {
        $this->businessComponentRegistry = $businessComponentRegistry;
        $this->authorization = $authorization;
    }

     /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $permissions = $this->businessComponentRegistry
            ->getBusinessComponent($options['application']->getCanonicalName())
            ->getPermissionsManager()
            ->getBusinessModules()
            ->getPermissions();
        
        $disabled = !$this->authorization->isGranted('BUSINESS_MANAGE_PERMISSION');

        
        // TODO Change choice_list by choice_loader
        $builder->add(
            'businessPermissions',
            ChoiceType::class,
            array(
                'label'       => 'role.field.application',
                'multiple'    => true,
                'expanded'    => true,
                'required'    => false,
                'disabled'    => $disabled,
                'choices_as_values' => true,
                'choices' => $permissions,
                'choice_name' => function ($permission, $key) {
                    return $key;
                },
                'choice_value' => function ($permission) {
                    return $permission->getId();
                },

            )
        );

        // Change Permissions in PermissionInterface[]
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($permissions) {
                $data = $event->getData();
                $permissionsArray = array();
                foreach ($data->getPermissions() as $key => $permission) {
                    $model = new BusinessPermission();
                    foreach ($permissions as $perm) {
                        if ($perm->getId() === $permission) {
                            $model = $perm;
                            break;
                        }
                    }

                    $permissionsArray[] = $model;
                }
                $data->setBusinessPermissions($permissionsArray);
            }
        );

        $builder->addEventListener(
            FormEvents::SUBMIT,
            function (FormEvent $event) use ($permissions) {
                $data = $event->getData();

                $permissionsArray = array();
                foreach ($data->getBusinessPermissions() as $key => $permission) {
                    $permissionsArray[] = $permission->getId();
                }
                $data->setPermissions($permissionsArray);
            }
        );
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CanalTP\SamCoreBundle\Entity\Role',
        ));
        
        $resolver->setRequired(array('application'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sam_business_right';
    }

    public function getBlockPrefix() {
        return parent::getBlockPrefix(); // TODO: Change the autogenerated stub
    }
}
