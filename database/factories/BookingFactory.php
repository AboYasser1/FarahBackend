<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\ProviderProfile;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $user = User::query()->inRandomOrder()->first() ?? User::factory()->create();
        $service = Service::query()->inRandomOrder()->first() ?? Service::factory()->create();
        $provider = ProviderProfile::query()->inRandomOrder()->first() ?? ProviderProfile::factory()->create();

        return [
            'user_id' => $user->id,
            'service_id' => $service->id,
            'provider_id' => $provider->id,
            'booking_date' => $this->faker->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'booking_time' => $this->faker->time('H:i:s'),
            'total_price' => $this->faker->randomFloat(2, 200, 5000),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'notes' => $this->faker->sentence(),
        ];
    }
}
