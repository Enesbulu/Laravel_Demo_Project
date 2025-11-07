<?php

namespace Tests\Feature;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseTransactFions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use Database\Factories\CategoryFactory; // Factory'mizi dahil ediyoruz

class CategoryTest extends TestCase
{
    // Veritabanını her testten önce sıfırlar (temizler)
    // use RefreshDatabase;
    // use DatabaseTransactFions;
    // use DatabaseTransactions;

    /**
     * Test: Çoklu kategori verisinin oluşturulması ve index sayfasının doğrulanması.
     * Bu test, aynı zamanda 10'dan fazla kayıt oluşturarak sayfalama mantığını da test eder.
     */
    public function test_categories_can_be_created_and_listed_with_pagination(): void
    {
        // --- 1. ÇOKLU VERİ GİRİŞİ: 20 Adet Ana Kategori Oluşturma ---
        // Sayfalama sınırımızın (10) üzerine çıkmak için 20 ana kategori oluşturuyoruz.
        $mainCategories = Category::factory(20)->create([
            'parent_id' => null, // Ana kategori olduklarını belirtiyoruz
        ]);

        // --- 2. HİYERARŞİK VERİ GİRİŞİ: 10 Adet Alt Kategori Oluşturma ---
        // İlk Ana Kategorinin altına 10 adet alt kategori ekleyelim.
        $firstParent = $mainCategories->first();
        Category::factory(10)->create([
            'parent_id' => $firstParent->id,
        ]);

        $totalCategories = Category::count();
        $this->assertEquals(30, $totalCategories, "Veritabanında 30 kayıt olmalı.");

        // --- 3. SİSTEM TESTİ: Index Sayfası ve Sayfalama Kontrolü ---

        // İlk sayfayı ziyaret et (Varsayılan olarak sayfa 1)
        $response = $this->get(route('categories.index'));

        $response->assertStatus(200); // Sayfanın başarılı yüklendiğini kontrol et

        // View'a gönderilen paginated koleksiyonun 10 Ana Kategori içerdiğini kontrol et
        $response->assertViewHas('categories', function ($collection) {
            // Sadece ilk 10 Ana Kategorinin çekildiğini ve Sayfalama Objesi olduğunu kontrol eder.
            return $collection->count() === 10
                && $collection->total() === 20; // Toplam ana kategori sayısı
        });

        // Sayfa 2'yi ziyaret et ve kalan 10 Ana Kategoriyi kontrol et
        $responsePage2 = $this->get(route('categories.index', ['page' => 2]));

        $responsePage2->assertStatus(200);
        $responsePage2->assertViewHas('categories', function ($collection) {
            // Sayfa 2'de de 10 Ana Kategori olmalı
            return $collection->count() === 10;
        });

        // Görüntüleme Testi: View'da ilk ana kategorinin adının ve alt kategori adının geçtiğini kontrol et
        $response->assertSee($firstParent->name);
        $response->assertSee(Category::where('parent_id', $firstParent->id)->first()->name);
    }
}
