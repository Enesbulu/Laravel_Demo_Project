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
        $electronics = Category::factory()->create(['name' => 'Elektronik', 'slug' => 'elektronik']);
        $fashion = Category::factory()->create(['name' => 'Moda', 'slug' => 'moda']);
        $books = Category::factory()->create(['name' => 'Kitaplar', 'slug' => 'kitaplar']);

        echo "Seviye 1 Kategoriler Oluşturuldu.\n";

        // 2. Birinci Seviye Alt Kategorileri Oluşturma (Seviye 2)
        $phones = Category::factory()->withParent($electronics->id)->create(['name' => 'Akıllı Telefonlar']);
        $laptops = Category::factory()->withParent($electronics->id)->create(['name' => 'Dizüstü Bilgisayarlar']);
        
        $menFashion = Category::factory()->withParent($fashion->id)->create(['name' => 'Erkek Giyim']);
        $womenFashion = Category::factory()->withParent($fashion->id)->create(['name' => 'Kadın Giyim']);

        echo "Seviye 2 Kategoriler Oluşturuldu.\n";

        // 3. İkinci Seviye Alt Kategorileri Oluşturma (Seviye 3)
        Category::factory()->withParent($phones->id)->create(['name' => 'iOS Telefonlar']);
        Category::factory()->withParent($phones->id)->create(['name' => 'Android Cihazlar']);

        Category::factory()->withParent($laptops->id)->create(['name' => 'Oyun Bilgisayarları']);

        Category::factory()->withParent($menFashion->id)->create(['name' => 'Erkek Ceketler']);

        echo "Seviye 3 Kategoriler Oluşturuldu.\n";
        
        // Rastgele 5 kategori daha ekleyerek çeşitliliği artırabiliriz
        Category::factory()->count(5)->create();
        
        echo "Toplam " . Category::count() . " kategori oluşturuldu.\n";
    }
}
