<?php

namespace Pingu\Menu\Entities\Policies;

use Pingu\Entity\Contracts\BundleContract;
use Pingu\Entity\Support\Entity;
use Pingu\Entity\Support\Policies\BaseEntityPolicy;
use Pingu\Menu\Entities\Menu;
use Pingu\User\Entities\User;

class MenuPolicy extends BaseEntityPolicy
{
    public function index(?User $user)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('view menus');
    }

    public function view(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('view menus');
    }

    public function edit(?User $user, Entity $entity)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('edit menus');
    }

    public function delete(?User $user, Entity $entity)
    {
        if (!$entity->deletable) {
            return false;
        }
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('delete menus');
    }

    public function create(?User $user, ?BundleContract $bundle = null)
    {
        $user = $this->userOrGuest($user);
        return $user->hasPermissionTo('add menus');
    }
}