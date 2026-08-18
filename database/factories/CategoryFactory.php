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
            'image' => 'categories/' . $this->faker->image('public/storage', 640, 480, null, false),
            'parent_id' => null,
            'status' => 'active',
        ];
    }
}
