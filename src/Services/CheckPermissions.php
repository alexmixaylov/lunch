<?php


namespace App\Services;


use App\Entity\User;

/**
 * Class CheckPermissions
 * @package App\Services
 */
class CheckPermissions
{
    /**
     * Check is User has ROLES_ADMIN
     * @param User $user
     *
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        $roles = $user->getRoles();
        return in_array ( "ROLE_ADMIN" , $roles);
    }
}
