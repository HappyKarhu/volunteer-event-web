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

        [
            'name' => 'Test Organizer',
            'email' => 'testorganizer@test.com',
            'password' => bcrypt('password'),
            'role' => 'organizer',
            'contact_email' => 'testorganizer@test.com',
            'company_name' => 'Demo Organization',
            'phone' => null,
            'website' => null,
            'logo' => 'logos/default-logo.png',
            'bio' => 'Main demo organizer account used for testing event workflows.',
        ],

        // Volunteers
        [
            'name' => 'Volunteer One',
            'email' => 'volunteer1@test.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
            'contact_email' => 'volunteer1@test.com',
            'company_name' => null,
            'phone' => null,
            'website' => null,
            'logo' => 'avatars/default-avatar.png',
            'bio' => 'Eager to help in community events.',
        ],
        [
            'name' => 'Volunteer Two',
            'email' => 'volunteer2@test.com',
            'password' => bcrypt('password'),
            'role' => 'volunteer',
            'contact_email' => 'volunteer2@test.com',
            'company_name' => null,
            'phone' => null,
            'website' => null,
            'logo' => 'avatars/avatar.jpg',
            'bio' => 'Interested in workshops and volunteering.',
        ],
];
// Save users to the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
