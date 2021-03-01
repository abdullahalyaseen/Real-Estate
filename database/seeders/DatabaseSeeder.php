<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Customer;
use App\Models\Flat;
use App\Models\Marker;
use App\Models\Photo;
use App\Models\Project;
use App\Models\Repres;
use App\Models\Target;
use App\Models\Trip;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(6)->create();
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
