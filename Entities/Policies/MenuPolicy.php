<?php

namespace Modules\Menu\Entities\Policies;

use Pingu\Core\Support\Policy;
use Pingu\Menu\Entities\Menu;
use Pingu\User\Entities\User;

class MenuPolicy extends Policy
{
    protected function userOrGuest(?User $user)
    {
        return $user ? $user : \Permissions::guestRole();
    }

    public function index(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('view '.Menu::friendlyNames());
    }

    public function view(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('view '.$entity::friendlyNames());
    }

    public function edit(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit '.$entity::friendlyNames());
    }

    public function delete(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('delete '.$entity::friendlyNames());
    }

    public function create(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit '.Menu::friendlyNames());
    }
}