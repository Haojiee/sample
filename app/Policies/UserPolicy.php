<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 用户访问和修改资料授权策略
     *
     * @param User $currentUser 当前登录用户
     * @param User $user        访问的用户
     * @return bool
     */
    public function update(User $currentUser, User $user) {
        return $currentUser->id === $user->id;
    }

    public function destroy(User $currentUser, User $user) {
        return $currentUser->is_admin && $currentUser->id !== $user->id;
    } 

}
