<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Benzersiz ve rastgele kelimelerden oluşan bir isim üretelim
        $tr_name = $this->faker->unique()->words($this->faker->numberBetween(1, 3), true) . ' Kategori';
        $tr_slug = Str::slug($tr_name);

        $en_name = $this->faker->unique()->words($this->faker->numberBetween(1, 3), true) . ' Category';
        $en_slug = Str::slug($en_name);

        return [
            'name' => [
                'tr' => $tr_name,
                'en' => $en_name,
            ],
            'slug' => [
                'tr' => $tr_slug,
                'en' => $en_slug,
            ],
            'description' => [
                'tr' => $this->faker->sentence(10),
                'en' => $this->faker->sentence(10),
            ],
            'is_active' => $this->faker->boolean(90), // %90 ihtimalle aktif olsun
            'parent_id' => null, // Varsayılan olarak ana kategori
        ];
    }

    public function withParent(int $parentId): Factory
    {
        return $this->state(fn(array $attributes) => [
            'parent_id' => $parentId
        ]);
    }
}
