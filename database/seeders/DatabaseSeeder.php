<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;
use Orchid\Platform\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        $adminRole = new Role([
            'slug' => 'admin',
            'name' => 'Administrator',
            'permissions' => [
                'platform.systems.roles' => true,
                'platform.systems.users' => true,
                'platform.systems.attachment' => true,
                'groups' => true,
                'grades' => true,
                'platform.index' => true
            ],
        ]);

        $teacherRole = new Role([
            'slug' => 'nauczyciel',
            'name' => 'Nauczyciel',
            'permissions' => [
                'platform.systems.roles' => false,
                'platform.systems.users' => false,
                'platform.systems.attachment' => true,
                'groups' => false,
                'grades' => true,
                'platform.index' => true
            ],
        ]);

        $studentRole = new Role([
            'slug' => 'uczen',
            'name' => 'Uczeń',
            'permissions' => [
                'platform.systems.roles' => false,
                'platform.systems.users' => false,
                'platform.systems.attachment' => false,
                'groups' => false,
                'grades' => false,
                'platform.index' => true
            ],
        ]);

        $adminRole->save();
        $teacherRole->save();
        $studentRole->save();

        $adminUser = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminUser->addRole($adminRole);

        $teacherUser = User::factory()->create([
            'name' => 'Nauczyciel Robert',
            'email' => 'robert@example.com',
            'password' => bcrypt('password'),
        ]);
        $teacherUser->addRole($teacherRole);

        $studentUser1 = User::factory()->create([
            'name' => 'Uczeń Krzyś',
            'email' => 'krzys@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentUser1->addRole($studentRole);

        $studentUser2 = User::factory()->create([
            'name' => 'Uczeń Tomek',
            'email' => 'tomek@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentUser2->addRole($studentRole);

        $studentUser3 = User::factory()->create([
            'name' => 'Uczeń Ania',
            'email' => 'ania@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentUser3->addRole($studentRole);

        $studentUser4 = User::factory()->create([
            'name' => 'Uczeń Marcel',
            'email' => 'marcel@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentUser4->addRole($studentRole);

        $studentUser5 = User::factory()->create([
            'name' => 'Uczeń Jakub',
            'email' => 'Jakub@example.com',
            'password' => bcrypt('password'),
        ]);
        $studentUser5->addRole($studentRole);

        Group::factory()->create([
            'name' => 'Matematyka 1A/2023',
            'teacher_id' => 2,
        ]);
    }
}
