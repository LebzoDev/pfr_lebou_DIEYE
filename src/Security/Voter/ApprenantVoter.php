<?php

namespace App\Security\Voter;

use App\Entity\CM;
use App\Entity\Apprenant;
use App\Entity\Formateur;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class ApprenantVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['APP_EDIT','APP_VIEW','APP_POST','APP_DELETE'])
            && $subject instanceof Apprenant;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!($user instanceof Formateur) AND !($user instanceof Apprenant) AND !($user instanceof CM)) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'APP_EDIT':
               if ($user instanceof Formateur || $user===$subject) {
                  return true;
               }
                break;
            case 'APP_VIEW':
                if ($user instanceof Formateur || $user===$subject || $user instanceof CM ) {
                    return true;
                 }
                break;
            case 'APP_POST':
                if ($user instanceof Formateur) {
                    return true;
                 }
                break;
            case 'APP_DELETE':
                if ($user instanceof Formateur) {
                    return true;
                 }
                break;
        }

        return false;
    }
}
