<?php

namespace App\Security\Voter;

use App\Entity\CM;
use App\Entity\Formateur;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class FormateurVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, [self::VIEW, self::EDIT]) && $subject instanceof Formateur;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!($user instanceof Formateur) && (!$user instanceof CM)) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::VIEW:
                if ($subject === $user || $user instanceof CM){
                    return true;
                }
                break;
            case self::EDIT:
                if ($subject === $user && $user instanceof Formateur){
                    return true;
                }
                break;
        }

        return false;
    }
}
