<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountRulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_rules', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('discount_type_id');
            $table->foreign('discount_type_id')->references('id')->on('discount_types')->onDelete('cascade');
            $table->unsignedInteger('category')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->float('threshold')->nullable();
            $table->unsignedInteger('percentage')->nullable();
            $table->enum('operator', config('app.discount_rules.operators'))->nullable();
            $table->enum('applies', config('app.discount_rules.applies'))->nullable();
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
        Schema::dropIfExists('discount_rules');
    }
}
