<?php

namespace CanalTP\SamEcoreSecurityBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\RoleVoter;

class ApplicationVoter extends RoleVoter
{
    /**
     * Constructor.
     *
     * @param string $prefix The role prefix
     */
    public function __construct($prefix = 'APP_')
    {
        parent::__construct($prefix);
    }
}