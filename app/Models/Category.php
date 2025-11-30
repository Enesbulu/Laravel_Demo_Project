<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Config;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property bool $is_active
 * @property int|null $parent_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */

class Category extends Model
{

    use HasFactory, HasTranslations, HasSlug;

    public $translatable = [
        "name",
        "description",
        "slug"
    ];

    protected $fillable = [
        "name",
        "parent_id",
        "slug",
        "description",
        "is_active",
        "full_path",
        "sort_order",
    ];

    protected $casts = [
        'name' => 'string',
        'slug' => 'string',
        'description' => 'string',
        'is_active' => 'boolean',
        'full_path' => 'string',
    ];

    protected $attributes = [
        'is_active' => true,
    ];  

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');//->orderBy('short_order');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function parentRecursive()
    {
        return $this->parent()->with('parentRecursive');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * DİNAMİK HİYERARŞİ (ACCESSOR)
     * Veritabanında saklamak yerine, o anki dile göre anlık hesaplar.
     * Blade'de kullanımı: {{ $category->full_path }}
     */
    public function getFullPathAttribute(): string
    {
        $path = [];

        $path[] = $this->name; // 1. Mevcut kategorinin adını al (Spatie o anki dili otomatik seçer!)
        $parent = $this->parent; // 2. Üst kategorilere tırman (parentRecursive ile hafızadan gelir)
        while ($parent) {
            array_push($path, $parent->name);    // Üst kategorinin adını dizinin BAŞINA ekle
            $parent = $parent->parent;
        }
        return implode(' > ', array_reverse($path));
    }
    protected static function booted(): void {}
}
