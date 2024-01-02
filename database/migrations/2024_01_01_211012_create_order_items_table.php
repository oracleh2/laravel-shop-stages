<?php

use Domain\Order\Models\Order;
use Domain\Product\Models\OptionValue;
use Domain\Product\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->decimal('price', 12, 2)->default(0);

            $table->unsignedInteger('quantity')->default(1);

            $table->timestamps();
        });

        Schema::create('order_item_option_value', function (Blueprint $table) {
           $table->id();

           $table->foreignIdFor(Order::class)
               ->constrained()
               ->cascadeOnDelete()
               ->cascadeOnUpdate();

           $table->foreignIdFor(OptionValue::class)
               ->constrained()
               ->cascadeOnDelete()
               ->cascadeOnUpdate();

           $table->timestamps();
        });
    }
    public function down(): void
    {
        if(!app()->isProduction()) {
            Schema::dropIfExists('order_items');
            Schema::dropIfExists('order_item_option_value');
        }
    }
};
