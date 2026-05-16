<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\News;
use App\Models\Feedback;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@civiclens.in',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Create Sample Users
        $users = [];
        $sampleUsers = [
            ['name' => 'Aarav Sharma',   'email' => 'aarav@example.com'],
            ['name' => 'Priya Patel',    'email' => 'priya@example.com'],
            ['name' => 'Rahul Kumar',    'email' => 'rahul@example.com'],
            ['name' => 'Sneha Reddy',    'email' => 'sneha@example.com'],
            ['name' => 'Vikram Singh',   'email' => 'vikram@example.com'],
        ];

        foreach ($sampleUsers as $u) {
            $users[] = User::create([
                'name'     => $u['name'],
                'email'    => $u['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]);
        }

        // Create Sample News Articles (Indian Government News)
        $newsArticles = [
            [
                'title'    => 'Union Budget 2025: Key Highlights on Infrastructure and Education',
                'content'  => 'The Union Budget for 2025-26 has been presented in Parliament with major allocations towards infrastructure development, education reforms, and healthcare modernization. Finance Minister outlined a comprehensive plan to boost capital expenditure by 25%, with special focus on building highways, railways, and smart cities across India. The education sector received a significant boost with the allocation of Rs 1.2 lakh crore, aimed at implementing the National Education Policy effectively.',
                'category' => 'Economy',
                'language' => 'English',
                'source'   => 'The Hindu',
                'image'    => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=600',
            ],
            [
                'title'    => 'Digital India Initiative Expands Rural Broadband Coverage to 500,000 Villages',
                'content'  => 'The Government of India has announced a significant expansion of the Digital India initiative, targeting broadband connectivity to over 500,000 villages by the end of 2026. The BharatNet project, which aims to bridge the digital divide between urban and rural India, has completed Phase III fiber optic cable laying in 12 states. The initiative will provide high-speed internet access to gram panchayats, enabling e-governance services, telemedicine facilities, and digital education platforms in remote areas.',
                'category' => 'Technology',
                'language' => 'English',
                'source'   => 'NDTV',
                'image'    => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600',
            ],
            [
                'title'    => 'Ayushman Bharat Scheme Crosses 50 Crore Beneficiaries Milestone',
                'content'  => 'The Ayushman Bharat Pradhan Mantri Jan Arogya Yojana (PM-JAY) has crossed a significant milestone of providing health coverage to 50 crore beneficiaries across India. The scheme, which provides free health insurance of Rs 5 lakh per family per year, has been instrumental in reducing out-of-pocket health expenditure for vulnerable families. Over 4 crore hospital treatments have been authorized under the scheme since its launch.',
                'category' => 'Health',
                'language' => 'English',
                'source'   => 'India Today',
                'image'    => 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=600',
            ],
            [
                'title'    => 'New Education Policy: 10,000 Atal Tinkering Labs Inaugurated Across India',
                'content'  => 'Under the New Education Policy 2020, the government has inaugurated 10,000 Atal Tinkering Labs in schools across India to foster innovation and scientific temper among students. These labs provide students access to modern equipment including 3D printers, robotics kits, and IoT devices. The initiative is part of Atal Innovation Mission under NITI Aayog.',
                'category' => 'Education',
                'language' => 'English',
                'source'   => 'Times of India',
                'image'    => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600',
            ],
            [
                'title'    => 'PM Gati Shakti: Multi-modal Connectivity Projects Worth Rs 5 Lakh Crore Approved',
                'content'  => 'The PM Gati Shakti National Master Plan has approved multi-modal connectivity projects worth Rs 5 lakh crore, including expressways, dedicated freight corridors, and port expansion projects. The integrated infrastructure plan aims to reduce logistics costs from 14% to 8% of GDP. Key projects include the Delhi-Mumbai Expressway, Sagarmala port development, and UDAN regional connectivity schemes.',
                'category' => 'Infrastructure',
                'language' => 'English',
                'source'   => 'Economic Times',
                'image'    => 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=600',
            ],
            [
                'title'    => 'Chandrayaan-4 Mission Gets Cabinet Approval with Rs 2,104 Crore Budget',
                'content'  => 'The Union Cabinet has approved the Chandrayaan-4 lunar mission with a budget of Rs 2,104 crore. ISRO plans to launch the mission by 2028, which will involve collecting lunar samples and returning them to Earth. This will make India the fourth nation to achieve a lunar sample return after the USA, Russia, and China. The mission builds on the success of Chandrayaan-3.',
                'category' => 'Technology',
                'language' => 'English',
                'source'   => 'Indian Express',
                'image'    => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=600',
            ],
            [
                'title'    => 'India Achieves Record 500 GW Renewable Energy Capacity Target Ahead of Schedule',
                'content'  => 'India has achieved its ambitious target of 500 GW renewable energy capacity ahead of the 2030 deadline. Solar energy contributes 280 GW while wind energy adds 150 GW to the total. The achievement positions India as a global leader in clean energy transition. The government has now set a revised target of 700 GW by 2032.',
                'category' => 'Environment',
                'language' => 'English',
                'source'   => 'Hindustan Times',
                'image'    => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=600',
            ],
            [
                'title'    => 'One Nation One Ration Card Scheme Enables 80 Crore Portable Transactions',
                'content'  => 'The One Nation One Ration Card scheme has facilitated over 80 crore portable transactions across India, enabling migrant workers and their families to access subsidized food grains from any fair price shop in the country. The scheme integrates with Aadhaar-based biometric authentication to prevent duplication and ensure targeted delivery of food security benefits.',
                'category' => 'Politics',
                'language' => 'English',
                'source'   => 'NDTV',
                'image'    => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=600',
            ],
        ];

        $newsModels = [];
        foreach ($newsArticles as $article) {
            $newsModels[] = News::create($article);
        }

        // Create Sample Feedback
        $sentiments = ['Positive', 'Neutral', 'Negative'];
        $biasLevels = ['Low', 'Medium', 'High'];
        $comments = [
            'Well-researched article with balanced viewpoints. The data presented is credible.',
            'This article provides comprehensive coverage of the topic. Good journalism.',
            'The article seems to have a slight political leaning but overall informative.',
            'Decent coverage but lacks depth on the implementation challenges.',
            'Excellent reporting with ground-level insights. Very trustworthy source.',
            'The article could benefit from more diverse perspectives and expert opinions.',
            'Good factual reporting but the headline seems slightly sensationalized.',
            'Balanced and fair coverage of a complex policy issue. Recommended read.',
        ];

        foreach ($newsModels as $news) {
            $numFeedback = rand(2, 4);
            $shuffledUsers = collect($users)->shuffle()->take($numFeedback);

            foreach ($shuffledUsers as $i => $user) {
                Feedback::create([
                    'user_id'       => $user->id,
                    'news_id'       => $news->id,
                    'trust_score'   => rand(2, 5),
                    'clarity_score' => rand(2, 5),
                    'bias_level'    => $biasLevels[array_rand($biasLevels)],
                    'sentiment'     => $sentiments[array_rand($sentiments)],
                    'comment'       => $comments[array_rand($comments)],
                ]);
            }
        }
    }
}
