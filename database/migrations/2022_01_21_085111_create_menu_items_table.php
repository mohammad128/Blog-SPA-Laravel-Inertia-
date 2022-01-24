<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('text');
            $table->string('href');
            $table->string('icon')->nullable();
            $table->boolean('external_link')->default(false);
//            $table->unsignedBigInteger('parent_id')->nullable()->default(null);
//            $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->unsignedBigInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->timestamps();
            $table->nestedSet();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
