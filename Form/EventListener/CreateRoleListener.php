<?php

/**
 * Description of RoleListener
 *
 * @author David Quintanel <david.quintanel@canaltp.fr>
 */

namespace CanalTP\SamEcoreSecurityBundle\Form\EventListener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\NotBlank;
use CanalTP\SamCoreBundle\Entity\UserApplicationRole;
use CanalTP\SamEcoreSecurityBundle\Form\Type\Role\CopyRoleByApplicationType;

class CreateRoleListener implements EventSubscriberInterface
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData'
        );
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $applications = $this->em->getRepository('CanalTPSamCoreBundle:Application')->findAllOrderedByName();
        $data->rolesByApplication = $applications;

        $form->add(
            'applications',
            ChoiceType::class,
            array(
                'label'       => 'role.field.application',
                'multiple'    => true,
                'expanded'    => true,
                'required'    => false,
                'constraints' => array(new NotBlank()),
                'choices' => $applications,
                'choice_name' => function ($application, $key) {
                    return $application->getId();
                },
                'choice_value' => function ($application) {
                    return $application->getId();
                },
                'choice_label' => function ($application) {
                    return $application->getName();
                },
            )
        );

        $form->add(
            'rolesByApplication',
            CollectionType::class,
            array(
                'label'        => 'role.field.parent.label',
                'entry_type'         => CopyRoleByApplicationType::class,
                'allow_add'    => false,
                'allow_delete' => false,
                'by_reference' => false,
                'entry_options'      => array(
                    'required'       => true,
                    'error_bubbling' => false,
                    'attr'           => array('class' => 'application-role-box')
                ),
            )
        );
    }
}
