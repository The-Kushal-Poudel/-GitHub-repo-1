<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Skill::query()->exists()) {
            return;
        }

        $skills = [
            'Laravel',
            'PHP',
            'MySQL',
            'React',
            'Tailwind CSS',
            'Git',
            'Java',
            'Spring',
            'PostgreSQL'
        ];

        foreach ($skills as $index => $label) {
            Skill::create([
                'label' => $label,
                'icon' => null,
                'sort_order' => $index + 1,
            ]);
        }
    }
}
