<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::create('food_meal', function (Blueprint $table) {
			$table->id();
			$table->foreignId('food_id')->references('id')->on('food')->constrained()->onDelete('restrict');
			$table->foreignId('meal_id')->references('id')->on('meals')->constrained()->onDelete('cascade');
			$table->double('user_serving_size', 10, 6);
			$table->timestamps();

			$table->unique(['food_id', 'meal_id']);
		});
	}

	public function down() : void
	{
		Schema::dropIfExists('food_meal');
	}
};
