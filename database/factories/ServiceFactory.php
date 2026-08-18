<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $provider = User::query()->where('user_type', 'provider')->inRandomOrder()->first() ?? User::factory()->create([
            'user_type' => 'provider',
        ]);

        return [
            'provider_id' => $provider->id,
            'category_id' => Category::query()->inRandomOrder()->first()?->id ?? Category::factory()->create()->id,
            'city_id' => $provider->city_id,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(50, 500),
            'currency' => 'SAR',
            'image' => 'services/' . $this->faker->image('public/storage', 640, 480, null, false),
            'rating_avg' => $this->faker->randomFloat(2, 3, 5),
            'reviews_count' => $this->faker->numberBetween(0, 50),
            'is_featured' => $this->faker->boolean(30),
            'is_available' => true,
            'status' => 'active',
        ];
    }
}
