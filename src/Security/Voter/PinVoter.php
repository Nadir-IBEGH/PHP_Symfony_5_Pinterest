<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PinVoter extends Voter
{
    protected function supports(string $attribute, $pin): bool
    {
        return in_array($attribute, ['PIN_EDIT', 'PIN_DELETE'])
            && $pin instanceof \App\Entity\Pin;
    }

    protected function voteOnAttribute(string $attribute, $pin, TokenInterface $token): bool
    {

        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'PIN_DELETE':
            case 'PIN_EDIT':
                return $user->isVerified() && $user == $pin->getUser();
        }

        return false;
    }
}
