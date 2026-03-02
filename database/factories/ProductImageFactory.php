<?php

namespace Database\Factories;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Tự động gán vào 1 sản phẩm bất kỳ
            'product_id' => Product::inRandomOrder()->first()->id ?? Product::factory(),
            'image_url' => 'https://picsum.photos/400?random=' . $this->faker->randomNumber(4),
            'is_primary' => 1,
        ];
    }
}
