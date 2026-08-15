<?php

namespace Database\Factories;

use App\Models\Favorite;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    protected $model = Favorite::class;

    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();
        $service = Service::query()->inRandomOrder()->first() ?? Service::factory()->create();

        return [
            'user_id' => $user->id,
            'service_id' => $service->id,
        ];
    }
}
