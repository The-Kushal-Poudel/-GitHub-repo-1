<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Faq::query()->exists()) {
            return;
        }

        $faqs = [
            [
                'question' => 'Are you available for freelance or full-time work?',
                'answer' => 'Yes! I am currently open to both freelance projects and full-time opportunities. Feel free to reach out through the contact section and let\'s discuss your project or job opening.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'What technologies do you specialize in?',
                'answer' => 'I primarily work with Laravel, PHP, MySQL, and Tailwind CSS for backend and full-stack web development. I also have experience with React for frontend interfaces and Java with Spring for backend systems.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How long does a typical project take?',
                'answer' => 'It depends on the project scope and complexity. A simple website can take 1-2 weeks, while a full-featured web application with admin panel, authentication, and complex features may take 4-8 weeks or more.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'What is your development process?',
                'answer' => 'I start by understanding the requirements and goals. Then I plan the architecture, build the backend logic, create the frontend interface, test thoroughly, and finally deploy. I maintain clear communication throughout the process.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'Can you work with an existing team or codebase?',
                'answer' => 'Absolutely. I am comfortable collaborating with teams using Git workflows, code reviews, and agile practices. I can also jump into existing codebases, understand the architecture, and contribute effectively.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Do you provide support after project delivery?',
                'answer' => 'Yes, I offer post-delivery support including bug fixes, minor updates, and maintenance. For ongoing support, we can discuss a maintenance plan that fits your needs and budget.',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }
    }
}
