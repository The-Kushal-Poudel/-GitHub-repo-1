<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Experience::query()->exists()) {
            return;
        }

        Experience::create([
            'title' => 'Backend Java Developer Intern',
            'company' => 'F1Soft International Pvt. Ltd.',
            'year_label' => 'January 7, 2024 - April 4, 2024',
            'icon' => 'Briefcase',
            'description' => 'Worked as an intern and learned Java, PostgreSQL, use case flow, and backend development practices.',
            'sort_order' => 1,
        ]);

        Experience::create([
            'title' => 'Bachelor of Computer Application',
            'company' => 'Patan Multiple Campus',
            'year_label' => '2020 - 2025',
            'icon' => 'GraduationCap',
            'description' => 'Completed a bachelor\'s degree focused on programming, database systems, web development, and software engineering.',
            'sort_order' => 2,
        ]);

        Experience::create([
            'title' => 'Laravel and Web Development Projects',
            'company' => 'Softsaron Pvt. Ltd. and Freelance',
            'year_label' => 'Present',
            'icon' => 'Laptop',
            'description' => 'Built Laravel based dynamic websites with admin panels, responsive UI, database integration, and Git workflow.',
            'sort_order' => 3,
        ]);
    }
}
