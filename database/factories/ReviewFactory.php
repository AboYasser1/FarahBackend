<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        $service = Service::query()->inRandomOrder()->first() ?? Service::factory()->create();
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence(),
        ];
    }
}
