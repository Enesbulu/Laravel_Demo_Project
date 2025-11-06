<?php
// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;



class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::all();
        // return view('categories.index',compact('categories'));
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Best Practice: Alt kategori seçimi için sadece ID ve Name alanlarını alarak hafifletiyoruz.
        // prepend() ile "Ana Kategori" seçeneğini (ID: 0) en başa ekliyoruz.
        $parentCategories = Category::pluck('name', 'id')
            ->prepend('Ana Kategori (Yok)', 0);

        return view('categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        // 1. Veri Doğrulama (Validation)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            // parent_id'nin null olabilmesine izin verilir ve veritabanında var olup olmadığı kontrol edilir.
            'parent_id' => ["nullable", "numeric", Rule::in(array_merge([0], Category::pluck("id")->toArray()))],
            'description' => 'nullable|string',
            // Checkbox'tan 1 veya 0 gelebilir, ama Modeldeki $casts zaten bunu boolean yapar.
            'is_active' => 'nullable|in:1,0',
        ]);

        // 2. İş Mantığı: Slug Otomasyonu
        $validated['slug'] = Str::slug($validated['name']);

        // MİMARİ KARAR: parent_id 0 gelirse (Ana Kategori seçeneği), veritabanına NULL olarak kaydedilmesi gerekir.
        // exists:categories,id kuralı 0 ID'yi kabul etmeyeceği için, bu kontrolü yapıyoruz.
        if (isset($validated['parent_id']) && $validated['parent_id'] == 0) {
            $validated['parent_id'] = null;
        }

        // 3. Kayıt işlemi (Mass Assignment güvenliği $fillable tarafından sağlanır)
        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::where('id', '!=', $category->id)->pluck('name', 'id');
        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateCategoryRequest $request, Category $categoryId)
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // 1. Veri Doğrulama (Validation)
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            // parent_id'nin null olabilmesine izin verilir ve veritabanında var olup olmadığı kontrol edilir.
            'parent_id' => 'nullable|numeric|exists:categories,id',
            'description' => 'nullable|string',
            // Checkbox'tan 1 veya 0 gelebilir, ama Modeldeki $casts zaten bunu boolean yapar.
            'is_active' => 'nullable|in:0,1',
        ]);

        // 2. İş Mantığı: Slug Otomasyonu
        $validated['slug'] = Str::slug($validated['name']);

        // MİMARİ KARAR: parent_id 0 gelirse (Ana Kategori seçeneği), veritabanına NULL olarak kaydedilmesi gerekir.
        // exists:categories,id kuralı 0 ID'yi kabul etmeyeceği için, bu kontrolü yapıyoruz.
        if (isset($validated['parent_id']) && $validated['parent_id'] == 0) {
            $validated['parent_id'] = null;
        }

        // 3. Kayıt işlemi (Mass Assignment güvenliği $fillable tarafından sağlanır)
        $category->update($validated);

        // return redirect()->route('categories.index')
        //     ->with('success', 'Kategori başarıyla oluşturuldu.');
        // $category = Category::findOrFail($categoryId);
        // $category->update($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Category $category)
    {
        $categoryName = $category->name;
        $category->delete();
        return redirect()->route('categories.index')->with('success', $categoryName . " başarı ile silindi.");  //ternary if ile "ve alt kategorileri" yazılacak.
    }
}
