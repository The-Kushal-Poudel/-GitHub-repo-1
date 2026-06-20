<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Project::query()->exists()) {
            return;
        }

        Project::create([
            'title' => 'News Portal',
            'slug' => 'news-portal',
            'description' => 'User and admin based news portal website with dashboard, category management, and role based access.',
            'role' => 'Backend and admin dashboard development',
            'tech_stack' => ['Laravel', 'SQL', 'Tailwind CSS'],
            'features' => ['Role based admin access', 'Category and news management', 'Responsive public news pages'],
            'github_link' => null,
            'live_link' => null,
            'image_url' => 'https://images.unsplash.com/photo-1495020689067-958852a7765e?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'Newspapers and digital news project preview',
            'status' => null,
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        Project::create([
            'title' => 'Sayumi Travels and Tours',
            'slug' => 'sayumi-travels-and-tours',
            'description' => 'Travel and tours website with dynamic packages, destinations, booking forms, and admin management features.',
            'role' => 'Full-stack Laravel development',
            'tech_stack' => ['Laravel', 'SQL', 'Tailwind CSS', 'Git'],
            'features' => ['Dynamic package management', 'Destination pages', 'Booking inquiry workflow'],
            'github_link' => null,
            'live_link' => 'https://sayumiglobal.com/',
            'image_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'Travel destination landscape for Sayumi Travels and Tours',
            'status' => null,
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        Project::create([
            'title' => 'ConvertTree',
            'slug' => 'converttree',
            'description' => 'All-in-one online tools platform for PDF, image, email, video, and daily utility tools.',
            'role' => 'Backend architecture and tool workflows',
            'tech_stack' => ['Laravel', 'SQL', 'Tailwind CSS'],
            'features' => ['Utility tool modules', 'Clean dashboard structure', 'Launch-ready product pages'],
            'github_link' => null,
            'live_link' => 'https://www.converttree.com/',
            'image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'Laptop workspace representing ConvertTree online tools',
            'status' => 'Launching Soon',
            'sort_order' => 3,
            'is_visible' => true,
        ]);
    }
}
