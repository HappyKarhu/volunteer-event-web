<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Default state (random user)
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

            // role (random by default)
            'role' => fake()->randomElement(['organizer', 'volunteer']),

            // optional fields
            'contact_email' => fake()->safeEmail(),
            'company_name' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'website' => fake()->url(),
            'bio' => fake()->sentence(),
        ];
    }

    /**
     * Organizer state
     */
    public function organizer(): static
    {
        return $this->state(fn () => [
            'role' => 'organizer',
            'company_name' => fake()->company(),
            'bio' => null,
        ]);
    }

    /**
     * Volunteer state
     */
    public function volunteer(): static
    {
        return $this->state(fn () => [
            'role' => 'volunteer',
            'company_name' => null,
            'bio' => fake()->sentence(),
            'skills' => fake()->words(3, true),
        ]);
    }

    /**
     * Fixed test organizer (easy login)
     */
    public function testOrganizer(): static
    {
        return $this->state(fn () => [
            'name' => 'Test Organizer',
            'email' => 'organizer@test.com',
            'role' => 'organizer',
            'bio' => 'This is a test organizer account.',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Fixed test volunteer (easy login)
     */
    public function testVolunteer(): static
    {
        return $this->state(fn () => [
            'name' => 'Test Volunteer',
            'email' => 'volunteer@test.com',
            'role' => 'volunteer',
            'password' => Hash::make('password'),
        ]);
    }

    /**
     * Unverified email state
     */
    public function unverified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => null,
        ]);
    }
}