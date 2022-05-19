<?php

namespace App\Security\Voter;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CategoryVoter extends Voter {

    public const EDIT = 'CAN_EDIT';
    public const VIEW = 'CAN_VIEW';

    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {

        $this -> categoryRepository = $categoryRepository;

    }

    protected function supports(string $attribute, $subject): bool {

        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof Category;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {

        $user = $token -> getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::EDIT => true,
            default => false,
        };

    }
}
