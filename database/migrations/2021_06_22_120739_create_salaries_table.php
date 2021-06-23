<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->integer('salary');
            $table->integer('norma')->default(22);
            $table->integer('count_worked_days');
            $table->boolean('tax_ms');
            $table->integer('year');
            $table->integer('mouth');
            $table->boolean('is_pensioner');
            $table->boolean('is_invalid');
            $table->integer('invalid_group')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salaries');
    }
}