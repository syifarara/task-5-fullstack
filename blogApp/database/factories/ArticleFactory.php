<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),  // Tambahkan title
            'content' => $this->faker->paragraph(),
            'image' => $this->faker->imageUrl(640, 480, 'articles', true), // Tambahkan image
            'category_id' => Category::factory(), // Pastikan category_id ada
            'user_id' => User::factory(), // Pastikan user_id ada
        ];
    }
}
