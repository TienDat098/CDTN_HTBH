<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Danh sách ngành hàng tạp hóa
        $categories = ['Gia vị', 'Đồ uống', 'Bánh kẹo', 'Thực phẩm khô', 'Đông lạnh', 'Sữa & Bơ', 'Hóa mỹ phẩm', 'Đồ gia dụng'];
        $name = $this->faker->unique()->randomElement($categories);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'status' => 1
        ];
    }
}
