<?php

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
        Schema::create('orders_plants', function (Blueprint $table) {
            $table->foreignId("plant_id")->constrained("plants")->nullOnDelete();
            $table->foreignId("order_id")->constrained("orders")->nullOnDelete();
            $table->primary(["plant_id", "order_id"]);
            $table->integer("quantity");
            $table->decimal("price_total", 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders_plants');
    }
};
