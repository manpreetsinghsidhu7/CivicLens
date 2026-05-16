<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\News;
use App\Models\Feedback;
use Carbon\Carbon;

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

        // Create 40+ genuine Indian users
        $sampleUsers = [
            ['name' => 'Aarav Sharma',       'email' => 'aarav@example.com'],
            ['name' => 'Priya Patel',        'email' => 'priya@example.com'],
            ['name' => 'Rahul Kumar',        'email' => 'rahul@example.com'],
            ['name' => 'Sneha Reddy',        'email' => 'sneha@example.com'],
            ['name' => 'Vikram Singh',       'email' => 'vikram@example.com'],
            ['name' => 'Ananya Iyer',        'email' => 'ananya@example.com'],
            ['name' => 'Arjun Nair',         'email' => 'arjun@example.com'],
            ['name' => 'Diya Menon',         'email' => 'diya@example.com'],
            ['name' => 'Karthik Rajan',      'email' => 'karthik@example.com'],
            ['name' => 'Meera Krishnan',     'email' => 'meera@example.com'],
            ['name' => 'Rohan Deshmukh',     'email' => 'rohan@example.com'],
            ['name' => 'Ishita Banerjee',    'email' => 'ishita@example.com'],
            ['name' => 'Aditya Joshi',       'email' => 'aditya@example.com'],
            ['name' => 'Kavya Subramaniam',  'email' => 'kavya@example.com'],
            ['name' => 'Siddharth Gupta',    'email' => 'siddharth@example.com'],
            ['name' => 'Nisha Agarwal',      'email' => 'nisha@example.com'],
            ['name' => 'Amit Verma',         'email' => 'amit@example.com'],
            ['name' => 'Pooja Mishra',       'email' => 'pooja@example.com'],
            ['name' => 'Rajesh Pillai',      'email' => 'rajesh@example.com'],
            ['name' => 'Swathi Rao',         'email' => 'swathi@example.com'],
            ['name' => 'Manish Tiwari',      'email' => 'manish@example.com'],
            ['name' => 'Deepika Chauhan',    'email' => 'deepika@example.com'],
            ['name' => 'Suresh Bhat',        'email' => 'suresh@example.com'],
            ['name' => 'Lakshmi Narayanan',  'email' => 'lakshmi@example.com'],
            ['name' => 'Varun Kapoor',       'email' => 'varun@example.com'],
            ['name' => 'Anjali Sinha',       'email' => 'anjali@example.com'],
            ['name' => 'Nikhil Mehta',       'email' => 'nikhil@example.com'],
            ['name' => 'Shreya Kulkarni',    'email' => 'shreya@example.com'],
            ['name' => 'Gaurav Pandey',      'email' => 'gaurav@example.com'],
            ['name' => 'Tanvi Bhatt',        'email' => 'tanvi@example.com'],
            ['name' => 'Harish Venkat',      'email' => 'harish@example.com'],
            ['name' => 'Ritu Saxena',        'email' => 'ritu@example.com'],
            ['name' => 'Pranav Hegde',       'email' => 'pranav@example.com'],
            ['name' => 'Divya Chatterjee',   'email' => 'divya@example.com'],
            ['name' => 'Akash Malhotra',     'email' => 'akash@example.com'],
            ['name' => 'Neha Dwivedi',       'email' => 'neha@example.com'],
            ['name' => 'Vivek Sethi',        'email' => 'vivek@example.com'],
            ['name' => 'Sana Khan',          'email' => 'sana@example.com'],
            ['name' => 'Tushar Jain',        'email' => 'tushar@example.com'],
            ['name' => 'Pallavi Goswami',    'email' => 'pallavi@example.com'],
        ];

        foreach ($sampleUsers as $u) {
            User::create([
                'name'     => $u['name'],
                'email'    => $u['email'],
                'password' => Hash::make('password'),
                'role'     => 'user',
            ]);
        }

        // Create Sample News Articles (admin-created → 0 feedback)
        $newsArticles = [
            [
                'title'       => 'Union Budget 2025: Key Highlights on Infrastructure and Education',
                'content'     => 'The Union Budget for 2025-26 has been presented in Parliament with major allocations towards infrastructure development, education reforms, and healthcare modernization. Finance Minister outlined a comprehensive plan to boost capital expenditure by 25%, with special focus on building highways, railways, and smart cities across India. The education sector received a significant boost with the allocation of Rs 1.2 lakh crore, aimed at implementing the National Education Policy effectively.',
                'category'    => 'Economy',
                'language'    => 'English',
                'source'      => 'The Hindu',
                'image'       => 'https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'Digital India Initiative Expands Rural Broadband Coverage to 500,000 Villages',
                'content'     => 'The Government of India has announced a significant expansion of the Digital India initiative, targeting broadband connectivity to over 500,000 villages by the end of 2026. The BharatNet project, which aims to bridge the digital divide between urban and rural India, has completed Phase III fiber optic cable laying in 12 states.',
                'category'    => 'Technology',
                'language'    => 'English',
                'source'      => 'NDTV',
                'image'       => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'Ayushman Bharat Scheme Crosses 50 Crore Beneficiaries Milestone',
                'content'     => 'The Ayushman Bharat Pradhan Mantri Jan Arogya Yojana (PM-JAY) has crossed a significant milestone of providing health coverage to 50 crore beneficiaries across India. The scheme, which provides free health insurance of Rs 5 lakh per family per year, has been instrumental in reducing out-of-pocket health expenditure for vulnerable families.',
                'category'    => 'Health',
                'language'    => 'English',
                'source'      => 'India Today',
                'image'       => 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'New Education Policy: 10,000 Atal Tinkering Labs Inaugurated Across India',
                'content'     => 'Under the New Education Policy 2020, the government has inaugurated 10,000 Atal Tinkering Labs in schools across India to foster innovation and scientific temper among students. These labs provide students access to modern equipment including 3D printers, robotics kits, and IoT devices.',
                'category'    => 'Education',
                'language'    => 'English',
                'source'      => 'Times of India',
                'image'       => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'PM Gati Shakti: Multi-modal Connectivity Projects Worth Rs 5 Lakh Crore Approved',
                'content'     => 'The PM Gati Shakti National Master Plan has approved multi-modal connectivity projects worth Rs 5 lakh crore, including expressways, dedicated freight corridors, and port expansion projects. The integrated infrastructure plan aims to reduce logistics costs from 14% to 8% of GDP.',
                'category'    => 'Infrastructure',
                'language'    => 'English',
                'source'      => 'Economic Times',
                'image'       => 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'Chandrayaan-4 Mission Gets Cabinet Approval with Rs 2,104 Crore Budget',
                'content'     => 'The Union Cabinet has approved the Chandrayaan-4 lunar mission with a budget of Rs 2,104 crore. ISRO plans to launch the mission by 2028, which will involve collecting lunar samples and returning them to Earth.',
                'category'    => 'Technology',
                'language'    => 'English',
                'source'      => 'Indian Express',
                'image'       => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'India Achieves Record 500 GW Renewable Energy Capacity Target Ahead of Schedule',
                'content'     => 'India has achieved its ambitious target of 500 GW renewable energy capacity ahead of the 2030 deadline. Solar energy contributes 280 GW while wind energy adds 150 GW to the total.',
                'category'    => 'Environment',
                'language'    => 'English',
                'source'      => 'Hindustan Times',
                'image'       => 'https://images.unsplash.com/photo-1509391366360-2e959784a276?w=600',
                'source_type' => 'admin',
            ],
            [
                'title'       => 'One Nation One Ration Card Scheme Enables 80 Crore Portable Transactions',
                'content'     => 'The One Nation One Ration Card scheme has facilitated over 80 crore portable transactions across India, enabling migrant workers and their families to access subsidized food grains from any fair price shop in the country.',
                'category'    => 'Politics',
                'language'    => 'English',
                'source'      => 'NDTV',
                'image'       => 'https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?w=600',
                'source_type' => 'admin',
            ],
        ];

        // Admin-created news gets 0 feedback — users will give real feedback
        foreach ($newsArticles as $i => $article) {
            $article['published_at'] = Carbon::now()->subDays(count($newsArticles) - $i)->subHours(rand(1, 12));
            News::create($article);
        }
    }
}
