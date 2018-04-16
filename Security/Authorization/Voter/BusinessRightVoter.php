<?php

namespace CanalTP\SamEcoreSecurityBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class BusinessRightVoter implements VoterInterface
{
    private $prefix;
    private $om;
    private $appFinder;

    public function __construct(ObjectManager $om, $appFinder)
    {
        $this->prefix = 'BUSINESS_';
        $this->om = $om;
        $this->appFinder = $appFinder;
    }

    public function supportsAttribute($attribute)
    {
        return 0 === strpos($attribute, $this->prefix);
    }

    public function supportsClass($class)
    {
        return true;
    }

    private function checkPermission($attribute, $permissions)
    {
        $result = false;
        if ($permissions == NULL) {
            return $result;
            //throw new \Exception('One of your roles have no permissions');
        }

        foreach ($permissions as $permission) {
            if ($attribute === $permission) {
                $result = true;
                break;
            }
        }
        return $result;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        $result = VoterInterface::ACCESS_ABSTAIN;

        if ($token->getUser() == 'anon.') {
            return ($result);
        }
        if (in_array('ROLE_API', $token->getUser()->getRoles())) {
            return VoterInterface::ACCESS_GRANTED;
        }
        if ($token->getUser()->isSuperAdmin()) {
            return VoterInterface::ACCESS_GRANTED;
        }

        $roles = $this->extractRoles($token);
        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $result = VoterInterface::ACCESS_DENIED;
            foreach ($roles as $role) {
                if ($this->checkPermission($role->getPermissions(), $attribute)) {
                    return VoterInterface::ACCESS_GRANTED;
                }
            }
        }

        return $result;
    }

    protected function extractRoles(TokenInterface $token)
    {
        return $this->om
            ->getRepository('CanalTPSamCoreBundle:Role')->findRolesByUserAndApplication(
                $token->getUser()->getId(),
                $this->appFinder->findFromUrl()->getCanonicalName()
            );
    }
}
