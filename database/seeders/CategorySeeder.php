<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ana Kategorileri Oluşturma (Seviye 1)
        $electronics = Category::factory()->create([
            'name' => [
                'tr' => 'Elektronik',
                'en' => 'Electronics'
            ],
            'slug' => [
                'tr' => 'elektronik',
                'en' => 'electronics',
            ]
        ]);
        $fashion = Category::factory()->create([
            'name' => [
                'tr' => 'Moda',
                'en' => 'Fashion'
            ],
            'slug' => [
                'tr' => 'moda',
                'en' => 'fashion',
            ]
        ]);
        $books = Category::factory()->create([
            'name' => [
                'tr' => 'Kitaplar',
                'en' => 'Books'
            ],
            'slug' => [
                'tr' => 'kitaplar',
                'en' => 'books',
            ]
        ]);

        echo "Seviye 1 Kategoriler Oluşturuldu.\n";

        // 2. Birinci Seviye Alt Kategorileri Oluşturma (Seviye 2)
        $phones = Category::factory()->withParent($electronics->id)->create(['name' => [
            'tr' => 'Akıllı Telefonlar',
            'en' => 'Smartphones'
        ]]);
        $laptops = Category::factory()->withParent($electronics->id)->create(['name' => [
            'tr' => 'Dizüstü Bilgisayarlar',
            'en' => 'Laptops'
        ]]);

        $menFashion = Category::factory()->withParent($fashion->id)->create(['name' => [
            'tr' => 'Erkek Giyim',
            'en' => 'Men Fashion'
        ]]);
        $womenFashion = Category::factory()->withParent($fashion->id)->create(['name' => [
            'tr' => 'Kadın Giyim',
            'en' => 'Women Fashion'
        ]]);
        echo "Seviye 2 Kategoriler Oluşturuldu.\n";

        // 3. İkinci Seviye Alt Kategorileri Oluşturma (Seviye 3)
        Category::factory()->withParent($phones->id)->create(['name' => [
            'tr' => 'iOS Telefonlar',
            'en' => 'iOS Phones'
        ]]);
        Category::factory()->withParent($phones->id)->create(['name' => [
            'tr' => 'Android Cihazlar',
            'en' => 'Android Devices'
        ]]);

        Category::factory()->withParent($laptops->id)->create([
            'name' => [
                'tr' => 'Oyun Bilgisayarları',
                'en' => 'Gaming Laptops'
            ]
        ]);

        Category::factory()->withParent($menFashion->id)->create(['name' => [
            'tr' => 'Erkek Ceketler',
            'en' => 'Men Jackets'
        ]]);

        echo "Seviye 3 Kategoriler Oluşturuldu.\n";

        // Rastgele 5 kategori daha ekleyerek çeşitliliği artırabiliriz
        Category::factory()->count(10)->create();

        echo "Toplam " . Category::count() . " kategori oluşturuldu.\n";
    }
}
