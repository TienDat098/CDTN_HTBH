<?php

namespace Database\Factories;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Sản phẩm ' . $this->faker->words(2, true) . ' ' . $this->faker->randomNumber(3);
        $importPrice = $this->faker->numberBetween(10, 150) * 1000;
        return [
            // Tự động bốc random 1 ID từ bảng categories và brands để gán vào
            'category_id' => Category::inRandomOrder()->first()->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()->id ?? Brand::factory(),
            'barcode' => $this->faker->unique()->ean13(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'import_price' => $importPrice,
            'sell_price' => $importPrice * 1.3, // Bán lời 30%
            'unit' => $this->faker->randomElement(['Chai', 'Gói', 'Hộp', 'Lốc', 'Thùng']),
            'description' => $this->faker->realText(100),
            'status' => 1,
        ];
    }
}
