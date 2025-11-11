<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    use HasFactory;
    use HasTranslations;

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
        "full_path"
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
        return $this->hasMany(Category::class, 'parent_id');
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

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            $defaultLocale = Config::get('app.locale', 'tr');
            $nameInDefaultLocale = '';

            if (is_array($category->name)) {
                $nameInDefaultLocale = $category->name[$defaultLocale] ?? reset($category->name);
            } else {
                $nameInDefaultLocale = $category->getTranslation('name', $defaultLocale);
            }

            if (empty($category->parent_id))
                $category->full_path = $nameInDefaultLocale;
            else {
                $parent = Category::find($category->parent_id);

                if ($parent)
                    $category->full_path = $parent->full_path . '>' . $nameInDefaultLocale;
                else
                    $category->full_path = $nameInDefaultLocale;
            }
        });
    }
}
