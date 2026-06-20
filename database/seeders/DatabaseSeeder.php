<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application\'s database.
     */
    public function run(): void
    {
        $this->call([
            ProfileSeeder::class,
            ProjectSeeder::class,
            BlogSeeder::class,
            ExperienceSeeder::class,
            SkillSeeder::class,
            SiteSettingSeeder::class,
            FaqSeeder::class,
        ]);
    }
}
