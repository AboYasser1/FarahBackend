<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\ProviderProfile;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $provider = ProviderProfile::query()->inRandomOrder()->first() ?? ProviderProfile::factory()->create();
        $category = Category::query()->inRandomOrder()->first() ?? Category::factory()->create();

        $title = $this->faker->randomElement([
            'قاعة زفاف فاخرة',
            'حفل استقبال أنيق',
            'جلسة تصوير احترافية',
            'خدمة تجهيز مناسبة',
            'ديكور احتفالي',
            'صالة حفلات',
            'خدمة كافية',
            'تجهيزات مناسبة',
        ]);

        return [
            'provider_id' => $provider->id,
            'category_id' => $category->id,
            'city_id' => $provider->city_id,
            'title' => $title,
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 200, 5000),
            'currency' => 'ILS',
            'image' => null,
            'rating_avg' => $this->faker->randomFloat(2, 3, 5),
            'reviews_count' => $this->faker->numberBetween(0, 100),
            'is_featured' => $this->faker->boolean(30),
            'is_available' => true,
            'status' => 'active',
        ];
    }
}
