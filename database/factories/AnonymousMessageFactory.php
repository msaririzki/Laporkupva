<?php

namespace Database\Factories;

use App\Models\AnonymousMessage;
use App\Models\Report;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AnonymousMessage>
 */
class AnonymousMessageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'report_id' => Report::factory(),
            'user_id' => null,
            'sender_type' => 'reporter',
            'body' => fake()->sentence(),
            'attachment_path' => null,
            'read_at' => null,
        ];
    }
}
