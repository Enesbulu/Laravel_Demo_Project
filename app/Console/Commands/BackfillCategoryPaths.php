<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class BackfillCategoryPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:backfill-category-paths';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the full_path column for all existing categories.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $categories = Category::all();
        $categories = Category::with('parentRecursive')->get();
        $updateCount = 0;
        $categoriesToUpdate = Category::whereNull('full_path')->get();
        
        foreach ($categories as $category) {
            $category->save();
        }

        $this->info("Doldurma Tamamndı: $updateCount");
        return 0;
    }
}
