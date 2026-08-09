<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	public function up() : void
	{
		Schema::create('trackables', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('slug')->unique();
			$table->string('units', 2)->nullable();
		});
	}

	public function down() : void
	{
		Schema::dropIfExists('trackables');
	}
};
