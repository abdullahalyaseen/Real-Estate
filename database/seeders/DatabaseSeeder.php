<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Customer;
use App\Models\Department;
use App\Models\Flat;
use App\Models\Marker;
use App\Models\Permission;
use App\Models\Photo;
use App\Models\Project;
use App\Models\Repres;
use App\Models\Target;
use App\Models\Trip;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            'first_name' => 'Abdullah',
            'last_name' => 'Al yaseen',
            'email' => 'abdullahalheem@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'number' => '05522436988',
            'remember_token' => Str::random(10),
            'is_active' => true,
        ];
        User::create($user);

        $perms = [
            'view_users',
            'add_user',
            'edit_user',
            'delete_user',
            'view_customer',
            'add_customer',
            'edit_customer',
            'delete_customer',
            'view_projects',
            'add_project',
            'edit_project',
            'delete_project',
        ];

        foreach ($perms as $perm){
            $temp = [
              'title'=>strval($perm)
            ];
            Permission::create($temp);
        }

        $department = [
            'title' => 'management'
        ];
        Department::create($department);



//        User::factory(6)->create();
        Customer::factory(100)->create();
        Flat::factory(200)->create();
        Marker::factory(50)->create();
        Project::factory(50)->create();
        Repres::factory(50)->create();
        Target::factory(1000)->create();
        Trip::factory(500)->create();
        Photo::factory(500)->create();
        Video::factory(500)->create();
    }
}
