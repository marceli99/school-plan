<?php

declare(strict_types=1);

use App\Orchid\Screens\GradeCreateScreen;
use App\Orchid\Screens\GradeEditScreen;
use App\Orchid\Screens\GradeListScreen;
use App\Orchid\Screens\GradeScreen;
use App\Orchid\Screens\GroupEditScreen;
use App\Orchid\Screens\GroupListScreen;
use App\Orchid\Screens\GroupScreen;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn(Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push(__('User'), route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users > User
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn(Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Role'), route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// GROUPS
// Platform > System > Groups
Route::screen('groups/{group}/edit', GroupEditScreen::class)
    ->name('platform.systems.groups.edit')
    ->breadcrumbs(fn(Trail $trail, $group) => $trail
        ->parent('platform.systems.groups')
        ->push(__('Group'), route('platform.systems.groups.edit', $group)));

// Platform > System > Groups > Create
Route::screen('groups/create', GroupEditScreen::class)
    ->name('platform.systems.groups.create')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.groups')
        ->push(__('Create'), route('platform.systems.groups.create')));

// Platform > System > Groups > Group
Route::screen('groups/{group}', GroupScreen::class)
    ->name('platform.systems.groups.view')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.systems.groups'));

// Platform > System > Groups
Route::screen('groups', GroupListScreen::class)
    ->name('platform.systems.groups')
    ->breadcrumbs(fn(Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Groups'), route('platform.systems.groups')));

// GRADES
// Platform > System > Grades
Route::screen('grades/edit/{grade}', GradeEditScreen::class)
    ->name('platform.systems.grades.edit');

// Platform > System > Grades > Create
Route::screen('grades/create/{group}/{user}', GradeCreateScreen::class)
    ->name('platform.systems.grades.create');

// Platform > System > Grades > Grade
Route::screen('grades/{grade}', GradeScreen::class)
    ->name('platform.systems.grades.view');

// Platform > System > Grades
Route::screen('grades', GradeListScreen::class)
    ->name('platform.systems.grades');
