<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizer1 = User::first();
        $organizer2 = User::skip(1)->first();

        $events = [
            [
                "organizer_id" => $organizer1->id,
                "title" => "Beach Cleaning",
                "description" => "Join us in cleaning the local beach and protecting marine life.",
                "start_date" => now()->addDays(5),
                "end_date" => now()->addDays(5)->addHours(4),
                "type" => "simple",
                "capacity" => 30,
                "location" => "Helsinki Beach",
                "tags" => "environment,cleanup,community",
                "requirements" => "Must be able to lift 10 kg, comfortable outdoors",
                "responsibilities" => "Collect trash, sort recyclables, assist team leaders",
                "bring_wear" => "Comfortable clothes, gloves, water bottle",
                "is_free" => true,
                "price" => null,
                "photo" => "beach-cleaning.jpg",
                "status" => "published",
            ],
            [
                "organizer_id" => $organizer2->id,
                "title" => "Tree Planting",
                "description" => "Help us plant trees and improve the environment. Volunteers will receive a Volunteerio t-shirt as a thank you for their participation.",
                "start_date" => now()->addDays(10),
                "end_date" => now()->addDays(10)->addHours(3),
                "type" => "simple",
                "capacity" => 50,
                "location" => "Central Park",
                "tags" => "nature,planting,volunteer",
                "requirements" => "Comfortable with gardening tools, wearing gloves",
                "responsibilities" => "Plant trees, water them",
                "bring_wear" => "Comfortable clothes, gloves, water bottle",
                "is_free" => true,
                "price" => null,
                "photo" => "tree-planting.jpg",
                "status" => "published",
            ],
            [
                "organizer_id" => $organizer1->id,
                "title" => "Community Garden Maintenance",
                "description" => "Assist in maintaining a local community garden. Price is for coffee and snacks provided during the event. Volunteers will receive a Volunteerio t-shirt as a thank you for their participation.",
                "start_date" => now()->addDays(7),
                "end_date" => now()->addDays(7)->addHours(5),
                "type" => "sectioned",
                "capacity" => null,
                "location" => "Green District",
                "tags" => "gardening,community,environment",
                "requirements" => "Experience with gardening preferred, able to stand for long periods",
                "responsibilities" => "Water plants, weed garden beds, assist visitors",
                "bring_wear" => "Comfortable shoes, gloves, hat",
                "is_free" => false,
                "price" => 3.00,
                "photo" => "gardening.jpg",
                "status" => "published",
            ],
            [
                "organizer_id" => $organizer2->id,
                "title" => "Environmental Awareness Workshop",
                "description" => "Educating the community about sustainability and eco-friendly practices.",
                "start_date" => now()->addDays(14),
                "end_date" => now()->addDays(14)->addHours(2),
                "type" => "sectioned",
                "capacity" => null,
                "location" => "City Hall",
                "tags" => "workshop,education,environment",
                "requirements" => "Interest in sustainability, public speaking encouraged",
                "responsibilities" => "Just be present and engage in discussions",
                "bring_wear" => "No specific attire required, but comfortable clothing recommended",       
                "is_free" => true,
                "price" => null,
                "photo" => "environment-workshop.jpg",
                "status" => "published",
            ],
            [
                "organizer_id" => $organizer1->id,
                "title" => "Cooking Workshop",
                "description" => "Learn to cook healthy and delicious meals in a fun, hands-on workshop. Price is for ingredients and materials used during the workshop.",
                "start_date" => now()->addDays(12),
                "end_date" => now()->addDays(12)->addHours(3),
                "type" => "simple",
                "capacity" => 20,
                "location" => "Community Center Kitchen",
                "tags" => "cooking, food, workshop",
                "requirements" => "None, suitable for all ages",
                "responsibilities" => "Participate in cooking activities, follow instructions",
                "bring_wear" => "Comfortable clothing, closed-toe shoes",
                "is_free" => false,
                "price" => 10.00,
                "photo" => "cooking-workshop.jpg",
                "status" => "published"
            ],
            [
                "organizer_id" => $organizer2->id,
                "title" => "River Cleanup Drive",
                "description" => "Join the community to clean up the riverbanks and help wildlife thrive.",
                "start_date" => now()->addDays(9),
                "end_date" => now()->addDays(9)->addHours(4),
                "type" => "simple",
                "capacity" => 40,
                "location" => "Vantaa River",
                "tags" => "environment,cleanup,volunteer",
                "requirements" => "Wear waterproof boots, gloves provided, Volunteerio t-shirt provided",
                "responsibilities" => "Collect trash, sort recyclables, assist team leaders",
                "bring_wear" => "Waterproof boots, comfortable clothes, gloves provided",
                "is_free" => true,
                "price" => null,
                "photo" => "river-cleanup.jpg",
                "status" => "published",
            ],
            [
                "organizer_id" => $organizer1->id,
                "title" => "Urban Gardening Workshop",
                "description" => "Learn how to grow vegetables and herbs in small urban spaces. Price is for materials used during the workshop.",
                "start_date" => now()->addDays(11),
                "end_date" => now()->addDays(11)->addHours(3),
                "type" => "simple",
                "capacity" => 25,
                "location" => "Downtown Community Center",
                "tags" => "gardening,urban,workshop",
                "requirements" => "Bring a notebook, gloves recommended, Volunteerio t-shirt provided",
                "responsibilities" => "Participate in gardening activities, follow instructions",
                "bring_wear" => "Comfortable clothing, gloves recommended",
                "is_free" => false,
                "price" => 5.00,
                "photo" => "urban-gardening.jpg", 
                "status" => "published",
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}