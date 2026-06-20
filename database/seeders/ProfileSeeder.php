<?php

namespace Database\Seeders;

use App\Models\Profile;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Profile::query()->exists()) {
            return;
        }

        Profile::create([
            'name' => 'Kushal Poudel',
            'role' => 'Backend Java Developer | Full-stack Laravel Developer',
            'location' => 'Sinamangal, Kathmandu',
            'email' => 'kushalpoudel240@gmail.com',
            'phone' => '9863614263 / 9824055306',
            'github_url' => 'https://github.com/The-Kushal-Poudel',
            'linkedin_url' => 'https://www.linkedin.com/in/kushal-poudel-317b25241/',
            'availability' => 'Available for work',
            'bio' => 'I am a BCA graduate and backend-focused developer from Kathmandu. ' .
                     'I enjoy building clean, dynamic, and useful web applications with ' .
                     'Laravel, PHP, SQL, React, and Tailwind CSS.',
            'image_url' => '/images/pic3.png',
            'cv_url' => '/Kushal_Poudel_CV.pdf',
        ]);
    }
}
