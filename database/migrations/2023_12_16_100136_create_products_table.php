<?php

use App\Models\Product;
use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title')
                ->fulltext();
            $table->string('slug')->unique();
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('on_home_page')->default(false);
            $table->integer('sorting')
                ->default(999);
            $table->text('description')
                ->fulltext()
                ->nullable();
            $table->foreignIdFor(Brand::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->json('json_properties')
                ->nullable();

            $table->timestamps();

            $table->fullText(['title', 'description']);
        });

        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Category::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(!app()->isProduction()) {
            Schema::dropIfExists('category_product');
            Schema::dropIfExists('products');
        }
    }
};
