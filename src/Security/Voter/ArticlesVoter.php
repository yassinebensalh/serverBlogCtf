<?php

namespace App\Security\Voter;

use App\Entity\Articles;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
class ArticlesVoter extends Voter
{
    const ARTICLES_EDIT = 'articles_edit';
    const ARTICLES_DELETE = 'articles_delete';
    const ARTICLES_SHOW = 'articles_show';

    private $security;

    public function __construct(Security $security){
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ARTICLES_EDIT, self::ARTICLES_DELETE, self::ARTICLES_SHOW])
            && $subject instanceof \App\Entity\Articles;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // verify if user is admin

        if ($this->security->isGranted('ROLE_ADMIN')){
            return true;
        }

        // we verify if the article has a user
        if ( null === $subject->getUser() ){
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ARTICLES_EDIT :
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canEdit($subject,$user);
                break;
            case self::ARTICLES_DELETE:
                // logic to determine if the user can DELETE
                // return true or false
                return $this->canDelete($subject,$user);
                break;
            case self::ARTICLES_SHOW:
                // logic to determine if the user can SHOW
                // return true or false
                return $this->canShow($subject,$user);
                break;

        }

        return false;
    }
    private function canEdit(Articles $articles, User $user): bool {
        return $user === $articles->getUser();
    }
    private function canDelete(Articles $articles, User $user): bool {
        return $user === $articles->getUser();
    }
    private function canShow(Articles $articles, User $user): bool {
        return $user === $articles->getUser();
    }

}
