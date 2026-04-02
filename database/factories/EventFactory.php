<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['simple', 'sectioned']);
        $isFree = $this->faker->boolean();

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'start_date' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+1 month', '+2 months'),
            'type' => $type,
            'location' => $this->faker->city(),
            'capacity' => $type === 'simple' ? $this->faker->numberBetween(10, 100) : null,
            'is_free' => $isFree,
            'price' => $isFree ? null : $this->faker->randomFloat(2, 5, 100),
            'status' => $this->faker->randomElement(['draft', 'published', 'cancelled']),
            'organizer_id' => User::factory()->state(fn () => ['role' => 'organizer']), // ensures an organizer exists
        ];
    }
}