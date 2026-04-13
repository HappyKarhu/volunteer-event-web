<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
        // Organizers
        [
            'name' => 'Alicia Green',
            'email' => 'alicgreen@example.com',
            'password' => bcrypt('password123'),
            'role' => 'organizer',
            'contact_email' => 'contact@green.org',
            'company_name' => 'GreenOrg',
            'phone' => '123-456-7890',
            'website' => 'https://greenorg.com',
            'logo' => 'logos/logo-greenorg.png',
            'bio' => 'GreenOrg is dedicated to organizing community clean-up events and promoting sustainability.',
        ],
        [
            'name' => 'Bob Blue',
            'email' => 'bobblue@example.com',
            'password' => bcrypt('password123'),
            'role' => 'organizer',
            'contact_email' => 'info@blueevents.com',
            'company_name' => 'Blue Events',
            'phone' => '987-654-3210',
            'website' => 'https://blueevents.com',
            'logo' => 'logos/logo-blueevents.png',
            'bio' => null,
        ],

        // Volunteers
        [
            'name' => 'Charlie Volunteer',
            'email' => 'charlie@example.com',
            'password' => bcrypt('password123'),
            'role' => 'volunteer',
            'contact_email' => 'charlie@example.com',
            'company_name' => null,
            'phone' => null,
            'website' => null,
            'logo' => 'avatars/default-avatar.png',
            'bio' => 'Passionate about environmental causes and community work.',
        ],
        [
            'name' => 'Diana Volunteer',
            'email' => 'diana@example.com',
            'password' => bcrypt('password123'),
            'role' => 'volunteer',
            'contact_email' => 'diana@example.com',
            'company_name' => null,
            'phone' => null,
            'website' => null,
            'logo' => 'avatars/avatar.jpg',
            'bio' => 'Enjoys teaching children and supporting local projects.',
        ],
];
// Save users to the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
