<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M2019_08_09_180214366414_InstallMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'menus', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('machineName');
                $table->boolean('deletable')->default(1);
                $table->timestamps();
            }
        );

        Schema::create(
            'menu_items', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('machineName')->unique();
                $table->boolean('deletable')->default(1);
                $table->string('url')->default('')->nullable();
                $table->integer('menu_id')->unsigned()->index();
                $table->foreign('menu_id')->references('id')->on('menus');
                $table->integer('parent_id')->unsigned()->index()->nullable();
                $table->foreign('parent_id')->references('id')->on('menu_items')->onDelete('set null');
                $table->integer('permission_id')->unsigned()->nullable();
                $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('set null');
                $table->integer('weight')->unsigned()->nullable();
                $table->boolean('active');
                $table->string('class')->nullable();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('menus');
    }
}
