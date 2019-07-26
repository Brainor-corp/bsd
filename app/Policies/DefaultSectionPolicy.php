<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultSectionPolicy
{
    use HandlesAuthorization;

    /** Если супер-админ, разрешает все действия
     *
     * @param User $user
     * @return bool | null
     */
    public function before(User $user)
    {
        if($user->isSuperAdmin()) {
            return true;
        }

        return null;
    }

    /** Разрешает отображение секции, если есть привелегии
     *
     * @param User $user
     * @param $section
     * @return bool
     */
    public function display(User $user, $section)
    {
        return $this->checkAccess($user, $section, 'show');
    }

    /** Разрешает редактирование, если есть привелегии
     *
     * @param User $user
     * @param $section
     * @return bool
     */
    public function edit(User $user, $section)
    {
        return $this->checkAccess($user, $section, 'edit');
    }

    /** Разрешает удаление, если есть привелегии
     *
     * @param User $user
     * @param $section
     * @return bool
     */
    public function delete(User $user, $section)
    {
        return $this->checkAccess($user, $section, 'delete');
    }

    /** Разрешает создание записи, если есть привелегии
     *
     * @param User $user
     * @param $section
     * @return bool
     */
    public function create(User $user, $section)
    {
        return $this->checkAccess($user, $section, 'create');
    }

    /** Проверка доступа к созданию, редактированию и т.д.
     *
     * @param User $user
     * @param $section
     * @param $ability string
     * @return bool
     */
    public function checkAccess(User $user, $section, $ability)
    {
        return $user->hasRoleWithPermission("$section.$ability");
    }
}
