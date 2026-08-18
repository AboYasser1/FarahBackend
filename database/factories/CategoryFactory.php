<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'Cleaning',
            'Repair',
            'Beauty',
            'Home',
            'Delivery',
            'Tutoring',
        ]);

        return [
            'name' => $name,
            'slug' => strtolower(str_replace(' ', '-', $name)),
            'image' => 'categories/default.jpg',
            'status' => 'active',
        ];
    }
}
