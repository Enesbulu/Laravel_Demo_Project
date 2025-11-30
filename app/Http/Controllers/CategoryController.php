<?php
// app/Http/Controllers/CategoryController.php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::all();
        //  dd($categories);
        // return view('categories.index',compact('categories'));
        $categories = Category::whereNull('parent_id')->with([
            'childrenRecursive',
            'parentRecursive'
        ])->paginate(10);
        // dd($categories);
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

            'name' => 'required|array',
            'name.tr' => 'required|string|max:255|unique:categories,name->tr',
            'name.en' => 'required|string|max:255|unique:categories,name->en',

            // parent_id'nin null olabilmesine izin verilir ve veritabanında var olup olmadığı kontrol edilir.
            'parent_id' => ["nullable", "numeric", Rule::in(array_merge([0], Category::pluck("id")->toArray()))],

            'description' => 'nullable|array',
            'description.*' => 'nullable|string',

            // Checkbox'tan 1 veya 0 gelebilir, ama Modeldeki $casts zaten bunu boolean yapar.
            'is_active' => 'nullable|in:1,0',

            // 'slug' => 'required|array',
            // 'slug.tr' => 'required|string|max:255|unique:categories,slug->tr',
            // 'slug.en' => 'required|string|max:255|unique:categories,slug->en',
        ]);
        // dd($validated);  
        // 2. İş Mantığı: Slug Otomasyonu
        // $validated['slug'] = Str::slug($validated['name']);
        if (empty($validated['slug']['tr']))
            $validated['slug']['tr'] = Str::slug($validated['name']['tr']);
        if (empty($validated['slug']['en']))
            $validated['slug']['en'] = Str::slug($validated['name']['en']);

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
    // public function edit(Category $category)
    public function edit(Request $request, $slug)
    {
        $locale = App::getLocale(); //seçili dili çeker.
        $category = Category::where("slug->{$locale}", $slug)->firstOrFail();

        $parentCategories = Category::where('id', '!=', $category->id)->pluck('name', 'id')->prepend('Ana Kategori (Yok)', 0);  //

        return view('categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateCategoryRequest $request, Category $categoryId)
    public function update(UpdateCategoryRequest $request, string $slug)
    {
        $locale = App::getLocale();
        //    NewsItem::whereLocales('name', ['en', 'nl'])->get();      //-----!!!!!!!!!!!!!
        $category = Category::where("slug->{$locale}", $slug)->firstOrFail();
        $localedValidate = $category->getTranslation('slug', $locale);

        // dd($request);
        // 1. Veri Doğrulama (Validation)
        $validated = $request->validate([

            // parent_id'nin null olabilmesine izin verilir ve veritabanında var olup olmadığı kontrol edilir.
            #region //parent_id
            'parent_id' => ['nullable', 'numeric', Rule::in(array_merge([0], Category::pluck('id')->toArray()))],  //'exists:categories,id'], //'nullable|numeric|exists:categories,id',
            #endregion

            #region //is_active
            // Checkbox'tan 1 veya 0 gelebilir, ama Modeldeki $casts zaten bunu boolean yapar.

            'is_active' => ['nullable', 'in:0,1'], //'nullable|in:0,1',
            #endregion

            #region //name
            'name' => ['required', 'array'], //'required|array',
            'name.tr' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name->tr')->ignore($category->id),
            ], //['required|string|max:255', Rule::unique('categories', 'name->tr')->ignore($category->id)],
            'name.en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name->en')->ignore($category->id),
            ], //['required|string|max:255', Rule::unique('categories', 'name->en')->ignore($category->id)],
            #endregion

            #region //slug
            'slug' => ['required', 'array'], //'required|array',
            'slug.tr' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug->tr')->ignore($category->id),
            ], //['required|string|max:255', Rule::unique('categories', 'slug->tr')->ignore($category->id)],
            'slug.en' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'slug->en')->ignore($category->id),
            ], // ['required|string|max:255', Rule::unique('categories', 'slug->en')->ignore($category->id)],
            #endregion

            #region //description
            'description'   => ['nullable', 'array'],
            //'description' => 'nullable|array',
            'description.*' => ['nullable', 'string'],
            //'description.*' => 'nullable|string',
            #endregion
        ]);

        // 2. İş Mantığı: Slug Otomasyonu
        // $validated['slug'] = Str::slug($validated['name']);

        #region //slug otomasyonu
        if (empty($validated['slug']['tr']))
            $validated['slug']['tr'] = Str::slug($validated['name']['tr']);
        if (empty($validated['slug']['en']))
            $validated['slug']['en'] = Str::slug($validated['name']['en']);
        #endregion

        // MİMARİ KARAR: parent_id 0 gelirse (Ana Kategori seçeneği), veritabanına NULL olarak kaydedilmesi gerekir.
        // exists:categories,id kuralı 0 ID'yi kabul etmeyeceği için, bu kontrolü yapıyoruz.
        if (isset($validated['parent_id']) && $validated['parent_id'] == 0) {
            $validated['parent_id'] = null;
        }
        // 3. Kayıt işlemi (Mass Assignment güvenliği $fillable tarafından sağlanır)


        $category->update($validated);

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

    public function reorder(Request $request)
    {
        $request->validate([
            'tree' => ['required', 'array'],
        ]);
        DB::transaction(function () use ($request) {
            $this->saveTree($request->tree, null);
        });
        return response()->json(['status' => 'success', 'message' => 'Kategori sıralaması başarıyla güncellendi.']);
    }


    private function saveTree(array $tree, $parentId = null)
    {
        foreach ($tree as $index => $node) {
            $category = Category::findOrFail($node['id']);
            $category->update([
                'parent_id' => $parentId,
                'order' => $index,
            ]);

            if (isset($node['children']) && is_array($node['children'])) {  //?
                $this->saveTree($node['children'], $category->id);
            }
        }
    }
}
