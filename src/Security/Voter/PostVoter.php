<?php

namespace App\Security\Voter;

use App\Entity\Post;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class PostVoter extends Voter
{
    const POST_OWNER_EDIT = 'post_owner_edit';
    const POST_OWNER_DELETE = 'post_owner_delete';
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::POST_OWNER_DELETE, self::POST_OWNER_EDIT])
            && $subject instanceof \App\Entity\Post;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::POST_OWNER_EDIT:
                // logic to determine if the user can EDIT
                // return true or false
//                break;
            case self::POST_OWNER_DELETE:
                // logic to determine if the user can VIEW
                // return true or false
                if ($this->security->isGranted('ROLE_ADMIN')) {
                    return true;
                }
                if ($subject->getAuthor() == $user) {
                    return true;
                }
                break;
        }

        return false;
    }
}
