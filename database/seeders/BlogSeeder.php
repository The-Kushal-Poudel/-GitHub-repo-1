<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Blog::query()->exists()) {
            return;
        }

        Blog::create([
            'title' => 'How I Built My Portfolio Website',
            'category' => 'React',
            'description' => 'A simple explanation of how I created my developer portfolio using React, Tailwind CSS, and Framer Motion.',
            'content' => '# How I Built My Portfolio Website' . "\n\n" .
                         'Building a portfolio website is an exciting rite of passage for any web developer. ' .
                         'It is not just a resume, but a living demonstration of your technical abilities, design sense, and attention to detail. ' .
                         'In this article, I will walk you through how I built my portfolio using React, Tailwind CSS, and Framer Motion.',
            'link' => null,
            'published_at' => Carbon::create(2026, 5, 1, 0, 0, 0),
            'is_published' => false, // seeded drafts per instructions
            'sort_order' => 1,
        ]);

        Blog::create([
            'title' => 'My Journey as a Backend Java Developer',
            'category' => 'Career',
            'description' => 'My learning journey, internship experience, and how I am growing as a backend developer.',
            'content' => '# My Journey as a Backend Java Developer' . "\n\n" .
                         'Java is one of the most popular and powerful backend programming languages in the world. ' .
                         'In this article, I share my personal journey as a Bachelor of Computer Application (BCA) student ' .
                         'focused on backend architecture, detailing my learning curve with Core Java, Spring Boot, PostgreSQL, and my internship experience at FoneNxt.',
            'link' => null,
            'published_at' => Carbon::create(2026, 5, 2, 0, 0, 0),
            'is_published' => false,
            'sort_order' => 2,
        ]);

        Blog::create([
            'title' => 'Laravel CRUD Project Explained',
            'category' => 'Laravel',
            'description' => 'A beginner-friendly explanation of CRUD, routing, controllers, models, migrations, and database flow in Laravel.',
            'content' => '# Laravel CRUD Project Explained' . "\n\n" .
                         'Laravel is the go-to PHP framework for developers who want clean, expressive, and robust applications. ' .
                         'If you are starting out with Laravel, understanding the flow of CRUD (Create, Read, Update, Delete) is essential. ' .
                         'This guide explains how models, migrations, controllers, and routing fit together to build a robust API or admin dashboard.',
            'link' => null,
            'published_at' => Carbon::create(2026, 5, 3, 0, 0, 0),
            'is_published' => false,
            'sort_order' => 3,
        ]);
    }
}
