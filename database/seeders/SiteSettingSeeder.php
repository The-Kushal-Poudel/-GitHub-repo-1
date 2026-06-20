<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site' => [
                'logoInitial' => 'K.',
                'logoName' => 'Kushal',
                'logoHighlight' => 'Poudel',
                'footerCopyright' => 'Copyright ' . date('Y') . ' Kushal Poudel. All rights reserved.',
                'footerCredit' => 'Built with React, Laravel API, Tailwind CSS and Motion animations',
            ],
            'navItems' => [
                ['id' => 'home', 'label' => 'Home', 'href' => '#home'],
                ['id' => 'about', 'label' => 'About', 'href' => '#about'],
                ['id' => 'skills', 'label' => 'Skills', 'href' => '#skills'],
                ['id' => 'projects', 'label' => 'Projects', 'href' => '#projects'],
                ['id' => 'blogs', 'label' => 'Blogs', 'href' => '#blogs'],
                ['id' => 'experience', 'label' => 'Experience', 'href' => '#experience'],
                ['id' => 'contact', 'label' => 'Contact', 'href' => '#contact'],
            ],
            'hero' => [
                'title' => 'I build clean, fast and meaningful digital experiences.',
                'description' => '',
                'primaryButton' => 'Download CV',
                'secondaryButton' => 'View Projects',
                'secondaryLink' => '#projects',
                'chips' => [
                    ['id' => 'laravel', 'label' => 'Laravel', 'className' => 'left-2 top-8 z-20', 'delay' => 0.7],
                    ['id' => 'react', 'label' => 'React', 'className' => 'right-4 top-24 z-20 hidden sm:block', 'delay' => 1.1],
                    ['id' => 'java', 'label' => 'Java', 'className' => 'bottom-36 right-12 z-20 hidden sm:block', 'delay' => 1.45],
                ],
            ],
            'about' => [
                'label' => 'About Me',
                'title' => 'Turning ideas into functional and beautiful web applications.',
                'description' => '',
                'signature' => 'Kushal',
                'cards' => [
                    [
                        'id' => 'clean-code',
                        'title' => 'Clean Code',
                        'icon' => 'Code',
                        'text' => 'Writing maintainable and scalable code with simple structure and best practices.',
                    ],
                    [
                        'id' => 'strong-backend',
                        'title' => 'Strong Backend',
                        'icon' => 'Database',
                        'text' => 'Building robust APIs, dashboards, and backend systems using Laravel, PHP, and Java.',
                    ],
                    [
                        'id' => 'responsive-ui',
                        'title' => 'Responsive UI',
                        'icon' => 'Monitor',
                        'text' => 'Creating clean, modern, and mobile-friendly interfaces using React and Tailwind CSS.',
                    ],
                    [
                        'id' => 'always-learning',
                        'title' => 'Always Learning',
                        'icon' => 'Rocket',
                        'text' => 'Exploring new technologies and improving through practical real-world projects.',
                    ],
                ],
            ],
            'techStack' => [
                'label' => 'Technologies I Work With',
            ],
            'projectsSection' => [
                'label' => 'Featured Projects',
                'title' => 'Some things I have built.',
                'ctaText' => 'Contact Me',
                'ctaLink' => '#contact',
            ],
            'blogsSection' => [
                'label' => 'Latest Blogs',
                'title' => 'Thoughts, learning and development notes.',
                'description' => 'I write about Laravel, React, backend development, project building, and my developer journey.',
                'ctaText' => 'Suggest a Topic',
                'ctaLink' => '#contact',
            ],
            'journeySection' => [
                'label' => 'My Journey',
                'title' => 'Education and Experience',
            ],
            'contact' => [
                'label' => 'Let\'s Connect',
                'title' => 'Have a project in mind? Let\'s build something amazing together.',
                'image' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?auto=format&fit=crop&w=900&q=80',
                'imageAlt' => 'Clean developer workspace with laptop',
                'namePlaceholder' => 'Your Name',
                'emailPlaceholder' => 'Email Address',
                'messagePlaceholder' => 'Your Message',
                'buttonText' => 'Send Message',
                'submittingText' => 'Sending Message...',
                'emailSubjectPrefix' => 'Portfolio inquiry from',
            ],
            'seo' => [
                'title' => 'Kushal Poudel | Full-stack Developer Portfolio',
                'description' => 'Dynamic developer portfolio powered by React, Laravel API, MySQL, Tailwind CSS and Motion animations.',
                'ogImage' => '/images/og-image.jpg',
            ],
            'theme' => [
                'primary' => '#151412',
                'accent' => '#a78d67',
                'background' => '#f8f3eb',
            ],
        ];

        foreach ($settings as $key => $value) {
            SiteSetting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
