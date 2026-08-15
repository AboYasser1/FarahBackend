<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->randomElement([
            'كوافير',
            'تصوير',
            'حفل',
            'مزاد',
            'مكتبة',
            'ملابس',
            'خدمات',
            'تزيين',
        ]);

        return [
            'name' => $name,
            'slug' => str()->slug($name),
            'image' => null,
            'parent_id' => null,
            'status' => 'active',
        ];
    }
}
